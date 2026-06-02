<?php
require_once __DIR__ . '/auth.php';
require_login();

$flash = ['ok' => null, 'msg' => '', 'section' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = $_POST['section'] ?? '';
    switch ($section) {
        case 'carrusel1':
            $r = handle_upload('imgs', DIR_CARRUSEL1, 6, true);
            break;
        case 'carrusel2':
            $r = handle_upload('imgs', DIR_CARRUSEL2, 2, true);
            break;
        case 'menu':
            $r = handle_upload('imgs', DIR_MENU, 1, true, 'imagen_menu');
            break;
        default:
            $r = ['ok' => false, 'msg' => 'Sección no válida.'];
    }
    $flash = ['ok' => $r['ok'], 'msg' => $r['msg'], 'section' => $section];
}

$carrusel1 = gallery_images(DIR_CARRUSEL1, '../' . URL_CARRUSEL1);
$carrusel2 = gallery_images(DIR_CARRUSEL2, '../' . URL_CARRUSEL2);
$menuImgs  = gallery_images(DIR_MENU, '../' . URL_MENU);

function flash_for(array $flash, string $section): string
{
    if ($flash['section'] !== $section || $flash['ok'] === null) {
        return '';
    }
    $cls = $flash['ok'] ? 'alert-success' : 'alert-error';
    return '<div class="alert ' . $cls . '">' . htmlspecialchars($flash['msg']) . '</div>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Panel · Hot Wings</title>
    <link rel="icon" type="image/png" href="../assets/img/wings_logo.png">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin">
<header class="admin-bar">
    <div class="admin-bar-inner">
        <div class="admin-brand">
            <img src="../assets/img/wings_logo.png" alt="Hot Wings">
            <span>Panel administrativo</span>
        </div>
        <div class="admin-actions">
            <a href="../index.php" target="_blank" class="btn btn-sm btn-primary">Ver sitio</a>
            <a href="logout.php" class="btn btn-sm btn-red">Salir</a>
        </div>
    </div>
</header>

<main class="admin-main container">
    <h1 class="admin-title">Administrar contenido</h1>
    <p class="admin-intro">Sube y reemplaza las imágenes del sitio. Formatos permitidos: <strong>JPG, PNG, WEBP</strong> (máx. 6 MB c/u).</p>

    <!-- Carrusel principal -->
    <section class="admin-card">
        <div class="admin-card-head">
            <h2>Carrusel principal</h2>
            <span class="badge">Hasta 6 imágenes</span>
        </div>
        <?= flash_for($flash, 'carrusel1') ?>
        <div class="thumbs">
            <?php if ($carrusel1): foreach ($carrusel1 as $src): ?>
                <div class="thumb"><img src="<?= $src ?>" alt="" loading="lazy"></div>
            <?php endforeach; else: ?>
                <p class="empty">Aún no hay imágenes cargadas.</p>
            <?php endif; ?>
        </div>
        <form method="post" enctype="multipart/form-data" class="upload-form" data-max="6">
            <input type="hidden" name="section" value="carrusel1">
            <label class="dropzone">
                <input type="file" name="imgs[]" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple required>
                <span class="dz-text">Arrastra o selecciona hasta 6 imágenes</span>
            </label>
            <div class="previews"></div>
            <button type="submit" class="btn btn-primary">Guardar carrusel principal</button>
            <p class="warn">⚠️ Al guardar se reemplazan todas las imágenes actuales de esta sección.</p>
        </form>
    </section>

    <!-- Carrusel secundario -->
    <section class="admin-card">
        <div class="admin-card-head">
            <h2>Carrusel secundario</h2>
            <span class="badge">Hasta 2 imágenes</span>
        </div>
        <?= flash_for($flash, 'carrusel2') ?>
        <div class="thumbs">
            <?php if ($carrusel2): foreach ($carrusel2 as $src): ?>
                <div class="thumb"><img src="<?= $src ?>" alt="" loading="lazy"></div>
            <?php endforeach; else: ?>
                <p class="empty">Aún no hay imágenes cargadas.</p>
            <?php endif; ?>
        </div>
        <form method="post" enctype="multipart/form-data" class="upload-form" data-max="2">
            <input type="hidden" name="section" value="carrusel2">
            <label class="dropzone">
                <input type="file" name="imgs[]" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple required>
                <span class="dz-text">Arrastra o selecciona hasta 2 imágenes</span>
            </label>
            <div class="previews"></div>
            <button type="submit" class="btn btn-primary">Guardar carrusel secundario</button>
            <p class="warn">⚠️ Al guardar se reemplazan las imágenes actuales de esta sección.</p>
        </form>
    </section>

    <!-- Menú -->
    <section class="admin-card">
        <div class="admin-card-head">
            <h2>Imagen del menú</h2>
            <span class="badge">1 imagen</span>
        </div>
        <?= flash_for($flash, 'menu') ?>
        <div class="thumbs">
            <?php if ($menuImgs): ?>
                <div class="thumb thumb-tall"><img src="<?= $menuImgs[0] ?>" alt="" loading="lazy"></div>
            <?php else: ?>
                <p class="empty">Aún no hay imagen de menú.</p>
            <?php endif; ?>
        </div>
        <form method="post" enctype="multipart/form-data" class="upload-form" data-max="1">
            <input type="hidden" name="section" value="menu">
            <label class="dropzone">
                <input type="file" name="imgs[]" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required>
                <span class="dz-text">Selecciona la nueva imagen del menú</span>
            </label>
            <div class="previews"></div>
            <button type="submit" class="btn btn-primary">Reemplazar menú</button>
        </form>
    </section>
</main>

<script src="admin.js" defer></script>
</body>
</html>
