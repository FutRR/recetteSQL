<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');
ob_start();
?>



<?php foreach (getRecettes($recettes) as $recette): ?>
    <article>
        <a class="mt-5 link-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover"
            href="details.php?action=infosRecette&id=<?= $recette['id_recette'] ?>">
            <?= $recette['nomRecette']; ?>
        </a>
        |
        <a class="link-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover" href="">
            <?= $recette['nomCategorie'] ?>
        </a>
        <p class="">
            <?= $recette['instructions']; ?>
        </p>
    </article>
<?php endforeach ?>

<script>
    if (document.title != "Recettes") {
        document.title = "Recettes";
    }
</script>

<?php
$content = ob_get_clean();

require_once "template.php";
?>