<?php

declare(strict_types=1);

use Nav2018\View\Html;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::escape($title) ?> | nav2018</title>
    <link rel="stylesheet" href="/assets/site.css">
</head>
<body>
<?= $navigation ?>
<main class="container">
    <h1><?= Html::escape($heading) ?></h1>
</main>
</body>
</html>
