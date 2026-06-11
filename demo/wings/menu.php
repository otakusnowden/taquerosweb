<?php
require_once __DIR__ . '/includes/config.php';
$active    = 'menu';
$pageTitle = 'Menú · ' . SITE_NAME;
$pageDesc  = 'Conoce el menú completo de Hot Wings: wings, boneless, ribs, burgers, snacks, bebidas y más.';

$menuImgs = gallery_images(DIR_MENU, URL_MENU);
$menuSrc  = $menuImgs[0] ?? 'assets/img/rebel_spirit.png';

include __DIR__ . '/includes/header.php';
?>

<section class="section section-dark">
    <div class="container menu-wrap">
        <span class="eyebrow reveal">Carta</span>
        <h1 class="section-title reveal">Nuestro <span class="hl">Menú</span></h1>
        <p class="lead reveal" style="margin:0 auto;">Todo lo que puedas comer. Toca la imagen para ampliarla.</p>

        <figure class="menu-figure" data-zoom data-full="<?= $menuSrc ?>">
            <img src="<?= $menuSrc ?>" alt="Menú completo de Hot Wings" loading="lazy">
        </figure>
        <p class="menu-hint reveal">📲 En móvil, toca el menú para verlo en pantalla completa.</p>
    </div>
</section>


<section class="section cta">
    <div class="container reveal">
        <h2 class="section-title">¿Listo para <span class="hl">ordenar</span>?</h2>
        <p class="lead" style="margin:0 auto 26px;">Reserva tu mesa y disfruta las mejores alitas de la ciudad.</p>
        <a href="reservacion" class="btn btn-primary">Reservar mesa</a>
    </div>
</section>
<?= section_divider() ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
