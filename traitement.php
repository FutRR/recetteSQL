<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');




if (isset($_GET["action"])) {

    switch ($_GET["action"]) {
        case "listerRecettes":
            break;

        case "infosRecettes":
            if (isset($_GET["id"])) {
                $index = $_GET["id"];

                // Recipe info Request
                $sql = "SELECT recette.nomRecette, recette.tempsPreparation, recette.instructions, categorie.nomCategorie, ingredient.nomIngredient
                        FROM recette
                        INNER JOIN categorie ON recette.id_categorie = categorie.id_categorie
                        INNER JOIN contenir ON recette.id_recette = contenir.id_recette
                        INNER JOIN ingredient ON contenir.id_ingredient = ingredient.id_ingredient
                        WHERE recette.id_recette = :id_recette";

                $detailStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
                $detailStatement->execute(["id_recette" => $index]);
                $details = $detailStatement->fetch();

                //Ingredients list Request
                $sql = "SELECT ingredient.nomIngredient, uniteMesure, contenir.quantite
                        FROM ingredient
                        INNER JOIN contenir ON ingredient.id_ingredient = contenir.id_ingredient
                        INNER JOIN recette ON contenir.id_recette = recette.id_recette
                        WHERE recette.id_recette = :id_recette";

                $listIngredientStatement = $mysqlClient->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
                $listIngredientStatement->execute(["id_recette" => $index]);
                $listIngredients = $listIngredientStatement->fetchAll();

                ob_start();

                if ($details) {
                    echo "<h2>Détails de la recette : " . $details['nomRecette'] . "</h2>";
                    echo "<h3>Ingrédients : </h3><ul>";
                    foreach ($listIngredients as $ingredient) {
                        echo "<li>{$ingredient['nomIngredient']} - {$ingredient['quantite']} {$ingredient['uniteMesure']}</li>";
                    }
                    echo "</ul>";
                    echo "<h4>Instructions : </h4><p>{$details['instructions']}</p>";
                } else {
                    echo "Pas de résultats";
                }
            } else {
                echo "Aucun ID trouvé";
            }
            echo "<script> 
                    if (document.title != '{$details['nomRecette']}') {
                    document.title = '{$details['nomRecette']}';
                    }
                </script>";
            $content = ob_get_clean();

            require_once "template.php";

            break;
    }
}
