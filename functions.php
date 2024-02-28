<?php

function getRecettes(array $recettes): array
{
    $results = [];

    foreach ($recettes as $recette) {
        $results[] = $recette;
    }

    return $results;
}

function getIngredients(array $ingredients): array
{
    $results = [];

    foreach ($ingredients as $ingredient) {
        $results[] = $ingredient;

    }

    return $results;
}

function getCategories(array $categories): array
{
    $results = [];

    foreach ($categories as $categorie) {
        $results[] = $categorie;

    }

    return $results;
}