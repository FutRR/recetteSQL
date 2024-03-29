<?php
ob_start();

foreach (getRecettes($recettes) as $recette) { ?>
    <article class='m-2'>
        <a class='mt-5 link-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover'
            href='traitement.php?action=infosRecettes&id=<?= $recette['id_recette'] ?>'>
            <?= $recette['nomRecette'] ?>
        </a>
        |
        <a class='link-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover' href=''>
            <?= $recette['nomCategorie'] ?>
        </a>
        |
        <a class='btn btn-danger btn-sm' href='traitement.php?action=delete&id=<?= $recette['id_recette'] ?>'>Supprimer</a>

    </article>
    <?php
}
?>
<a class='btn btn-warning' href='traitement.php?action=ajoutRecette'>Ajouter une recette</a>

<a class='btn btn-warning' href='traitement.php?action=ajoutIngredient'>Ajouter un ingrédient</a>


<script>
    if (document.title != 'Recettes') {
        document.title = 'Recettes';
    }
</script>

<?php
$content = ob_get_clean();
require_once "index.php";