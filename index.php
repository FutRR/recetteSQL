<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <?php require_once(__DIR__ . "/navbar.php"); ?>

    <div class="container">

        <h1 class="mt-5 pt-5">Maxithon</h1>

        <?php require_once(__DIR__ . "/traitement.php"); ?>
        <?= $content ?>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>