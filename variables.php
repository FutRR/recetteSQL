<?php

$recetteStatement = $mysqlClient->prepare('SELECT * FROM recette');
$recetteStatement->execute();
$recettes = $recetteStatement->fetchAll();


$ingredientStatement = $mysqlClient->prepare('SELECT * FROM ingredient');
$ingredientStatement->execute();
$ingredients = $ingredientStatement->fetchAll();