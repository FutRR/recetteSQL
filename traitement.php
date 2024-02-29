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
                $sql = "SELECT recette.nomRecette, recette.tempsPreparation, recette.instructions, recette.image,categorie.nomCategorie
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

        case "add":
            if (isset($_POST["submit"])) {
                $nomRecette = $_POST["nomRecette"];
                $tempsPreparation = $_POST["tempsPreparation"];
                $instructions = $_POST["instructions"];
                $id_categorie = $_POST["id_categorie"];
                $image = $_POST["image"];
                $id_ingredient = $_POST["id_ingredient"];
                $quantite = $_POST["quantite"];


                $sql = "INSERT INTO recette (nomRecette, tempsPreparation, instructions, id_categorie, image)
                        VALUES (:nomRecette, :tempsPreparation, :instructions, :id_categorie, :image)";

                $formStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

                $formStatement->bindValue(':nomRecette', $nomRecette);
                $formStatement->bindValue(':tempsPreparation', $tempsPreparation);
                $formStatement->bindValue(':instructions', $instructions);
                $formStatement->bindValue(':id_categorie', $id_categorie);
                $formStatement->bindValue(':image', $image);

                $inserted_id = $mysqlClient->lastInsertId();


                $sql = "INSERT INTO contenir (id_recette, id_ingredient, quantite)
                            VALUES (:id_recette, :id_ingredient, :quantite)";

                $checkboxStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

                $checkboxStatement->bindValue(':id_recette', $inserted_id);
                $checkboxStatement->bindValue(':id_ingredient', $id_ingredient);
                $checkboxStatement->bindValue(':quantite', $quantite);

                $checkboxStatement->execute();
                $formStatement->execute();
            }
            break;
    }

} else {
    require_once(__DIR__ . '/listeRecette.php');
}
