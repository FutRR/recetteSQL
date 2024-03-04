<?php
ob_start();
?>

<h2 class='alert alert-danger'>Mauvais URL</h2>
<a class='btn btn-warning' href='traitement.php?action=listerRecettes'>Retour</a>

<script>
    if (document.title != 'Erreur') {
        document.title = 'Erreur';
    }
</script>

<?php
$content = ob_get_clean();
require_once "index.php";