<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');


if (isset($_GET["action"])) {

    switch ($_GET["action"]) {
        case "listerRecettes":
            require_once(__DIR__ . '/listeRecette.php');
            break;

        case "infosRecettes":
            if (isset($_GET["id"])) {
                $index = $_GET["id"];

                // Recipe info Request
                $sql = "SELECT recette.id_recette, recette.nomRecette, recette.tempsPreparation, recette.instructions, recette.image,categorie.nomCategorie
                        FROM recette
                        INNER JOIN categorie ON recette.id_categorie = categorie.id_categorie
                        WHERE recette.id_recette = :id_recette";

                $detailStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
                $detailStatement->execute(["id_recette" => $index]);
                $details = $detailStatement->fetch();

                //Ingredients list Request
                $sql = "SELECT ingredient.nomIngredient, ingredient.uniteMesure, contenir.quantite, 
                            ROUND(SUM(ingredient.prixIngredient*contenir.quantite), 2) AS prixTotal
                        FROM ingredient
                        INNER JOIN contenir ON ingredient.id_ingredient = contenir.id_ingredient
                        INNER JOIN recette ON contenir.id_recette = recette.id_recette
                        WHERE recette.id_recette = :id_recette
                        GROUP BY ingredient.id_ingredient, nomIngredient, uniteMesure, contenir.quantite";

                $listIngredientStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
                $listIngredientStatement->execute(["id_recette" => $index]);
                $listIngredients = $listIngredientStatement->fetchAll();

                require_once(__DIR__ . '/infosRecette.php');

            } else {
                echo "Aucun ID trouvé";
            }
            break;

        case "ajoutRecette":
            require_once(__DIR__ . '/ajoutRecette.php');
            break;

        case "addRecette":
            if (isset($_POST["submit"])) {
                $nomRecette = filter_var($_POST["nomRecette"], FILTER_SANITIZE_SPECIAL_CHARS);
                $tempsPreparation = filter_var($_POST["tempsPreparation"], FILTER_SANITIZE_NUMBER_INT);
                $instructions = filter_var($_POST["instructions"], FILTER_SANITIZE_SPECIAL_CHARS);
                $id_categorie = filter_var($_POST["id_categorie"], FILTER_SANITIZE_SPECIAL_CHARS);
                $image = filter_var($_POST["image"], FILTER_SANITIZE_SPECIAL_CHARS);
                $ingredients = filter_var($_POST["ingredients"], FILTER_SANITIZE_SPECIAL_CHARS);
                $quantites = filter_var(array_filter($_POST["quantite"]), FILTER_SANITIZE_NUMBER_FLOAT);


                $sql = "INSERT INTO recette (nomRecette, tempsPreparation, instructions, id_categorie, image)
                        VALUES (:nomRecette, :tempsPreparation, :instructions, :id_categorie, :image)";

                $formStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

                $formStatement->bindValue(':nomRecette', $nomRecette);
                $formStatement->bindValue(':tempsPreparation', $tempsPreparation);
                $formStatement->bindValue(':instructions', $instructions);
                $formStatement->bindValue(':id_categorie', $id_categorie);
                $formStatement->bindValue(':image', $image);

                $formStatement->execute();

                $ingredients_quantites = [];
                foreach ($ingredients as $id_ingredient) {
                    $ingredients_quantites[] = [$id_ingredient, $quantites[$id_ingredient - 1]];
                }

                $inserted_id = $mysqlClient->lastInsertId();

                $sql = "INSERT INTO contenir (id_recette, id_ingredient, quantite)
                            VALUES (:id_recette, :id_ingredient, :quantite)";


                foreach ($ingredients_quantites as $iq) {
                    $checkboxStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

                    $checkboxStatement->execute([
                        "id_recette" => $inserted_id,
                        "id_ingredient" => $iq[0],
                        "quantite" => $iq[1],
                    ]);
                }
                header("Location:traitement.php?action=listerRecettes");
                die;

            }
            break;

        case "updateRecette":
            if (isset($_GET['id'])) {
                $index = $_GET["id"];

                // Recipe info Request
                $sql = "SELECT recette.id_recette, recette.nomRecette, recette.tempsPreparation, recette.instructions, recette.id_categorie, recette.image, categorie.nomCategorie
                        FROM recette
                        INNER JOIN categorie ON recette.id_categorie = categorie.id_categorie
                        WHERE recette.id_recette = :id_recette";

                $detailStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
                $detailStatement->execute(["id_recette" => $index]);
                $details = $detailStatement->fetch();

                $sql = "SELECT contenir.id_recette, contenir.id_ingredient, contenir.quantite, ingredient.nomIngredient
                FROM contenir
                INNER JOIN ingredient ON contenir.id_ingredient = ingredient.id_ingredient
                INNER JOIN recette ON contenir.id_recette = recette.id_recette
                WHERE recette.id_recette = :id_recette";

                $ingredientDetailStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
                $ingredientDetailStatement->execute(["id_recette" => $index]);
                $ingredientDetails = $ingredientDetailStatement->fetchAll();

            }
            require_once(__DIR__ . '/updateRecette.php');

            break;

        case "modifierRecette":
            if (isset($_GET['id'])) {
                $index = $_GET['id'];

                if (isset($_POST["submit"])) {
                    $nomRecette = filter_var($_POST["nomRecette"], FILTER_SANITIZE_SPECIAL_CHARS);
                    $tempsPreparation = filter_var($_POST["tempsPreparation"], FILTER_SANITIZE_NUMBER_INT);
                    $instructions = filter_var($_POST["instructions"], FILTER_SANITIZE_SPECIAL_CHARS);
                    $id_categorie = filter_var($_POST["id_categorie"], FILTER_SANITIZE_SPECIAL_CHARS);
                    $image = filter_var($_POST["image"], FILTER_SANITIZE_SPECIAL_CHARS);
                    $ingredients = filter_var($_POST["ingredients"], FILTER_SANITIZE_SPECIAL_CHARS);
                    $quantites = filter_var(array_filter($_POST["quantite"]), FILTER_SANITIZE_NUMBER_INT);

                    $sql = "UPDATE recette
                            SET nomRecette = :nomRecette,
                                tempsPreparation = :tempsPreparation, 
                                instructions = :instructions, 
                                id_categorie = :id_categorie, 
                                image = :image
                            WHERE id_recette = :id_recette";

                    $updateStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

                    $updateStatement->execute([
                        'id_recette' => $index,
                        'nomRecette' => $nomRecette,
                        'tempsPreparation' => $tempsPreparation,
                        'instructions' => $instructions,
                        'id_categorie' => $id_categorie,
                        'image' => $image,
                    ]);

                    $ingredients_quantites = [];
                    foreach ($ingredients as $id_ingredient) {
                        $ingredients_quantites[] = [$id_ingredient, $quantites[$id_ingredient - 1]];
                    }

                    $sql = "DELETE FROM contenir WHERE id_recette = :id_recette";
                    $delStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
                    $delStatement->execute([
                        'id_recette' => $index,
                    ]);


                    $sql = "INSERT INTO contenir (id_recette, id_ingredient, quantite) VALUES (:id_recette, :id_ingredient, :quantite)
                    ON DUPLICATE KEY UPDATE 
                    id_ingredient = IF( id_recette = :id_recette, :id_ingredient, id_ingredient ), 
                    quantite = IF( id_recette = :id_recette, :quantite, quantite )";


                    foreach ($ingredients_quantites as $iq) {
                        $updateCheckboxStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

                        $updateCheckboxStatement->bindParam(':id_recette', $index);
                        $updateCheckboxStatement->bindParam(':id_ingredient', $iq[0]);
                        $updateCheckboxStatement->bindParam(':quantite', $iq[1]);

                        var_dump($index);

                        $updateCheckboxStatement->execute();
                    }
                }
                header("Location:traitement.php?action=listerRecettes");
                die;


            }
            break;


        case "ajoutIngredient":
            require_once(__DIR__ . '/ajoutIngredient.php');
            break;

        case "addIngredient":
            if (isset($_POST["submit"])) {
                $nomIngredient = filter_var($_POST["nomIngredient"], FILTER_SANITIZE_SPECIAL_CHARS);
                $prixIngredient = filter_var($_POST["prixIngredient"], FILTER_SANITIZE_NUMBER_FLOAT);
                $uniteMesure = filter_var($_POST["uniteMesure"], FILTER_SANITIZE_SPECIAL_CHARS);

                $sql = "INSERT INTO ingredient (nomIngredient, prixIngredient, uniteMesure)
                        VALUES (:nomIngredient, :prixIngredient, :uniteMesure)";

                $ingredientFormStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
                $ingredientFormStatement->execute([
                    "nomIngredient" => $nomIngredient,
                    "prixIngredient" => $prixIngredient,
                    "uniteMesure" => $uniteMesure,
                ]);

                header("Location:traitement.php?action=listerRecettes");
                die;

            }
            break;

        case "delete":
            if (isset($_GET["id"])) {
                $index = $_GET["id"];

                $sql = "DELETE FROM contenir WHERE id_recette = :id_recette";
                $deleteStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

                $deleteStatement->execute([
                    "id_recette" => $index,
                ]);

                $sql = "DELETE FROM recette WHERE id_recette = :id_recette";
                $deleteRecetteStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

                $deleteRecetteStatement->execute([
                    "id_recette" => $index,
                ]);

                header("Location:traitement.php?action=listerRecettes");
                die;
            }
            break;

        default:
            require_once(__DIR__ . '/error.php');
            break;

    }

} else {
    require_once(__DIR__ . '/listeRecette.php');
}
