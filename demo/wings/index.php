<?php
require_once __DIR__ . '/includes/config.php';
$active    = './';
$pageTitle = SITE_NAME . ' · ' . SITE_TAGLINE . ' — Las mejores alitas de la ciudad';
$pageDesc  = 'Hot Wings: wings, boneless, ribs y burgers con el mejor sabor. Promociones, ubicaciones y reservaciones en línea.';

$carrusel1 = gallery_images(DIR_CARRUSEL1, URL_CARRUSEL1);
$carrusel2 = gallery_images(DIR_CARRUSEL2, URL_CARRUSEL2);

include __DIR__ . '/includes/header.php';
?>

<!-- Sección 1: Carrusel principal -->
<section class="hero">
    <div class="carousel" data-carousel data-interval="5000" aria-roledescription="carrusel" aria-label="Promociones destacadas">
        <div class="carousel-track" data-track>
            <?php if ($carrusel1): foreach ($carrusel1 as $i => $src): ?>
                <div class="carousel-slide" data-slide>
                    <img src="<?= $src ?>" alt="Promoción Hot Wings <?= $i + 1 ?>"
                         <?= $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>
                         width="1280" height="560">
                </div>
            <?php endforeach; else: ?>
                <div class="carousel-slide" data-slide><img src="assets/img/rebel_spirit.png" alt="Hot Wings"></div>
            <?php endif; ?>
        </div>
        <?php if (count($carrusel1) > 1): ?>
            <button class="carousel-btn prev" data-prev aria-label="Anterior">&#10094;</button>
            <button class="carousel-btn next" data-next aria-label="Siguiente">&#10095;</button>
            <div class="carousel-dots" data-dots role="tablist" aria-label="Indicadores"></div>
        <?php endif; ?>
    </div>
</section>
<!--
<?= section_divider() ?>
-->

<!-- Sección 2: Call To Action -->
<section class="section cta">
    <div class="container reveal">
        <img class="cta-spirit" src="assets/img/rebel_spirit.png" alt="" aria-hidden="true" loading="lazy">
        <span class="eyebrow">#STAY REBEL</span>
        <h2 class="section-title">Las mejores <span class="hl">alitas</span> de la ciudad</h2>
        <p class="lead" style="margin:0 auto 28px;">Descubre nuestro menú completo y encuentra tu sabor favorito.</p>
        <a href="menu" class="btn btn-red">Ver Menú</a>
    </div>
</section>
<?= section_divider() ?>

<!-- Sección 3: Carrusel secundario -->
<section class="section section-darker">
    <div class="container">
        <div style="text-align:center; margin-bottom:30px;" class="reveal">
            <span class="eyebrow">Promos</span>
            <h2 class="section-title">Promociones <span class="hl">destacadas</span></h2>
        </div>
        <div class="carousel reveal" data-carousel data-interval="6000" aria-label="Promociones destacadas secundarias">
            <div class="promos-track carousel-track" data-track>
                <?php if ($carrusel2): foreach ($carrusel2 as $i => $src): ?>
                    <div class="promo-card" data-slide>
                        <div class="frame"><img src="<?= $src ?>" alt="Promoción destacada <?= $i + 1 ?>" loading="lazy" width="1100" height="420"></div>
                    </div>
                <?php endforeach; else: ?>
                    <div class="promo-card" data-slide><div class="frame"><img src="assets/img/rebel_spirit.png" alt="Hot Wings"></div></div>
                <?php endif; ?>
            </div>
            <?php if (count($carrusel2) > 1): ?>
                <button class="carousel-btn prev" data-prev aria-label="Anterior">&#10094;</button>
                <button class="carousel-btn next" data-next aria-label="Siguiente">&#10095;</button>
                <div class="carousel-dots" data-dots aria-label="Indicadores"></div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?= section_divider() ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
