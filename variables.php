<?php

$recetteStatement = $mysqlClient->prepare('SELECT * 
                                            FROM recette
                                            INNER JOIN categorie ON recette.id_categorie = categorie.id_categorie
                                            ORDER BY recette.id_recette');
$recetteStatement->execute();
$recettes = $recetteStatement->fetchAll();


$ingredientStatement = $mysqlClient->prepare('SELECT *
                                            FROM ingredient
                                            INNER JOIN contenir ON ingredient.id_ingredient = contenir.id_ingredient
                                            INNER JOIN recette ON contenir.id_recette = recette.id_recette');
$ingredientStatement->execute();
$ingredients = $ingredientStatement->fetchAll();


$categorieStatement = $mysqlClient->prepare('SELECT * 
                                            FROM categorie 
                                            INNER JOIN recette 
                                            ON categorie.id_categorie = recette.id_categorie
                                            GROUP BY recette.id_recette
                                            ');
$categorieStatement->execute();
$categories = $categorieStatement->fetchAll();