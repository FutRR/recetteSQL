<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');
ob_start();
?>



<script>
    if (document.title != "Details") {
        document.title = "Details";
    }
</script>

<?php
$content = ob_get_clean();

require_once "template.php";
?>