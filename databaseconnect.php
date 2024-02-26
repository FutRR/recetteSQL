<?php
try {
    // On se connecte à MySQL
    $mysqlClient = new PDO(
        'mysql:host=localhost;dbname=recette_maxime;charset=utf8',
        'root',
        '',
    );
    $mysqlClient->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    // En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur : ' . $e->getMessage());
}
