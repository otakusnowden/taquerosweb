<?php
require_once __DIR__ . '/includes/config.php';
$active    = 'ubicaciones';
$pageTitle = 'Ubicaciones y Contacto · ' . SITE_NAME;
$pageDesc  = 'Encuentra Hot Wings: dirección, teléfono, WhatsApp, correo, horarios y mapa de ubicación.';

include __DIR__ . '/includes/header.php';
?>

<section class="section section-dark">
    <div class="container">
        <div style="text-align:center; margin-bottom:36px;" class="reveal">
            <span class="eyebrow">Visítanos</span>
            <h1 class="section-title">Ubicaciones y <span class="hl">contacto</span></h1>
            <p class="lead" style="margin:0 auto;">Te esperamos con las mejores alitas. Aquí están todos nuestros datos de contacto.</p>
        </div>

        <div class="contact-grid">
            <div class="contact-list reveal">
                <div class="contact-item">
                    <span class="ic">
                        <svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1112 6.5a2.5 2.5 0 010 5z"/></svg>
                    </span>
                    <div><h4>Dirección</h4><p><?= ADDRESS ?></p></div>
                </div>
                <div class="contact-item">
                    <span class="ic">
                        <svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M6.62 10.79a15.5 15.5 0 006.59 6.59l2.2-2.2a1 1 0 011.02-.24 11.4 11.4 0 003.57.57 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1 11.4 11.4 0 00.57 3.57 1 1 0 01-.24 1.02l-2.21 2.2z"/></svg>
                    </span>
                    <div><h4>Teléfono</h4><a href="tel:<?= PHONE_TEL ?>"><?= PHONE_DISPLAY ?></a></div>
                </div>
                <div class="contact-item">
                    <span class="ic">
                        <svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.82 11.82 0 018.413 3.488 11.82 11.82 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-.607z"/></svg>
                    </span>
                    <div><h4>WhatsApp</h4><a href="<?= whatsapp_link('Hola, quiero más información de Hot Wings.') ?>" target="_blank" rel="noopener"><?= PHONE_DISPLAY ?></a></div>
                </div>
                <div class="contact-item">
                    <span class="ic">
                        <svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4l-8 5-8-5V6l8 5 8-5z"/></svg>
                    </span>
                    <div><h4>Correo</h4><a href="mailto:<?= EMAIL ?>"><?= EMAIL ?></a></div>
                </div>
                <div class="contact-item">
                    <span class="ic">
                        <svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 10.59l3.3 3.3-1.42 1.42L11 13V6h2z"/></svg>
                    </span>
                    <div><h4>Horario</h4><p><?= SCHEDULE ?></p></div>
                </div>
                <div class="contact-item">
                    <span class="ic">
                        <svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M12 2a10 10 0 100 20 10 10 0 000-20zm6.6 6h-2.9a15.7 15.7 0 00-1.3-3.4A8 8 0 0118.6 8zM12 4c.8 1.2 1.4 2.5 1.8 4h-3.6c.4-1.5 1-2.8 1.8-4zM4.3 14a8 8 0 010-4h3.3a17.6 17.6 0 000 4zm.7 2h2.9c.3 1.2.7 2.4 1.3 3.4A8 8 0 015 16zm2.9-8H5a8 8 0 014.2-3.4A15.7 15.7 0 007.9 8zM12 20c-.8-1.2-1.4-2.5-1.8-4h3.6c-.4 1.5-1 2.8-1.8 4zm2.2-6H9.8a15.3 15.3 0 010-4h4.4a15.3 15.3 0 010 4zm.8 5.4c.6-1 1-2.2 1.3-3.4H19a8 8 0 01-4 3.4zm1.6-5.4a17.6 17.6 0 000-4h3.3a8 8 0 010 4z"/></svg>
                    </span>
                    <div><h4>Redes sociales</h4>
                        <p style="display:flex; gap:10px; margin-top:6px;">
                            <a href="<?= FACEBOOK_URL ?>" target="_blank" rel="noopener">Facebook</a> ·
                            <a href="<?= INSTAGRAM_URL ?>" target="_blank" rel="noopener">Instagram</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="map-frame reveal">
                <iframe src="<?= MAPS_EMBED ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        title="Mapa de ubicación Hot Wings" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</section>
<?= section_divider() ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
