<?php
/**
 * Hot Wings — Configuración global del sitio
 * Demo lista para hosting PHP tradicional (PHP 8+).
 */

// ----- Datos del negocio (editar según el restaurante) -----
const SITE_NAME      = 'Super Wings';
const SITE_TAGLINE   = 'Wings & Ribs';
const WHATSAPP_PHONE = '5215555555555';                  // wa.me — incluye código de país, sin signos
const PHONE_DISPLAY  = '+52 55 5555 5555';
const PHONE_TEL      = '+525555555555';
const EMAIL          = 'contacto@superwings.mx';
const ADDRESS        = 'Av. Principal 123, Col. Centro, Ciudad de México';
const SCHEDULE       = 'Lun a Dom · 12:00 — 23:00 hrs';
const FACEBOOK_URL   = 'https://facebook.com/superwings';
const INSTAGRAM_URL  = 'https://instagram.com/superwings';
// Mapa embebido (Google Maps) — reemplazar src por la ubicación real del restaurante.
const MAPS_EMBED     = 'https://www.google.com/maps?q=Zocalo%20Ciudad%20de%20Mexico&output=embed';

// ----- Credenciales del panel administrativo -----
const ADMIN_USER = 'admin';
const ADMIN_PASS = 'SuperWings2026';

// ----- Rutas de uploads (relativas a la raíz del sitio) -----
const DIR_CARRUSEL1 = __DIR__ . '/../uploads/carrusel1';
const DIR_CARRUSEL2 = __DIR__ . '/../uploads/carrusel2';
const DIR_MENU      = __DIR__ . '/../uploads/menu';

const URL_CARRUSEL1 = 'uploads/carrusel1';
const URL_CARRUSEL2 = 'uploads/carrusel2';
const URL_MENU      = 'uploads/menu';

const ALLOWED_EXT  = ['jpg', 'jpeg', 'png', 'webp'];
const MAX_UPLOAD   = 6 * 1024 * 1024; // 6 MB por imagen

/**
 * Devuelve las imágenes (ordenadas) de un directorio de uploads.
 * @return array<string> rutas web relativas listas para usar en <img src>
 */
function gallery_images(string $dir, string $urlBase): array
{
    if (!is_dir($dir)) {
        return [];
    }
    $files = glob($dir . '/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE) ?: [];
    natcasesort($files);
    return array_map(
        static fn(string $f) => $urlBase . '/' . rawurlencode(basename($f)),
        array_values($files)
    );
}

/** Construye un enlace wa.me con texto opcional ya codificado. */
function whatsapp_link(string $text = ''): string
{
    $base = 'https://wa.me/' . WHATSAPP_PHONE;
    return $text === '' ? $base : $base . '?text=' . rawurlencode($text);
}

/**
 * Separador de imagen (borde rasgado) que alterna automáticamente entre
 * border-01.svg y border-02.svg en cada llamada dentro de la misma página.
 * Patrón: 1ª → border-01, 2ª → border-02, 3ª → border-01, …
 */
function section_divider(string $base = ''): string
{
    static $i = 0;
    $isFirst = ($i % 2 === 0);
    $img     = $isFirst ? 'border-02.svg' : 'border-01.svg';
    $mod     = $isFirst ? 'div-02' : 'div-01';
    $i++;
    return '<div class="section-divider ' . $mod . '" aria-hidden="true">'
         . '<img src="' . $base . 'assets/img/' . $img . '" alt="" loading="lazy" decoding="async">'
         . '</div>';
}
