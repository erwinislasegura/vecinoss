<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($title) ?> | VecinoSS</title>
    <meta name="description" content="Noticias locales, actualidad y comunidad de la Provincia de San Antonio.">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body>
<?php require dirname(__DIR__) . '/partials/header.php'; ?>
<main><?= $content ?></main>
<?php require dirname(__DIR__) . '/partials/footer.php'; ?>
<script src="<?= asset('js/app.js') ?>" defer></script>
</body></html>

