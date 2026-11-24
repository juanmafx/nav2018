<?php

declare(strict_types=1);

use Nav2018\View\Html;

?>
<nav aria-label="Primary">
    <ul>
<?php foreach ($links as $link): ?>
        <li<?= $link->isActive ? ' class="active"' : '' ?>><a class="nav-link" href="<?= Html::escape($link->path) ?>"<?= $link->isActive ? ' aria-current="page"' : '' ?>><?= Html::escape($link->label) ?></a></li>
<?php endforeach; ?>
    </ul>
</nav>
