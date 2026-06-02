<?php
require_once __DIR__ . '/config.php';
$active   = $active   ?? '';
$pageTitle = $pageTitle ?? SITE_NAME . ' · ' . SITE_TAGLINE;
$pageDesc  = $pageDesc  ?? 'Las mejores alitas de la ciudad. Wings, boneless, ribs, burgers y más.';
$nav = [
    'index.php'       => 'Home',
    'menu.php'        => 'Menú',
    'ubicaciones.php' => 'Ubicaciones',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>">
    <meta name="theme-color" content="#0d0d0d">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDesc) ?>">
    <meta property="og:type" content="website">
    <link rel="icon" type="image/png" href="assets/img/wings_logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<a class="skip-link" href="#main">Saltar al contenido</a>
<header class="site-header" id="siteHeader">
    <div class="container header-inner">
        <a class="brand" href="index.php" aria-label="<?= SITE_NAME ?> inicio">
            <img src="assets/img/wings_logo.png" alt="<?= SITE_NAME ?> <?= SITE_TAGLINE ?>" width="170" height="84">
        </a>

        <button class="nav-toggle" id="navToggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="primaryNav">
            <span></span><span></span><span></span>
        </button>

        <nav class="primary-nav" id="primaryNav" aria-label="Navegación principal">
            <ul>
                <?php foreach ($nav as $href => $label): ?>
                    <li>
                        <a href="<?= $href ?>"<?= $active === $href ? ' class="is-active" aria-current="page"' : '' ?>>
                            <?= $label ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li class="nav-cta">
                    <a href="reservacion.php" class="btn btn-primary btn-sm"<?= $active === 'reservacion.php' ? ' aria-current="page"' : '' ?>>Reservar</a>
                </li>
            </ul>
        </nav>
    </div>
</header>
<main id="main">
