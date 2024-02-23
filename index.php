<?php
try {
    // On se connecte à MySQL
    $mysqlClient = new PDO('mysql:host=localhost;dbname=recette_maxime;charset=utf8', 'root', '');
} catch (Exception $e) {
    // En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur : ' . $e->getMessage());
}
// Si tout va bien, on peut continuer

// On récupère tout le contenu de la table recipes
$sqlQuery = 'SELECT * FROM recette WHERE id_categorie = 2';
$recetteStatement = $mysqlClient->prepare($sqlQuery);
$recetteStatement->execute();
$recettes = $recetteStatement->fetchAll();

// On affiche chaque recette une à une
foreach ($recettes as $recette) {
    ?>
    <p>
        <?php echo $recette['nomRecette']; ?>
    </p>
    <?php
}
?>