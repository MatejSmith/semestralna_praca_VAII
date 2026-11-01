<?php

/**
 * Spoločné rozloženie aplikácie SportRent (hlavička, menu, pätička).
 * Obsah konkrétneho pohľadu sa vkladá cez $contentHTML.
 *
 * Pozn.: súbor bol vygenerovaný pomocou AI (Claude) a upravený podľa zadania.
 */

/** @var string $contentHTML */
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */

// Rozloženie nemá prístup k dátam z kontroléra, preto aktívnu položku menu určíme z parametra ?c=
$currentController = $_GET['c'] ?? 'home';
$currentController = is_string($currentController) ? strtolower($currentController) : 'home';
$isCatalogActive = in_array($currentController, ['home', 'polozka'], true);
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= App\Configuration::APP_NAME ?> – požičovňa športového vybavenia</title>
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $link->asset('favicons/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $link->asset('favicons/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $link->asset('favicons/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= $link->asset('favicons/site.webmanifest') ?>">
    <link rel="shortcut icon" href="<?= $link->asset('favicons/favicon.ico') ?>">
    <link rel="stylesheet" href="<?= $link->asset('css/style.css') ?>">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="<?= $link->url('home.index') ?>" aria-label="<?= App\Configuration::APP_NAME ?> – domov">
            <span class="logo">LOGO</span>
            <span class="brand-name"><?= App\Configuration::APP_NAME ?></span>
        </a>

        <!-- Menu na mobile sa otvára bez JavaScriptu (skrytý checkbox + label) -->
        <input type="checkbox" id="menu-toggle" class="menu-toggle" aria-label="Zobraziť menu">
        <label for="menu-toggle" class="menu-button" aria-hidden="true">☰</label>

        <div class="menu-panel">
            <nav class="main-nav" aria-label="Hlavné menu">
                <ul>
                    <li><a href="<?= $link->url('home.index') ?>"<?= $isCatalogActive ? ' class="active"' : '' ?>>Katalóg</a></li>
                    <li><a href="#">Moje rezervácie</a></li>
                    <li><a href="#">Profil</a></li>
                    <li><a href="#">Admin</a></li>
                </ul>
            </nav>

            <div class="auth">
                <?php if ($user->isLoggedIn()) { ?>
                    <span class="user-name"><?= e($user->getName()) ?></span>
                    <a class="btn" href="<?= $link->url('auth.logout') ?>">Odhlásiť</a>
                <?php } else { ?>
                    <a class="btn" href="<?= App\Configuration::LOGIN_URL ?>">Prihlásiť sa</a>
                    <a class="btn btn-primary" href="#">Registrovať</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

<main>
    <?= $contentHTML ?>
</main>

<footer class="site-footer">
    <div class="footer-inner">
        <p>© <?= date('Y') ?> <?= App\Configuration::APP_NAME ?> – požičovňa športového vybavenia</p>
        <ul>
            <li><a href="#">Kontakt</a></li>
            <li><a href="#">Podmienky požičania</a></li>
            <li><a href="#">Otváracie hodiny</a></li>
        </ul>
    </div>
</footer>

</body>
</html>
