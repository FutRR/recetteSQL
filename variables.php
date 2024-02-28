<?php

$recetteStatement = $mysqlClient->prepare('SELECT * 
                                            FROM recette
                                            INNER JOIN categorie ON recette.id_categorie = categorie.id_categorie
                                            ORDER BY recette.id_recette');
$recetteStatement->execute();
$recettes = $recetteStatement->fetchAll();


$ingredientStatement = $mysqlClient->prepare('SELECT *
                                            FROM ingredient');
$ingredientStatement->execute();
$ingredients = $ingredientStatement->fetchAll();


$categorieStatement = $mysqlClient->prepare('SELECT * 
                                            FROM categorie 
                                            ');
$categorieStatement->execute();
$categories = $categorieStatement->fetchAll();