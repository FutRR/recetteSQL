-- 1- Afficher toutes les recettes disponibles (nom de la recette, catégorie et temps de préparation) triée de façon décroissante sur la durée de réalisation.

    SELECT nomRecette, tempsPreparation, instructions, categorie.nomCategorie 
	FROM recette
	INNER JOIN categorie ON recette.id_categorie = categorie.id_categorie
    ORDER BY tempsPreparation DESC;

-- 2- En modifiant la requête précédente, faites apparaître le nombre d’ingrédients nécessaire par recette.
    SELECT nomRecette, tempsPreparation, instructions, COUNT(contenir.id_recette) AS nbIngredient
    FROM recette
    INNER JOIN contenir ON recette.id_recette = contenir.id_recette
    GROUP BY recette.id_recette
    ORDER BY tempsPreparation DESC;


-- 3- Afficher les recettes qui nécessitent au moins 30 min de préparation.
    SELECT * FROM recette
    WHERE tempsPreparation > 30;

-- 4- Afficher les recettes dont le nom contient le mot « Salade » (peu importe où est situé le mot en question).
    SELECT * FROM recette
    WHERE nomRecette LIKE '%Salade%';

-- 5- Insérer une nouvelle recette : « Pâtes à la carbonara » dont la durée de réalisation est de 20 min avec les instructions de votre choix. Pensez à alimenter votre base de données en conséquence afin de pouvoir lister les détails de cette recettes (ingrédients).
    INSERT INTO recette (nomRecette, tempsPreparation, instructions, id_categorie)
    VALUES ('Pâtes à la carbonara', '20', 'lorem450', '2');

-- 6- Modifier le nom de la recette ayant comme identifiant id_recette = 3 (nom de la recette à votre convenance).
    UPDATE recette
    SET nomRecette = "Pâtes vodka"
    WHERE id_recette = 3;

-- 7- Supprimer la recette n°2 de la base de données.
    DELETE FROM recette WHERE id_recette = 2

-- 8- Afficher le prix total de la recette n°5.
    SELECT recette.nomRecette, ROUND(SUM(ingredient.prixIngredient*contenir.quantite), 2) AS prixTotal
    FROM contenir
    INNER JOIN ingredient ON contenir.id_ingredient = ingredient.id_ingredient
    INNER JOIN recette ON contenir.id_recette = recette.id_recette
    WHERE recette.id_recette = 
    GROUP BY recette.id_recette;    

-- 9- Afficher le détail de la recette n°5 (liste des ingrédients, quantités et prix)
    SELECT nomIngredient, contenir.quantite, prixIngredient
    FROM ingredient
    INNER JOIN contenir ON ingredient.id_ingredient = contenir.id_ingredient

-- 10- Ajouter un ingrédient en base de données : Poivre, unité : cuillère à café, prix : 2.5€
    INSERT INTO ingredient (nomIngredient, prixIngredient, uniteMesure)
    VALUES ('Poivre', '15','kg');

-- 11- Modifier le prix de l’ingrédient n°12 (prix à votre convenance)
    UPDATE ingredient SET prixIngredient = 2
    WHERE id_ingredient = 2