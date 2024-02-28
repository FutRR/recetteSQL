<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');




if (isset($_GET["action"])) {

    switch ($_GET["action"]) {
        case "listerRecettes":
            ob_start();
            foreach (getRecettes($recettes) as $recette) {
                echo "<article class='m-2'>
                    <a class='mt-5 link-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover'
                        href='traitement.php?action=infosRecettes&id={$recette['id_recette']}'>
                        {$recette['nomRecette']}
                    </a>
                    |
                    <a class='link-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover' href=''>
                        {$recette['nomCategorie']}
                    </a>
                    </article>";
            }
            echo "<a class='btn btn-warning' href='traitement.php?action=ajoutRecette'>Ajouter une recette</a>

                <script>
                    if (document.title != 'Recettes') {
                        document.title = 'Recettes';
                    } </script>";

            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }


            $content = ob_get_clean();
            require_once "index.php";
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

                //Starting Output buffering
                ob_start();

                if ($details) {
                    echo "<h2>Détails de la recette : " . $details['nomRecette'] . "</h2>";
                    if ($listIngredients) {
                        echo "<h3>Ingrédients : </h3><ul>";
                        foreach ($listIngredients as $ingredient) {
                            echo "<li>{$ingredient['nomIngredient']} - {$ingredient['quantite']} {$ingredient['uniteMesure']} | environ {$ingredient['prixTotal']} €</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<h3>Pas d'ingrédients</h3>";
                    }
                    echo "<h4>Instructions : </h4><p>{$details['instructions']}</p>";
                    echo "<img class='img-fluid' src='{$details['image']}' alt='Image de la recette'/>";
                } else {
                    echo "Pas de résultats";
                }
            } else {
                echo "Aucun ID trouvé";
            }
            echo "<script> 
            if (document.title != '{$details['nomRecette']}') {
            document.title = '{$details['nomRecette']}';
            } </script>";

            //Ending Output buffering and assigning the code to $content variable
            $content = ob_get_clean();
            require_once "index.php";

            break;

        case "ajoutRecette":
            ob_start();
            echo "<div class='container-fluid'>
                    <div class='col align-self-center'>
                        <form action='traitement.php?action=add' method='POST' autocomplete='off' enctype='multipart/form-data'
                        class='mb-3 mx-auto'>
                            <p>
                                <label class='form-label'>
                                    Nom de la recette :
                                    <input type='text' name='nomRecette' class='form-control'>
                                </label>
                            </p>
                            <p>
                                <label class='form-label'>
                                    Temps de préparation :
                                    <input type='number' step='any' name='tempsPreparation' class='form-control' min='1'>
                                </label>
                            </p>
                            <p>
                                <label class='form-label'>
                                    Instructions :
                                    <textarea name='instructions' class='form-control' rows='3'></textarea>
                                </label>
                            </p>
                            <p>
                                <label class='form-label'>
                                    Catégorie :
                                    <select name='id_categorie' value='1' class='form-control'>
                                        ";
            foreach ($categories as $categorie) {
                echo "<option value='{$categorie['id_categorie']}'>{$categorie['nomCategorie']}</option>";
            }
            echo "
                                    </select>
                                </label>
                            </p>
                            <p>
                                <label class='form-label'>
                                    Lien de l'image :
                                    <input type='text' name='image' class='form-control'>
                                </label>
                            </p>
                            <p>
                                <fieldset>
                                    <legend>Ingrédients :</legend>";
            foreach (getIngredients($ingredients) as $ingredient) {
                echo "<div class='form-check checkbox-lg'>
                        <label class='form-check-label'>
                        {$ingredient['nomIngredient']}
                        <input type='checkbox' id='{$ingredient['id_ingredient']}' name='id_ingredient' class='form-control form-check-input'/>
                        </label>

                        <label class='form-label' for='{$ingredient['id_ingredient']}'>
                        Quantité :
                        <input type='number' step='any' name='quantite' class='form-control' min='0'>
                        </label>
                    </div>";
            }
            echo "
                                </fieldset>
                            </p>
                            <p>
                                <input class='btn btn-warning' type='submit' name='submit' value='Ajouter la recette'>
                            </p>
                        </form>";

            $content = ob_get_clean();
            require_once "index.php";

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


                $form = $formStatement->execute();
                $checkboxStatement->execute();





                if ($form) {
                    $_SESSION['message'] = "<p class='alert alert-success'>Recette envoyée</p>";
                    header('Location:traitement.php?listerRecettes');
                    exit(0);
                } else {
                    $_SESSION['message'] = "<p class='alert alert-success'>Recette non envoyée</p>";
                    header('Location:traitement.php?listerRecettes');
                    exit(0);

                }
            }


            break;
    }
} else {
    ob_start();
    foreach (getRecettes($recettes) as $recette) {
        echo "<article class='m-2'>
            <a class='mt-5 link-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover'
                href='traitement.php?action=infosRecettes&id={$recette['id_recette']}'>
                {$recette['nomRecette']}
            </a>
            |
            <a class='link-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover' href=''>
                {$recette['nomCategorie']}
            </a>
        </article>";
    }
    echo "<a class='btn btn-warning' href='traitement.php?action=ajoutRecette'>Ajouter une recette</a>

        <script>
        if (document.title != 'Recettes') {
            document.title = 'Recettes';
        } </script>";

    $content = ob_get_clean();
    require_once "index.php";
}
