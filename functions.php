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