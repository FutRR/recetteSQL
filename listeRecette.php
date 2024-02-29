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
    </article>
    <?php
}
?>
<a class='btn btn-warning' href='traitement.php?action=ajoutRecette'>Ajouter une recette</a>

<script>
    if (document.title != 'Recettes') {
        document.title = 'Recettes';
    } 
</script>

<?php
$content = ob_get_clean();
require_once "index.php";