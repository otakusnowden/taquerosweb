<?php
$cfg      = require __DIR__ . '/config.php';
$paquetes = $cfg['paquetes'];
$siteUrl  = rtrim($cfg['site_url'], '/');
$siteName = $cfg['site_name'];

// Precio mínimo para AggregateOffer
$precioMin = min(array_column($paquetes, 'precio'));
$precioMax = max(array_column($paquetes, 'precio'));

// Helper: formatea precio MXN  → "2,199"
function fmt(int $n): string { return number_format($n, 0, '.', ','); }

// JSON-LD generado dinámicamente desde config
$jsonLd = [
    '@context' => 'https://schema.org/',
    '@graph'   => [
        // ── Organización ────────────────────────────────────────────────────
        [
            '@type'  => 'Organization',
            '@id'    => $siteUrl . '/#organization',
            'name'   => $siteName,
            'url'    => $siteUrl,
            'logo'   => $siteUrl . '/images/taqueros_web_logo1.jpg',
            'contactPoint' => [
                '@type'             => 'ContactPoint',
                'contactType'       => 'customer service',
                'availableLanguage' => 'Spanish',
                'url'               => 'https://wa.me/' . $cfg['whatsapp'],
            ],
            'sameAs' => [
                'https://www.facebook.com/taquerosweb',
                'https://www.instagram.com/taquerosweb',
            ],
        ],
        // ── WebSite ─────────────────────────────────────────────────────────
        [
            '@type'           => 'WebSite',
            '@id'             => $siteUrl . '/#website',
            'url'             => $siteUrl,
            'name'            => $siteName . ' — ' . $cfg['site_tagline'],
            'description'     => $cfg['site_desc'],
            'publisher'       => ['@id' => $siteUrl . '/#organization'],
            'inLanguage'      => 'es-MX',
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => $siteUrl . '/?s={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ],
        // ── Producto principal (AggregateOffer) ─────────────────────────────
        [
            '@type'       => 'Product',
            '@id'         => $siteUrl . '/#product-sitio-web',
            'name'        => 'Sitio Web Profesional para Negocios Mexicanos',
            'description' => 'Diseño y desarrollo de páginas web profesionales para taquerías y negocios en México. Responsive, SEO incluido, entrega en días.',
            'image'       => $siteUrl . '/' . $cfg['site_image'],
            'sku'         => 'TW-PACK-ALL',
            'brand'       => [
                '@type' => 'Brand',
                'name'  => $siteName,
            ],
            'manufacturer' => ['@id' => $siteUrl . '/#organization'],
            'category'     => 'Servicios de Diseño Web',
            'offers' => [
                '@type'         => 'AggregateOffer',
                'priceCurrency' => 'MXN',
                'lowPrice'      => (string) $precioMin,
                'highPrice'     => (string) $precioMax,
                'offerCount'    => count($paquetes),
                'availability'  => 'https://schema.org/InStock',
                'url'           => $siteUrl,
                'offers'        => array_map(fn($p) => [
                    '@type'           => 'Offer',
                    'name'            => $p['nombre'] . ' — ' . $p['subtitulo'],
                    'sku'             => $p['sku'],
                    'price'           => (string) $p['precio'],
                    'priceCurrency'   => 'MXN',
                    'availability'    => $p['disponible']
                                         ? 'https://schema.org/InStock'
                                         : 'https://schema.org/OutOfStock',
                    'url'             => $siteUrl . '/#paquetes',
                    'seller'          => ['@id' => $siteUrl . '/#organization'],
                    'priceValidUntil' => date('Y') + 1 . '-12-31',
                ], array_values($paquetes)),
            ],
            'aggregateRating' => [
                '@type'       => 'AggregateRating',
                'ratingValue' => '4.9',
                'reviewCount' => '320',
                'bestRating'  => '5',
            ],
        ],
    ],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- ===== SEO PRIMARIO ===== -->
<!--
<title>TaquerosWeb.com — Sitios Web Profesionales para Negocios Mexicanos</title>
-->
<meta name="description" content="<?= htmlspecialchars($cfg['site_desc']) ?>" />
<meta name="keywords" content="diseño web México, páginas web para taquerías, sitio web barato, web para negocios mexicanos, landing page, tienda online MXN" />
<meta name="author" content="<?= $siteName ?>" />
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
<link rel="canonical" href="<?= $siteUrl ?>/" />

<!-- ===== OPEN GRAPH ===== -->
<meta property="og:type"        content="website" />
<meta property="og:title"       content="TaquerosWeb.com — Sitios Web que Sí Venden" />
<meta property="og:description" content="<?= htmlspecialchars($cfg['site_desc']) ?>" />
<meta property="og:image"       content="<?= $siteUrl ?>/<?= $cfg['site_image'] ?>" />
<meta property="og:image:alt"   content="TaquerosWeb — Sitios web profesionales para negocios mexicanos" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:url"         content="<?= $siteUrl ?>/" />
<meta property="og:site_name"   content="<?= $siteName ?>" />
<meta property="og:locale"      content="es_MX" />

<!-- ===== TWITTER CARD ===== -->
<meta name="twitter:card"        content="summary_large_image" />
<meta name="twitter:title"       content="TaquerosWeb.com — Sitios Web que Sí Venden" />
<meta name="twitter:description" content="<?= htmlspecialchars($cfg['site_desc']) ?>" />
<meta name="twitter:image"       content="<?= $siteUrl ?>/<?= $cfg['site_image'] ?>" />

<!-- ===== DATOS ESTRUCTURADOS JSON-LD ===== -->
<script type="application/ld+json">
<?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<!-- ===== PRECONNECT / FONTS ===== -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

<style>
/* ===== CSS CUSTOM PROPERTIES ===== */
:root {
  --color-bg:        #0B0A08;
  --color-surface:   #141210;
  --color-card:      #1A1714;
  --color-border:    rgba(255,255,255,0.07);
  --color-orange:    #FF6B2B;
  --color-red:       #E8294C;
  --color-gold:      #FFB800;
  --color-lime:      #8BDA4F;
  --color-text:      #F5F0E8;
  --color-muted:     #8A8278;
  --grad-hero:       linear-gradient(135deg, #FF6B2B 0%, #E8294C 50%, #7B1FA2 100%);
  --grad-card:       linear-gradient(160deg, #1F1B16 0%, #141210 100%);
  --grad-gold:       linear-gradient(135deg, #FFB800, #FF6B2B);
  --font-display:    'Bebas Neue', sans-serif;
  --font-body:       'Manrope', sans-serif;
  --radius-card:     20px;
  --radius-btn:      12px;
  --shadow-card:     0 0 0 1px rgba(255,107,43,0.08), 0 24px 64px rgba(0,0,0,0.6);
  --shadow-glow:     0 0 40px rgba(255,107,43,0.25);
  --transition:      all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== RESET & BASE ===== */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; font-size: 16px; }
body {
  font-family: var(--font-body);
  background: var(--color-bg);
  color: var(--color-text);
  line-height: 1.6;
  overflow-x: hidden;
}
a { color: inherit; text-decoration: none; }
img { max-width: 100%; }
button { font-family: var(--font-body); cursor: pointer; border: none; }

/* ===== SCROLLBAR ===== */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: var(--color-bg); }
::-webkit-scrollbar-thumb { background: var(--color-orange); border-radius: 3px; }

/* ===== NOISE OVERLAY ===== */
body::before {
  content: '';
  position: fixed;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
  pointer-events: none;
  z-index: 0;
  opacity: 0.5;
}

/* ===== NAVBAR ===== */
.navbar {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 1000;
  padding: 1rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: rgba(11,10,8,0.85);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid var(--color-border);
  transition: var(--transition);
}
.navbar.scrolled { padding: 0.75rem 2rem; background: rgba(11,10,8,0.97); }
.navbar-logo {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-family: var(--font-display);
  font-size: 1.8rem;
  letter-spacing: 1px;
}
.logo-icon { font-size: 1.6rem; }
.logo-taqueros { color: var(--color-orange); }
.logo-web { color: var(--color-text); }
.logo-mx { color: var(--color-gold); font-size: 1.1rem; vertical-align: super; }
.nav-links { display: flex; gap: 2rem; list-style: none; }
.nav-links a { font-size: 0.875rem; font-weight: 500; color: var(--color-muted); transition: var(--transition); }
.nav-links a:hover { color: var(--color-text); }
.nav-cta { display: flex; gap: 0.75rem; align-items: center; }
.btn-nav {
  padding: 0.6rem 1.5rem;
  border-radius: var(--radius-btn);
  font-size: 0.875rem;
  font-weight: 700;
  background: var(--grad-hero);
  color: #fff;
  transition: var(--transition);
  position: relative;
  overflow: hidden;
}
.btn-nav::after { content: ''; position: absolute; inset: 0; background: rgba(255,255,255,0); transition: var(--transition); }
.btn-nav:hover::after { background: rgba(255,255,255,0.1); }
.btn-nav:hover { transform: translateY(-1px); box-shadow: var(--shadow-glow); }
.hamburger { display: none; flex-direction: column; gap: 5px; background: none; padding: 0.5rem; }
.hamburger span { display: block; width: 22px; height: 2px; background: var(--color-text); border-radius: 2px; transition: var(--transition); }

/* ===== MOBILE NAV ===== */
.mobile-menu {
  display: none;
  position: fixed;
  inset: 0;
  z-index: 999;
  background: rgba(11,10,8,0.98);
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 2rem;
}
.mobile-menu.open { display: flex; }
.mobile-menu a { font-size: 1.5rem; font-weight: 700; color: var(--color-text); transition: var(--transition); }
.mobile-menu a:hover { color: var(--color-orange); }
.mobile-close { position: absolute; top: 1.5rem; right: 2rem; font-size: 2rem; background: none; color: var(--color-text); }

/* ===== SECTION UTILITIES ===== */
.section { position: relative; z-index: 1; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
.section-tag {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: rgba(255,107,43,0.12);
  border: 1px solid rgba(255,107,43,0.25);
  border-radius: 100px;
  padding: 0.4rem 1rem;
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--color-orange);
  letter-spacing: 0.08em;
  text-transform: uppercase;
  margin-bottom: 1.25rem;
}
.section-title {
  font-family: var(--font-display);
  font-size: clamp(2.2rem, 5vw, 3.5rem);
  line-height: 1.05;
  letter-spacing: 1px;
  margin-bottom: 1rem;
}
.highlight { color: var(--color-orange); }
.highlight-gold { color: var(--color-gold); }
.section-sub { font-size: 1.05rem; color: var(--color-muted); max-width: 560px; line-height: 1.7; }

/* ===== HERO ===== */
.hero {
  min-height: 100vh;
  display: flex;
  align-items: center;
  padding: 8rem 0 5rem;
  position: relative;
  overflow: hidden;
}
.hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(255,107,43,0.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,107,43,0.04) 1px, transparent 1px);
  background-size: 60px 60px;
  mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);
}
.hero::after {
  content: '';
  position: absolute;
  top: -20%; left: 50%;
  transform: translateX(-50%);
  width: 800px; height: 800px;
  background: radial-gradient(circle, rgba(255,107,43,0.12) 0%, transparent 70%);
  pointer-events: none;
}
.hero-inner {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4rem;
  align-items: center;
}
.hero-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  background: rgba(255,184,0,0.1);
  border: 1px solid rgba(255,184,0,0.2);
  border-radius: 100px;
  padding: 0.4rem 1rem;
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--color-gold);
  margin-bottom: 1.5rem;
  animation: fadeInUp 0.6s ease forwards;
}
.hero-title {
  font-family: var(--font-display);
  font-size: clamp(3rem, 7vw, 5.5rem);
  line-height: 1;
  letter-spacing: 2px;
  margin-bottom: 1.5rem;
  animation: fadeInUp 0.7s ease 0.1s both;
}
.hero-title .line-orange {
  display: block;
  background: var(--grad-hero);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.hero-subtitle {
  font-size: 1.125rem;
  color: var(--color-muted);
  line-height: 1.7;
  margin-bottom: 2.5rem;
  max-width: 480px;
  animation: fadeInUp 0.7s ease 0.2s both;
}
.hero-actions {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  margin-bottom: 3rem;
  animation: fadeInUp 0.7s ease 0.3s both;
}
.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.9rem 2rem;
  border-radius: var(--radius-btn);
  font-size: 1rem;
  font-weight: 700;
  background: var(--grad-hero);
  color: #fff;
  transition: var(--transition);
  position: relative;
  overflow: hidden;
}
.btn-primary::before { content: ''; position: absolute; inset: 0; background: rgba(255,255,255,0); transition: var(--transition); }
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 16px 48px rgba(255,107,43,0.4); }
.btn-primary:hover::before { background: rgba(255,255,255,0.08); }
.btn-secondary {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.9rem 2rem;
  border-radius: var(--radius-btn);
  font-size: 1rem;
  font-weight: 700;
  background: rgba(255,255,255,0.05);
  color: var(--color-text);
  border: 1px solid var(--color-border);
  transition: var(--transition);
}
.btn-secondary:hover { background: rgba(255,255,255,0.1); transform: translateY(-2px); }
.hero-stats { display: flex; gap: 2.5rem; animation: fadeInUp 0.7s ease 0.4s both; }
.stat-item { text-align: left; }
.stat-num { font-family: var(--font-display); font-size: 2rem; color: var(--color-orange); letter-spacing: 1px; }
.stat-label { font-size: 0.8rem; color: var(--color-muted); font-weight: 500; }

/* Hero visual */
.hero-visual { position: relative; animation: fadeInRight 0.8s ease 0.2s both; }
.mockup-wrap { position: relative; z-index: 2; }
.browser-mock {
  background: #1A1714;
  border-radius: 16px;
  border: 1px solid rgba(255,255,255,0.1);
  overflow: hidden;
  box-shadow: 0 32px 80px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.05);
}
.browser-mock img {
  display: block;
  width: 100%;
  height: auto;
  object-fit: cover;
}
.float-badge {
  position: absolute;
  background: var(--color-card);
  border: 1px solid var(--color-border);
  border-radius: 12px;
  padding: 0.75rem 1rem;
  font-size: 0.8rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 16px 40px rgba(0,0,0,0.5);
  animation: float 3s ease-in-out infinite;
  z-index: 10;
}
.badge-1 { top: -1.5rem; left: -2rem; animation-delay: 0s; }
.badge-2 { bottom: 2rem; right: -2rem; animation-delay: 1.5s; }
.badge-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--color-lime); box-shadow: 0 0 8px var(--color-lime); }
.hero-glow-1, .hero-glow-2 { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; }
.hero-glow-1 { width: 300px; height: 300px; background: rgba(255,107,43,0.2); top: -50px; right: -50px; animation: pulse 4s ease-in-out infinite; }
.hero-glow-2 { width: 200px; height: 200px; background: rgba(123,31,162,0.2); bottom: 0; left: -30px; animation: pulse 4s ease-in-out infinite 2s; }

/* ===== MOBILE HERO BANNER ===== */
/* Visible only on mobile — imagen destacada del sitio */
.hero-banner-mobile {
  display: none;
  margin: 2rem 0 1rem;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 16px 48px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,107,43,0.15);
  position: relative;
}
.hero-banner-mobile img {
  display: block;
  width: 100%;
  height: auto;
  object-fit: cover;
}
.hero-banner-mobile-label {
  position: absolute;
  bottom: 0.75rem;
  left: 0.75rem;
  background: rgba(11,10,8,0.82);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255,107,43,0.3);
  border-radius: 8px;
  padding: 0.4rem 0.75rem;
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--color-orange);
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

/* ===== TICKER / MARQUEE ===== */
.ticker {
  overflow: hidden;
  border-top: 1px solid var(--color-border);
  border-bottom: 1px solid var(--color-border);
  background: rgba(255,107,43,0.04);
  padding: 1rem 0;
}
.ticker-inner {
  display: flex;
  gap: 3rem;
  animation: marquee 25s linear infinite;
  white-space: nowrap;
}
.ticker-item { display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; font-weight: 600; color: var(--color-muted); flex-shrink: 0; }
.ticker-icon { font-size: 1.1rem; }

/* ===== PAQUETES ===== */
.packages { padding: 6rem 0; }
.packages-header { text-align: center; margin-bottom: 4rem; }
.packages-header .section-sub { margin: 0 auto; }
.pricing-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
.pricing-card {
  background: var(--grad-card);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-card);
  padding: 2rem;
  position: relative;
  transition: var(--transition);
  cursor: pointer;
  overflow: hidden;
}
.pricing-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at 50% 0%, rgba(255,107,43,0.08), transparent 70%);
  opacity: 0;
  transition: var(--transition);
}
.pricing-card:hover::before { opacity: 1; }
.pricing-card:hover { border-color: rgba(255,107,43,0.3); transform: translateY(-6px); box-shadow: var(--shadow-card), var(--shadow-glow); }
.pricing-card.featured { border-color: rgba(255,107,43,0.5); background: linear-gradient(160deg, #231A12 0%, #141210 100%); }
.pricing-card.best { border-color: rgba(255,184,0,0.4); background: linear-gradient(160deg, #1F1A0D 0%, #141210 100%); }
.card-badge {
  display: inline-flex; align-items: center; gap: 0.4rem;
  padding: 0.35rem 0.85rem; border-radius: 100px;
  font-size: 0.7rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase;
  margin-bottom: 1.25rem; position: relative; z-index: 1;
}
.badge-hot { background: rgba(255,107,43,0.2); color: var(--color-orange); border: 1px solid rgba(255,107,43,0.4); }
.badge-best { background: rgba(255,184,0,0.15); color: var(--color-gold); border: 1px solid rgba(255,184,0,0.35); }
.badge-placeholder { height: 28px; margin-bottom: 1.25rem; }
.card-emoji { font-size: 2.2rem; margin-bottom: 0.75rem; display: block; }
.card-name { font-family: var(--font-display); font-size: 1.4rem; letter-spacing: 0.5px; color: var(--color-text); margin-bottom: 0.25rem; }
.card-tagline { font-size: 0.8rem; color: var(--color-muted); margin-bottom: 1.5rem; }
.card-price { margin-bottom: 1.5rem; }
.price-amount { font-family: var(--font-display); font-size: 2.6rem; color: var(--color-text); letter-spacing: 1px; }
.price-amount.orange { color: var(--color-orange); }
.price-amount.gold { color: var(--color-gold); }
.price-currency { font-size: 1rem; color: var(--color-muted); font-weight: 600; vertical-align: top; padding-top: 0.4rem; }
.price-desde { font-size: 0.8rem; color: var(--color-muted); }
.card-divider { height: 1px; background: var(--color-border); margin-bottom: 1.25rem; }
.card-features { list-style: none; display: flex; flex-direction: column; gap: 0.6rem; margin-bottom: 1.75rem; }
.card-features li { display: flex; align-items: flex-start; gap: 0.6rem; font-size: 0.875rem; color: var(--color-muted); line-height: 1.4; }
.feature-check { color: var(--color-lime); flex-shrink: 0; margin-top: 1px; font-size: 0.9rem; }
.feature-cross { color: #444; flex-shrink: 0; margin-top: 1px; }
.btn-card { width: 100%; padding: 0.85rem; border-radius: var(--radius-btn); font-size: 0.9rem; font-weight: 700; transition: var(--transition); position: relative; overflow: hidden; }
.btn-card-primary { background: var(--grad-hero); color: #fff; }
.btn-card-primary:hover { transform: translateY(-1px); box-shadow: 0 12px 32px rgba(255,107,43,0.4); }
.btn-card-gold { background: var(--grad-gold); color: #000; }
.btn-card-gold:hover { transform: translateY(-1px); box-shadow: 0 12px 32px rgba(255,184,0,0.4); }
.btn-card-outline { background: transparent; color: var(--color-text); border: 1px solid var(--color-border); }
.btn-card-outline:hover { border-color: rgba(255,107,43,0.4); background: rgba(255,107,43,0.05); }
.delivery-chip { display: inline-flex; align-items: center; gap: 0.4rem; margin-top: 0.75rem; font-size: 0.72rem; font-weight: 600; color: var(--color-muted); }
.delivery-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--color-lime); }

/* ===== DEMO BUTTON ===== */
.card-actions { display: flex; flex-direction: column; gap: 0.6rem; }
.btn-demo {
  width: 100%; padding: 0.75rem; border-radius: var(--radius-btn);
  font-size: 0.82rem; font-weight: 700;
  background: transparent; color: var(--color-muted);
  border: 1px dashed rgba(255,255,255,0.12);
  transition: var(--transition);
  display: flex; align-items: center; justify-content: center; gap: 0.5rem;
  cursor: pointer; position: relative; overflow: hidden;
}
.btn-demo::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg,rgba(139,218,79,0),rgba(139,218,79,0)); transition: var(--transition); }
.btn-demo:hover { color: var(--color-lime); border-color: rgba(139,218,79,0.4); border-style: solid; background: rgba(139,218,79,0.06); transform: translateY(-1px); }
.btn-demo:hover::before { background: linear-gradient(135deg,rgba(139,218,79,0.04),rgba(139,218,79,0)); }
.btn-demo .demo-pulse { width: 6px; height: 6px; border-radius: 50%; background: var(--color-lime); box-shadow: 0 0 6px var(--color-lime); animation: pulse-demo 2s ease-in-out infinite; }
@keyframes pulse-demo { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.4)} }
.demo-microcopy { text-align: center; font-size: 0.68rem; color: rgba(139,218,79,0.5); margin-top: -0.2rem; font-weight: 500; letter-spacing: 0.02em; }

/* ===== DEMO MODAL ===== */
.demo-modal-overlay {
  position: fixed; inset: 0; z-index: 3000;
  background: rgba(0,0,0,0.92); backdrop-filter: blur(16px);
  display: flex; align-items: center; justify-content: center;
  opacity: 0; pointer-events: none;
  transition: opacity 0.35s cubic-bezier(0.4,0,0.2,1);
}
.demo-modal-overlay.open { opacity: 1; pointer-events: all; }
.demo-modal {
  width: 94vw; max-width: 1100px; height: 85vh; max-height: 750px;
  background: #111; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px;
  overflow: hidden; display: flex; flex-direction: column;
  transform: scale(0.92) translateY(20px);
  transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1), opacity 0.35s ease;
  opacity: 0;
  box-shadow: 0 48px 120px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.05);
}
.demo-modal-overlay.open .demo-modal { transform: scale(1) translateY(0); opacity: 1; }
.demo-modal-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);
  background: rgba(26,23,20,0.95); flex-shrink: 0;
}
.demo-modal-info { display: flex; align-items: center; gap: 1rem; }
.demo-modal-badge {
  display: inline-flex; align-items: center; gap: 0.4rem;
  background: rgba(139,218,79,0.12); border: 1px solid rgba(139,218,79,0.25);
  border-radius: 100px; padding: 0.3rem 0.8rem;
  font-size: 0.68rem; font-weight: 700; color: var(--color-lime); text-transform: uppercase; letter-spacing: 0.06em;
}
.demo-modal-badge .live-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--color-lime); box-shadow: 0 0 8px var(--color-lime); animation: pulse-demo 1.5s ease-in-out infinite; }
.demo-modal-title { font-family: var(--font-display); font-size: 1.2rem; letter-spacing: 0.5px; }
.demo-modal-pkg { font-size: 0.78rem; color: var(--color-orange); font-weight: 600; }
.demo-modal-actions { display: flex; gap: 0.5rem; align-items: center; }
.btn-fullscreen { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.78rem; font-weight: 600; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: var(--color-text); transition: var(--transition); }
.btn-fullscreen:hover { background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.2); }
.btn-demo-close { width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: var(--color-text); font-size: 1.1rem; display: flex; align-items: center; justify-content: center; transition: var(--transition); }
.btn-demo-close:hover { background: rgba(232,41,76,0.2); border-color: rgba(232,41,76,0.4); color: var(--color-red); }
.demo-modal-body { flex: 1; position: relative; overflow: hidden; background: #0a0a0a; }
.demo-iframe { width: 100%; height: 100%; border: none; background: #fff; opacity: 0; transition: opacity 0.4s ease; }
.demo-iframe.loaded { opacity: 1; }
.demo-loader { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; background: #111; transition: opacity 0.4s ease; z-index: 2; }
.demo-loader.hidden { opacity: 0; pointer-events: none; }
.demo-loader-spinner { width: 40px; height: 40px; border: 3px solid rgba(255,107,43,0.15); border-top-color: var(--color-orange); border-radius: 50%; animation: spin-slow 0.8s linear infinite; }
.demo-loader-text { font-size: 0.85rem; color: var(--color-muted); font-weight: 500; }
.demo-modal-footer { border-top: 1px solid rgba(255,255,255,0.08); background: rgba(26,23,20,0.95); padding: 0.75rem 1.5rem; display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0; overflow-x: auto; }
.demo-modal-footer::-webkit-scrollbar { height: 0; }
.demo-thumb { flex-shrink: 0; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.75rem; font-weight: 600; color: var(--color-muted); background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); cursor: pointer; transition: var(--transition); white-space: nowrap; display: flex; align-items: center; gap: 0.4rem; }
.demo-thumb:hover { background: rgba(255,107,43,0.08); border-color: rgba(255,107,43,0.2); color: var(--color-text); }
.demo-thumb.active { background: rgba(255,107,43,0.15); border-color: rgba(255,107,43,0.4); color: var(--color-orange); }
.demo-footer-label { font-size: 0.68rem; font-weight: 700; color: var(--color-muted); text-transform: uppercase; letter-spacing: 0.06em; flex-shrink: 0; margin-right: 0.25rem; }
.demo-footer-sep { width: 1px; height: 24px; background: var(--color-border); flex-shrink: 0; }
.btn-buy-modal { flex-shrink: 0; margin-left: auto; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.78rem; font-weight: 700; background: var(--grad-hero); color: #fff; transition: var(--transition); }
.btn-buy-modal:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(255,107,43,0.4); }

/* ===== SERVICIOS ===== */
.services { padding: 6rem 0; background: rgba(255,107,43,0.02); }
.services-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-top: 3.5rem; }
.service-card { background: var(--color-card); border: 1px solid var(--color-border); border-radius: 16px; padding: 1.75rem; transition: var(--transition); }
.service-card:hover { border-color: rgba(255,107,43,0.25); transform: translateY(-4px); }
.service-icon { font-size: 2rem; margin-bottom: 1rem; display: block; }
.service-title { font-family: var(--font-display); font-size: 1.2rem; letter-spacing: 0.5px; margin-bottom: 0.5rem; }
.service-desc { font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; }
.service-list { list-style: none; margin-top: 1rem; display: flex; flex-direction: column; gap: 0.35rem; }
.service-list li { font-size: 0.8rem; color: var(--color-muted); display: flex; align-items: center; gap: 0.4rem; }
.service-list li::before { content: '•'; color: var(--color-orange); }

/* ===== PROCESO ===== */
.proceso { padding: 6rem 0; }
.proceso-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
  margin-top: 3.5rem;
  position: relative;
}
.proceso-grid::before {
  content: '';
  position: absolute;
  top: 2.5rem;
  left: 12.5%;
  width: 75%;
  height: 2px;
  background: linear-gradient(90deg, var(--color-orange), var(--color-red), var(--color-gold));
  opacity: 0.3;
}
.step-card { background: var(--color-card); border: 1px solid var(--color-border); border-radius: 16px; padding: 2rem 1.5rem; text-align: center; transition: var(--transition); position: relative; }
.step-card:hover { border-color: rgba(255,107,43,0.3); transform: translateY(-4px); }
.step-num { position: absolute; top: -14px; left: 50%; transform: translateX(-50%); width: 28px; height: 28px; border-radius: 50%; background: var(--grad-hero); font-size: 0.8rem; font-weight: 800; color: #fff; display: flex; align-items: center; justify-content: center; }
.step-icon { font-size: 2.2rem; display: block; margin-bottom: 1rem; margin-top: 0.5rem; }
.step-title { font-family: var(--font-display); font-size: 1.2rem; letter-spacing: 0.5px; margin-bottom: 0.5rem; }
.step-desc { font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; }

/* ===== BENEFICIOS ===== */
.beneficios { padding: 6rem 0; }
.beneficios-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
.beneficio-item { display: flex; align-items: flex-start; gap: 1rem; }
.beneficio-icon-wrap { font-size: 1.5rem; width: 48px; height: 48px; background: rgba(255,107,43,0.1); border: 1px solid rgba(255,107,43,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.beneficio-title { font-weight: 700; font-size: 0.95rem; margin-bottom: 0.25rem; }
.beneficio-desc { font-size: 0.85rem; color: var(--color-muted); line-height: 1.6; }

/* ===== TESTIMONIOS ===== */
.testimonios { padding: 6rem 0; background: rgba(255,107,43,0.02); }
.testimonios-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 3.5rem; }
.testi-card { background: var(--color-card); border: 1px solid var(--color-border); border-radius: var(--radius-card); padding: 2rem; transition: var(--transition); }
.testi-card:hover { border-color: rgba(255,107,43,0.2); transform: translateY(-4px); }
.testi-stars { color: var(--color-gold); font-size: 1rem; margin-bottom: 1rem; letter-spacing: 2px; }
.testi-quote { font-size: 0.9rem; color: var(--color-muted); line-height: 1.7; margin-bottom: 1.5rem; font-style: italic; }
.testi-author { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; }
.testi-avatar { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; color: #fff; flex-shrink: 0; }
.av-1 { background: linear-gradient(135deg,#FF6B2B,#E8294C); }
.av-2 { background: linear-gradient(135deg,#E8294C,#7B1FA2); }
.av-3 { background: linear-gradient(135deg,#FFB800,#FF6B2B); }
.av-4 { background: linear-gradient(135deg,#8BDA4F,#FFB800); }
.av-5 { background: linear-gradient(135deg,#7B1FA2,#E8294C); }
.av-6 { background: linear-gradient(135deg,#FF6B2B,#FFB800); }
.testi-name { font-weight: 700; font-size: 0.9rem; }
.testi-role { font-size: 0.78rem; color: var(--color-muted); }
.testi-package { font-size: 0.75rem; background: rgba(255,107,43,0.08); border: 1px solid rgba(255,107,43,0.15); border-radius: 8px; padding: 0.3rem 0.75rem; display: inline-block; color: var(--color-muted); }

/* ===== CHECKOUT ===== */
.checkout { padding: 6rem 0; }
.checkout-wrap { display: grid; grid-template-columns: 1fr 1.2fr; gap: 4rem; align-items: start; }
.checkout-form { background: var(--color-card); border: 1px solid var(--color-border); border-radius: var(--radius-card); padding: 2.5rem; }
.form-title { font-family: var(--font-display); font-size: 1.8rem; letter-spacing: 0.5px; margin-bottom: 0.4rem; }
.form-sub { font-size: 0.85rem; color: var(--color-muted); margin-bottom: 1.75rem; }
.package-selector { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-bottom: 1.25rem; }
.pkg-option { padding: 0.6rem 0.5rem; border-radius: 10px; font-size: 0.75rem; font-weight: 700; color: var(--color-muted); background: rgba(255,255,255,0.04); border: 1px solid var(--color-border); cursor: pointer; text-align: center; transition: var(--transition); }
.pkg-option:hover { border-color: rgba(255,107,43,0.3); color: var(--color-text); }
.pkg-option.active { background: rgba(255,107,43,0.12); border-color: rgba(255,107,43,0.4); color: var(--color-orange); }
.form-group { margin-bottom: 1rem; }
.form-label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-muted); margin-bottom: 0.4rem; }
.form-input, .form-textarea {
  width: 100%; padding: 0.75rem 1rem;
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 10px;
  color: var(--color-text);
  font-size: 0.9rem;
  font-family: var(--font-body);
  transition: var(--transition);
  outline: none;
}
.form-input::placeholder, .form-textarea::placeholder { color: rgba(138,130,120,0.5); }
.form-input:focus, .form-textarea:focus { border-color: rgba(255,107,43,0.5); background: rgba(255,107,43,0.04); box-shadow: 0 0 0 3px rgba(255,107,43,0.1); }
.form-textarea { resize: vertical; min-height: 100px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.selected-pkg-display { background: rgba(255,107,43,0.08); border: 1px solid rgba(255,107,43,0.2); border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; }
.selected-pkg-name { font-weight: 700; font-size: 0.9rem; }
.selected-pkg-price { font-family: var(--font-display); font-size: 1.4rem; color: var(--color-orange); }
.payment-methods { display: flex; gap: 0.5rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.pm-badge { background: rgba(255,255,255,0.05); border: 1px solid var(--color-border); border-radius: 8px; padding: 0.4rem 0.75rem; font-size: 0.72rem; font-weight: 600; color: var(--color-muted); }
.btn-buy { width: 100%; padding: 1rem; border-radius: var(--radius-btn); font-size: 1rem; font-weight: 800; background: var(--grad-hero); color: #fff; transition: var(--transition); position: relative; overflow: hidden; letter-spacing: 0.02em; }
.btn-buy:hover { transform: translateY(-2px); box-shadow: 0 16px 48px rgba(255,107,43,0.5); }
.btn-buy:active { transform: translateY(0); }
.secure-note { text-align: center; font-size: 0.75rem; color: var(--color-muted); margin-top: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.4rem; }

/* Success Modal */
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 2000; display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: var(--transition); backdrop-filter: blur(8px); }
.modal-overlay.open { opacity: 1; pointer-events: all; }
.modal-box { background: #1A1714; border: 1px solid rgba(139,218,79,0.3); border-radius: var(--radius-card); padding: 3rem; max-width: 480px; width: 90%; text-align: center; transform: scale(0.9); transition: var(--transition); }
.modal-overlay.open .modal-box { transform: scale(1); }
.modal-icon { font-size: 4rem; margin-bottom: 1rem; }
.modal-title { font-family: var(--font-display); font-size: 2rem; letter-spacing: 1px; margin-bottom: 0.75rem; color: var(--color-lime); }
.modal-msg { color: var(--color-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 2rem; }
.btn-modal { padding: 0.85rem 2rem; border-radius: var(--radius-btn); font-size: 0.9rem; font-weight: 700; background: var(--grad-hero); color: #fff; transition: var(--transition); }
.btn-modal:hover { transform: translateY(-1px); }

/* ===== CTA BANNER ===== */
.cta-banner { padding: 5rem 0; position: relative; overflow: hidden; }
.cta-banner::before { content: ''; position: absolute; inset: 0; background: var(--grad-hero); opacity: 0.06; }
.cta-banner-inner { background: var(--color-card); border: 1px solid rgba(255,107,43,0.2); border-radius: 24px; padding: 4rem 3rem; text-align: center; position: relative; overflow: hidden; }
.cta-banner-inner::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse 80% 60% at 50% 50%, rgba(255,107,43,0.06), transparent); }
.cta-emoji { font-size: 3rem; display: block; margin-bottom: 1rem; }
.cta-title { font-family: var(--font-display); font-size: clamp(2rem, 5vw, 3.5rem); letter-spacing: 1px; margin-bottom: 1rem; }
.cta-sub { font-size: 1rem; color: var(--color-muted); margin-bottom: 2.5rem; }
.cta-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }

/* ===== FOOTER ===== */
.footer { background: #080706; border-top: 1px solid var(--color-border); padding: 4rem 0 2rem; }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 3rem; margin-bottom: 3rem; }
.footer-logo { font-family: var(--font-display); font-size: 2rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
.footer-about { font-size: 0.875rem; color: var(--color-muted); line-height: 1.7; margin-bottom: 1.5rem; }
.social-links { display: flex; gap: 0.75rem; }
.social-btn { width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.06); border: 1px solid var(--color-border); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; color: var(--color-muted); text-decoration: none; transition: var(--transition); }
.social-btn:hover { background: rgba(255,107,43,0.15); border-color: rgba(255,107,43,0.3); color: var(--color-orange); }
.footer-col-title { font-weight: 700; font-size: 0.85rem; letter-spacing: 0.05em; color: var(--color-text); margin-bottom: 1.25rem; text-transform: uppercase; }
.footer-links { list-style: none; display: flex; flex-direction: column; gap: 0.6rem; }
.footer-links a { font-size: 0.875rem; color: var(--color-muted); transition: var(--transition); }
.footer-links a:hover { color: var(--color-orange); }
.footer-bottom { border-top: 1px solid var(--color-border); padding-top: 1.75rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
.footer-copy { font-size: 0.8rem; color: var(--color-muted); }
.footer-legal { display: flex; gap: 1.5rem; }
.footer-legal a { font-size: 0.8rem; color: var(--color-muted); transition: var(--transition); }
.footer-legal a:hover { color: var(--color-orange); }

/* ===== WHATSAPP FLOAT ===== */
.whatsapp-float { position: fixed; bottom: 2rem; right: 2rem; z-index: 500; width: 56px; height: 56px; border-radius: 50%; background: #25D366; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; box-shadow: 0 8px 24px rgba(37,211,102,0.4); transition: var(--transition); animation: pulse-wa 2s ease-in-out infinite; text-decoration: none; }
.whatsapp-float:hover { transform: scale(1.1); box-shadow: 0 12px 32px rgba(37,211,102,0.6); }

/* ===== ANIMATIONS ===== */
@keyframes fadeInUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
@keyframes fadeInRight { from{opacity:0;transform:translateX(32px)} to{opacity:1;transform:translateX(0)} }
@keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
@keyframes pulse { 0%,100%{opacity:0.3;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.05)} }
@keyframes pulse-wa { 0%,100%{box-shadow:0 8px 24px rgba(37,211,102,0.4)} 50%{box-shadow:0 8px 32px rgba(37,211,102,0.7)} }
@keyframes marquee { from{transform:translateX(0)} to{transform:translateX(-50%)} }
@keyframes spin-slow { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
.reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
.reveal.visible { opacity: 1; transform: translateY(0); }

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
  .pricing-grid { grid-template-columns: repeat(2, 1fr); }
  .services-grid { grid-template-columns: repeat(2, 1fr); }
  .footer-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
}
@media (max-width: 768px) {
  .nav-links { display: none; }
  .hamburger { display: flex; }
  .hero-inner { grid-template-columns: 1fr; gap: 0; }
  /* Desktop visual oculto, banner mobile toma su lugar */
  .hero-visual { display: none; }
  .hero-banner-mobile { display: block; }
  .pricing-grid { grid-template-columns: 1fr; }
  .services-grid { grid-template-columns: 1fr; }
  .proceso-grid { grid-template-columns: 1fr 1fr; }
  .proceso-grid::before { display: none; }
  .testimonios-grid { grid-template-columns: 1fr; }
  .checkout-wrap { grid-template-columns: 1fr; }
  .beneficios-grid { grid-template-columns: 1fr; }
  .footer-grid { grid-template-columns: 1fr; }
  .footer-bottom { flex-direction: column; text-align: center; }
  .package-selector { grid-template-columns: 1fr; }
  .form-row { grid-template-columns: 1fr; }
  .hero-stats { gap: 1.5rem; }
  .demo-modal { width: 98vw; height: 90vh; max-height: none; border-radius: 12px; }
  .demo-modal-header { padding: 0.75rem 1rem; flex-wrap: wrap; gap: 0.5rem; }
  .demo-modal-title { font-size: 1rem; }
  .demo-modal-footer { padding: 0.5rem 0.75rem; }
  .demo-thumb { font-size: 0.7rem; padding: 0.4rem 0.75rem; }
  .btn-buy-modal { display: none; }
}
@media (max-width: 480px) {
  .proceso-grid { grid-template-columns: 1fr; }
  .hero-actions { flex-direction: column; }
  .btn-primary, .btn-secondary { width: 100%; justify-content: center; }
  .cta-actions { flex-direction: column; align-items: center; }
}
</style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar" id="navbar">
  <a href="#" class="navbar-logo">
    <span class="logo-icon">🌮</span>
    <span class="logo-taqueros">Taqueros</span><span class="logo-web">Web</span><span class="logo-mx">.com</span>
  </a>
  <ul class="nav-links">
    <li><a href="#paquetes">Paquetes</a></li>
    <li><a href="#servicios">Servicios</a></li>
    <li><a href="#proceso">Proceso</a></li>
    <li><a href="#testimonios">Clientes</a></li>
    <li><a href="#contacto">Contacto</a></li>
  </ul>
  <div class="nav-cta">
    <a href="#paquetes" class="btn-nav">🔥 Ver paquetes</a>
  </div>
  <button class="hamburger" id="hamburger" aria-label="Menú">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- ===== MOBILE MENU ===== -->
<div class="mobile-menu" id="mobileMenu">
  <button class="mobile-close" id="mobileClose">✕</button>
  <a href="#paquetes" class="mobile-link">🌮 Paquetes</a>
  <a href="#servicios" class="mobile-link">⚡ Servicios</a>
  <a href="#proceso" class="mobile-link">📋 Proceso</a>
  <a href="#testimonios" class="mobile-link">⭐ Clientes</a>
  <a href="#contacto" class="mobile-link">📩 Contactar</a>
  <a href="#paquetes" class="btn-primary">🔥 Comprar ahora</a>
</div>

<!-- ===== HERO ===== -->
<section class="hero section" id="inicio">
  <div class="container">
    <div class="hero-inner">
      <div class="hero-content">
        <div class="hero-eyebrow">🌮 &nbsp;El taco digital que tu negocio necesita</div>
        <h1 class="hero-title">
          TU SITIO WEB
          <span class="line-orange">LISTO EN DÍAS,</span>
          SIN ROLLO
        </h1>
        <p class="hero-subtitle">
          Sitios web profesionales al precio de unos tacos de canasta. Rápidos, bonitos y que sí convierten. Sin conocimientos técnicos, sin complicaciones.
        </p>
        <div class="hero-actions">
          <a href="#paquetes" class="btn-primary">🔥 Ver paquetes</a>
          <a href="#contacto" class="btn-secondary">💬 Comprar ahora</a>
        </div>
        <div class="hero-stats">
          <div class="stat-item"><div class="stat-num">+320</div><div class="stat-label">Sitios entregados</div></div>
          <div class="stat-item"><div class="stat-num">98%</div><div class="stat-label">Clientes satisfechos</div></div>
          <div class="stat-item"><div class="stat-num">3 días</div><div class="stat-label">Entrega promedio</div></div>
        </div>

        <!-- Banner exclusivo para mobile -->
        <div class="hero-banner-mobile">
          <img
            src="images/taquerosweb_banner.jpg"
            alt="TaquerosWeb — Sitios web profesionales para negocios mexicanos"
            width="800"
            height="450"
            loading="lazy"
          />
          <div class="hero-banner-mobile-label">
            <span>🚀</span> Sitio en producción
          </div>
        </div>
      </div>

      <!-- Hero visual — sólo desktop -->
      <div class="hero-visual">
        <div class="hero-glow-1"></div>
        <div class="hero-glow-2"></div>
        <div class="mockup-wrap">
          <div class="float-badge badge-1">
            <span class="badge-dot"></span>
            Sitio en producción 🚀
          </div>
          <div class="browser-mock">
            <img
              src="images/taquerosweb_banner.jpg"
              alt="Ejemplo de sitio web para taquería hecho por TaquerosWeb"
              width="600"
              height="338"
              loading="eager"
            />
          </div>
          <div class="float-badge badge-2">
            🌮 +320 sitios entregados
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== TICKER / MARQUEE ===== -->
<div class="ticker">
  <div class="ticker-inner">
    <div class="ticker-item"><span class="ticker-icon">🌮</span> Entrega en 3-7 días</div>
    <div class="ticker-item"><span class="ticker-icon">📱</span> 100% Responsive</div>
    <div class="ticker-item"><span class="ticker-icon">💬</span> WhatsApp integrado</div>
    <div class="ticker-item"><span class="ticker-icon">🔍</span> SEO incluido</div>
    <div class="ticker-item"><span class="ticker-icon">🔥</span> Soporte post-entrega</div>
    <div class="ticker-item"><span class="ticker-icon">💳</span> Paga a meses sin intereses</div>
    <div class="ticker-item"><span class="ticker-icon">🛡️</span> SSL incluido</div>
    <div class="ticker-item"><span class="ticker-icon">🌮</span> Precios en MXN</div>
    <div class="ticker-item"><span class="ticker-icon">👁️</span> Demos interactivos en cada paquete</div>
    <div class="ticker-item"><span class="ticker-icon">🌮</span> Entrega en 3-7 días</div>
    <div class="ticker-item"><span class="ticker-icon">📱</span> 100% Responsive</div>
    <div class="ticker-item"><span class="ticker-icon">💬</span> WhatsApp integrado</div>
    <div class="ticker-item"><span class="ticker-icon">🔍</span> SEO incluido</div>
    <div class="ticker-item"><span class="ticker-icon">🔥</span> Soporte post-entrega</div>
    <div class="ticker-item"><span class="ticker-icon">💳</span> Paga a meses sin intereses</div>
    <div class="ticker-item"><span class="ticker-icon">🛡️</span> SSL incluido</div>
    <div class="ticker-item"><span class="ticker-icon">🌮</span> Precios en MXN</div>
    <div class="ticker-item"><span class="ticker-icon">👁️</span> Demos interactivos en cada paquete</div>
  </div>
</div>

<!-- ===== PAQUETES ===== -->
<section class="packages section" id="paquetes">
  <div class="container">
    <div class="packages-header">
      <div class="section-tag">🌮 Menú de paquetes</div>
      <h2 class="section-title">Elige el taco<br><span class="highlight">digital perfecto</span></h2>
      <p class="section-sub">Desde una landing rápida hasta un e-commerce completo. Todos los paquetes incluyen diseño profesional y entrega express. <strong style="color:var(--color-lime)">👁️ ¡Ahora con demos interactivos!</strong></p>
    </div>

    <div class="pricing-grid" id="pricingGrid">

      <?php
      // Configuración de features por paquete
      $features = [
          'starter' => [
              'inc' => ['1 página de aterrizaje','Diseño responsive mobile-first','Botón de WhatsApp','Formulario de contacto','SEO básico (títulos y meta)','Entrega en 3 días hábiles'],
              'exc' => ['Dominio y hosting','Blog o secciones extra'],
          ],
          'basico' => [
              'inc' => ['Todo en uno (servicios, nosotros, galería, reseñas, contacto)','Diseño responsive','WhatsApp + Mapa de ubicación','Formulario de contacto avanzado','SEO básico optimizado','Galería de fotos o Video','Entrega en 5 días hábiles'],
              'exc' => ['Tienda o catálogo'],
          ],
          'catalogo' => [
              'inc' => ['Hasta 6 páginas personalizadas','Catálogo de productos/servicios','Diseño premium responsive','WhatsApp + Redes sociales','SEO intermedio (palabras clave)','Google Analytics incluido','Entrega en 5-7 días hábiles'],
              'exc' => [],
          ],
          'tienda' => [
              'inc' => ['Tienda con hasta 30 productos','Carrito de compras','Integración MercadoPago / PayPal','Gestión de inventario básica','Diseño responsive premium','SEO e-commerce optimizado','Entrega en 7-10 días hábiles'],
              'exc' => ['Panel admin avanzado'],
          ],
          'pro' => [
              'inc' => ['Sitio multi-página sin límite','E-commerce completo (hasta 100 productos)','Panel de administración','SEO profesional + Google My Business','Blog avanzado con CMS','Dominio por 1 año incluido','Hosting 1 año incluido','1 mes de soporte gratuito'],
              'exc' => [],
          ],
          'premium' => [
              'inc' => ['Desarrollo 100% a tu medida','Integraciones de APIs y sistemas','Plataformas y apps web','SEO avanzado y estrategia digital','Dominio + Hosting premium 1 año','3 meses de soporte incluido','Reuniones de seguimiento','Cotización personalizada'],
              'exc' => [],
          ],
      ];

      foreach ($paquetes as $key => $p):
          $f = $features[$key];
          $cardClass = 'pricing-card reveal';
          if ($key === 'catalogo') $cardClass .= ' featured';
          if ($key === 'pro')      $cardClass .= ' best';

          $precioFormato = '$' . fmt($p['precio']) . ' MXN';
          $dataPrice = ($p['precio_desde'] ?? false)
              ? 'Desde $' . fmt($p['precio']) . ' MXN'
              : $precioFormato;
      ?>
      <div class="<?= $cardClass ?>"
           data-pkg="<?= htmlspecialchars($p['nombre']) ?> — <?= htmlspecialchars($p['subtitulo']) ?>"
           data-price="<?= $dataPrice ?>"
           data-demo="<?= $p['demo'] ?>"
           data-demo-biz="<?= htmlspecialchars($p['demo_biz']) ?>">

        <?php if ($p['tipo_badge'] === 'hot'): ?>
          <div class="card-badge badge-hot">🔥 Más vendido</div>
        <?php elseif ($p['tipo_badge'] === 'best'): ?>
          <div class="card-badge badge-best">⭐ Mejor opción</div>
        <?php else: ?>
          <div class="badge-placeholder"></div>
        <?php endif; ?>

        <span class="card-emoji"><?= $p['emoji'] ?></span>
        <div class="card-name"><?= $p['nombre'] ?></div>
        <div class="card-tagline"><?= $p['subtitulo'] ?></div>

        <div class="card-price">
          <?php if ($p['precio_desde'] ?? false): ?>
            <div class="price-desde">Desde</div>
          <?php endif; ?>
          <span class="price-currency">$</span><span class="price-amount<?= $key === 'catalogo' ? ' orange' : ($key === 'pro' ? ' gold' : '') ?>"><?= fmt($p['precio']) ?></span>
          <span class="price-currency"> MXN</span>
        </div>

        <div class="card-divider"></div>

        <ul class="card-features">
          <?php foreach ($f['inc'] as $item): ?>
            <li><span class="feature-check">✓</span> <?= htmlspecialchars($item) ?></li>
          <?php endforeach; ?>
          <?php foreach ($f['exc'] as $item): ?>
            <li><span class="feature-cross">✗</span> <?= htmlspecialchars($item) ?></li>
          <?php endforeach; ?>
        </ul>

        <div class="card-actions">
          <button class="btn-card <?= $p['btn_clase'] ?>" onclick="selectPackage(this)"><?= $p['btn_texto'] ?></button>
          <button class="btn-demo" onclick="openDemo(this)"><span class="demo-pulse"></span> 👁️ Ver demo en vivo</button>
          <div class="demo-microcopy">Mira exactamente cómo se verá tu sitio</div>
        </div>
        <div class="delivery-chip"><div class="delivery-dot"></div> Entrega: <?= $p['entrega'] ?></div>
      </div>
      <?php endforeach; ?>

    </div><!-- /pricing-grid -->
  </div>
</section>

<!-- ===== SERVICIOS ===== -->
<section class="services section" id="servicios">
  <div class="container">
    <div class="section-tag">⚡ ¿Qué incluye?</div>
    <h2 class="section-title">Todo lo que necesita<br><span class="highlight">tu negocio digital</span></h2>
    <p class="section-sub">Cada paquete viene cargado con lo esencial. No vendemos sitios a medias — vendemos presencia digital completa.</p>
    <div class="services-grid">
      <div class="service-card reveal"><span class="service-icon">📱</span><div class="service-title">Diseño Responsive</div><div class="service-desc">Tu sitio se ve perfecto en celular, tablet y computadora. El 80% de tus clientes te visitan desde el teléfono.</div><ul class="service-list"><li>Mobile-first por defecto</li><li>Optimizado para todos los navegadores</li><li>Velocidad de carga rápida</li></ul></div>
      <div class="service-card reveal"><span class="service-icon">💬</span><div class="service-title">WhatsApp Integrado</div><div class="service-desc">Botón de contacto directo con tu número. Tus clientes te escriben en un clic y tú cierras ventas desde el chat.</div><ul class="service-list"><li>Botón flotante personalizable</li><li>Mensaje predefinido automático</li><li>Link de catálogo integrable</li></ul></div>
      <div class="service-card reveal"><span class="service-icon">🔍</span><div class="service-title">SEO Básico</div><div class="service-desc">Configuramos tu sitio para que Google lo encuentre. Más visibilidad = más clientes sin pagar publicidad.</div><ul class="service-list"><li>Títulos y metas optimizados</li><li>Velocidad optimizada (Core Web Vitals)</li><li>Google Search Console setup</li></ul></div>
      <div class="service-card reveal"><span class="service-icon">☁️</span><div class="service-title">Hosting Opcional</div><div class="service-desc">Manejamos tu infraestructura o te orientamos con el mejor hosting según tu presupuesto y necesidades.</div><ul class="service-list"><li>SSL (https) gratuito incluido</li><li>Backups automáticos</li><li>Uptime 99.9% garantizado</li></ul></div>
      <div class="service-card reveal"><span class="service-icon">⚡</span><div class="service-title">Tiempos Express</div><div class="service-desc">Entregamos en tiempo récord sin sacrificar calidad. El hambre de resultados no espera — nosotros tampoco.</div><ul class="service-list"><li>Starter: 3 días hábiles</li><li>Catálogo: 5–7 días hábiles</li><li>Negocio Pro: 10–14 días</li></ul></div>
      <div class="service-card reveal"><span class="service-icon">🛡️</span><div class="service-title">Soporte Post-Entrega</div><div class="service-desc">No desaparecemos después de entregar. Estamos aquí para ajustes, dudas y actualizaciones.</div><ul class="service-list"><li>30 días de revisiones incluidas</li><li>Soporte por WhatsApp</li><li>Manual básico de uso</li></ul></div>
      <div class="service-card reveal"><span class="service-icon">📊</span><div class="service-title">Analytics Integrado</div><div class="service-desc">Conectamos Google Analytics y Tag Manager para que sepas exactamente cuántos visitan tu sitio y de dónde vienen.</div><ul class="service-list"><li>Google Analytics 4</li><li>Mapa de calor (paquetes Pro)</li><li>Reporte mensual básico</li></ul></div>
      <div class="service-card reveal"><span class="service-icon">🎨</span><div class="service-title">Diseño Personalizado</div><div class="service-desc">Sin plantillas genéricas. Diseñamos con tu identidad, colores, logo y la personalidad de tu marca.</div><ul class="service-list"><li>Identidad visual consistente</li><li>Tipografías y paleta personalizada</li><li>Revisiones incluidas en el proceso</li></ul></div>
    </div>
  </div>
</section>

<!-- ===== PROCESO ===== -->
<section class="proceso section" id="proceso">
  <div class="container">
    <div style="text-align:center;margin-bottom:1rem">
      <div class="section-tag">📋 ¿Cómo funciona?</div>
      <h2 class="section-title">Del taco a tu pantalla<br><span class="highlight">en 4 pasos</span></h2>
      <p class="section-sub" style="margin:0 auto">Más fácil que pedir tacos a domicilio. Literalmente.</p>
    </div>
    <div class="proceso-grid">
      <div class="step-card reveal"><div class="step-num">1</div><span class="step-icon">🌮</span><div class="step-title">Elige tu paquete</div><div class="step-desc">Revisa nuestro menú y selecciona el paquete que mejor se adapte a tu negocio y presupuesto.</div></div>
      <div class="step-card reveal"><div class="step-num">2</div><span class="step-icon">💳</span><div class="step-title">Realiza el pago</div><div class="step-desc">Pago seguro con tarjeta, transferencia o a meses sin intereses. Recibe tu comprobante al instante.</div></div>
      <div class="step-card reveal"><div class="step-num">3</div><span class="step-icon">📝</span><div class="step-title">Envía tu info</div><div class="step-desc">Completa un formulario sencillo con los datos de tu negocio, logo y lo que quieres comunicar.</div></div>
      <div class="step-card reveal"><div class="step-num">4</div><span class="step-icon">🚀</span><div class="step-title">Recibe tu sitio</div><div class="step-desc">Revisamos juntos el resultado, hacemos ajustes y lanzamos tu sitio al mundo digital. ¡Listo!</div></div>
    </div>
  </div>
</section>

<!-- ===== BENEFICIOS ===== -->
<section class="beneficios section" id="beneficios">
  <div class="container">
    <div class="beneficios-grid">
      <div>
        <div class="section-tag">✅ ¿Por qué nosotros?</div>
        <h2 class="section-title">La salsa secreta<br><span class="highlight">de cada proyecto</span></h2>
        <p class="section-sub">No solo construimos sitios web — construimos herramientas de venta que trabajan por ti las 24 horas.</p>
        <div style="margin-top:2rem"><a href="#paquetes" class="btn-primary">🔥 Ver paquetes ahora</a></div>
      </div>
      <div style="display:flex;flex-direction:column;gap:1.5rem">
        <div class="beneficio-item reveal"><div class="beneficio-icon-wrap">⚡</div><div><div class="beneficio-title">Entrega ultrarrápida</div><div class="beneficio-desc">Mientras otros tardan semanas, nosotros entregamos en días. Sabemos que cada día sin sitio es dinero perdido.</div></div></div>
        <div class="beneficio-item reveal"><div class="beneficio-icon-wrap">💰</div><div><div class="beneficio-title">Precios en MXN sin sorpresas</div><div class="beneficio-desc">Precios fijos en pesos mexicanos. Sin cobros ocultos, sin tipos de cambio, sin "te cotizo mañana".</div></div></div>
        <div class="beneficio-item reveal"><div class="beneficio-icon-wrap">🧠</div><div><div class="beneficio-title">Sin conocimientos técnicos</div><div class="beneficio-desc">Tú dinos qué quieres y nosotros lo construimos. No necesitas saber de código ni de diseño, de eso nos encargamos.</div></div></div>
        <div class="beneficio-item reveal"><div class="beneficio-icon-wrap">🛡️</div><div><div class="beneficio-title">Soporte real y humano</div><div class="beneficio-desc">Un asesor real, no un bot. Contacto directo por WhatsApp para que nunca te quedes con dudas.</div></div></div>
      </div>
    </div>
  </div>
</section>

<!-- ===== TESTIMONIOS ===== -->
<section class="testimonios section" id="testimonios">
  <div class="container">
    <div style="text-align:center;margin-bottom:1rem">
      <div class="section-tag">⭐ Lo que dicen nuestros clientes</div>
      <h2 class="section-title">Clientes felices<br><span class="highlight">resultados reales</span></h2>
    </div>
    <div class="testimonios-grid">
      <div class="testi-card reveal"><div class="testi-stars">★★★★★</div><p class="testi-quote">Tenía mi taquería sin presencia en internet y en una semana ya tenía mi sitio con menú, galería y botón de WhatsApp. Mis pedidos aumentaron un 40% en el primer mes.</p><div class="testi-author"><div class="testi-avatar av-1">R</div><div><div class="testi-name">Roberto Mendoza</div><div class="testi-role">Tacos El Compadre, CDMX</div></div></div><div class="testi-package">🌮 Catálogo – Productos</div></div>
      <div class="testi-card reveal"><div class="testi-stars">★★★★★</div><p class="testi-quote">Pensé que un sitio web iba a ser carísimo y complicado. Me llevé la sorpresa de que fue rápido, económico y el resultado quedó increíble. Lo recomiendo con mis ojos cerrados.</p><div class="testi-author"><div class="testi-avatar av-2">L</div><div><div class="testi-name">Laura García</div><div class="testi-role">Estética LauraBeauty, Guadalajara</div></div></div><div class="testi-package">💬 Básico – Sitio de Contacto</div></div>
      <div class="testi-card reveal"><div class="testi-stars">★★★★★</div><p class="testi-quote">Vendí mis primeros productos en línea a los dos días de lanzar mi tienda. El equipo me guió en todo el proceso y la integración con MercadoPago funcionó a la perfección.</p><div class="testi-author"><div class="testi-avatar av-3">C</div><div><div class="testi-name">Carlos Reyes</div><div class="testi-role">Artesanías Reyes, Oaxaca</div></div></div><div class="testi-package">🛒 Tienda Lite – E-commerce</div></div>
      <div class="testi-card reveal"><div class="testi-stars">★★★★★</div><p class="testi-quote">Mi agencia de viajes necesitaba un sitio profesional urgente. En 5 días hábiles ya estaba en línea con todo: cotizador, galería de destinos y formulario de reserva. ¡Excelente trabajo!</p><div class="testi-author"><div class="testi-avatar av-4">M</div><div><div class="testi-name">Mónica Vázquez</div><div class="testi-role">Viajes Horizonte, Monterrey</div></div></div><div class="testi-package">💼 Negocio Pro</div></div>
      <div class="testi-card reveal"><div class="testi-stars">★★★★★</div><p class="testi-quote">La atención es de primera. Siempre disponibles por WhatsApp, explicaron cada detalle y entregaron antes del tiempo prometido. El sitio quedó exactamente como lo imaginé.</p><div class="testi-author"><div class="testi-avatar av-5">A</div><div><div class="testi-name">Alejandro Torres</div><div class="testi-role">Clínica Dental Torres, Puebla</div></div></div><div class="testi-package">💼 Negocio Pro</div></div>
      <div class="testi-card reveal"><div class="testi-stars">★★★★★</div><p class="testi-quote">Emprendedora de tiempo completo y sin tiempo para aprender de tecnología. Me hicieron todo y ahora mis clientas me encuentran en Google. Invertí $3,999 y lo recuperé en semanas.</p><div class="testi-author"><div class="testi-avatar av-6">P</div><div><div class="testi-name">Patricia Lozano</div><div class="testi-role">Pastelería Paty's, León Gto.</div></div></div><div class="testi-package">💬 Básico – Sitio de Contacto</div></div>
    </div>
  </div>
</section>

<!-- ===== CHECKOUT / FORMULARIO ===== -->
<section class="checkout section" id="contacto">
  <div class="container">
    <div class="checkout-wrap">
      <div class="checkout-info">
        <div class="section-tag">🛒 Ordena tu sitio</div>
        <h2 class="section-title">Completa tu<br><span class="highlight">pedido digital</span></h2>
        <p class="section-sub" style="margin-bottom:2rem">Llena el formulario, elige tu paquete y uno de nuestros asesores te contactará en menos de 24 horas para coordinar los detalles.</p>
        <div style="display:flex;flex-direction:column;gap:1.25rem">
          <div class="beneficio-item"><div class="beneficio-icon-wrap">✅</div><div><div class="beneficio-title">Sin riesgo</div><div class="beneficio-desc">Si no quedas satisfecho con el diseño, te ajustamos sin costo adicional.</div></div></div>
          <div class="beneficio-item"><div class="beneficio-icon-wrap">🔒</div><div><div class="beneficio-title">Pago 100% seguro</div><div class="beneficio-desc">Procesamos pagos con Stripe y MercadoPago. Tus datos siempre protegidos.</div></div></div>
          <div class="beneficio-item"><div class="beneficio-icon-wrap">⚡</div><div><div class="beneficio-title">Respuesta en menos de 24h</div><div class="beneficio-desc">Confirmamos tu pedido y arrancamos el mismo día hábil que recibimos tu pago.</div></div></div>
        </div>
      </div>

      <div class="checkout-form reveal">
        <div class="form-title">¡Pide tu sitio! 🌮</div>
        <div class="form-sub">Selecciona tu paquete y llena tus datos. Es más fácil que pedir tacos.</div>
        <div class="package-selector" id="pkgSelector">
          <?php foreach ($paquetes as $key => $p):
              $label = ($p['precio_desde'] ?? false) ? 'Desde $' . fmt($p['precio']) : '$' . fmt($p['precio']);
              $pkgId = $p['nombre'] . ' — ' . $p['subtitulo'];
              $pkgPrice = ($p['precio_desde'] ?? false)
                  ? 'Desde $' . fmt($p['precio']) . ' MXN'
                  : '$' . fmt($p['precio']) . ' MXN';
          ?>
          <div class="pkg-option<?= $key === 'starter' ? ' active' : '' ?>"
               data-pkg="<?= htmlspecialchars($pkgId) ?>"
               data-price="<?= htmlspecialchars($pkgPrice) ?>"
               onclick="selectPkg(this)">
            <?= $p['emoji'] ?> <?= $p['nombre'] ?> <?= $label ?>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="selected-pkg-display" id="selectedPkgDisplay">
          <div>
            <div style="font-size:0.72rem;color:var(--color-muted);margin-bottom:2px">Paquete seleccionado</div>
            <div class="selected-pkg-name" id="selPkgName">🌮 <?= $paquetes['starter']['nombre'] ?> — <?= $paquetes['starter']['subtitulo'] ?></div>
          </div>
          <div class="selected-pkg-price" id="selPkgPrice">$<?= fmt($paquetes['starter']['precio']) ?> MXN</div>
        </div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">Nombre completo *</label><input type="text" class="form-input" placeholder="Juan Pérez" id="fName" /></div>
          <div class="form-group"><label class="form-label">Correo electrónico *</label><input type="email" class="form-input" placeholder="juan@ejemplo.com" id="fEmail" /></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">Teléfono / WhatsApp *</label><input type="tel" class="form-input" placeholder="+52 56 6286 6353" id="fPhone" /></div>
          <div class="form-group"><label class="form-label">Nombre de tu negocio *</label><input type="text" class="form-input" placeholder="Barber Shop Luxor" id="fBusiness" /></div>
        </div>
        <div class="form-group"><label class="form-label">Cuéntanos tu negocio</label><textarea class="form-textarea" placeholder="Describe a qué se dedica tu negocio, a quién va dirigido y qué esperas de tu sitio web..." id="fDesc"></textarea></div>
        <!-- Honeypot antispam -->
        <div style="position:absolute;left:-9999px;top:-9999px;opacity:0;height:0;overflow:hidden" aria-hidden="true">
          <input type="text" id="fWebsite" name="website" tabindex="-1" autocomplete="off" />
        </div>
        <div style="font-size:0.78rem;color:var(--color-muted);margin-bottom:0.5rem;font-weight:600">Métodos de pago disponibles:</div>
        <div class="payment-methods">
          <div class="pm-badge">💳 Tarjeta de crédito/débito</div>
          <div class="pm-badge">📲 MercadoPago</div>
          <div class="pm-badge">🏦 Transferencia SPEI</div>
          <div class="pm-badge">📅 Meses sin intereses</div>
        </div>
        <button class="btn-buy" onclick="submitOrder()">🌮 ¡Ordenar mi sitio web!</button>
        <div class="secure-note">🔒 Pago seguro con cifrado SSL · Tus datos están protegidos</div>
      </div>
    </div>
  </div>
</section>

<!-- ===== CTA BANNER ===== -->
<section class="cta-banner section">
  <div class="container">
    <div class="cta-banner-inner reveal">
      <span class="cta-emoji">🌮</span>
      <h2 class="cta-title">¿Sigues sin sitio web?<br><span class="highlight">Tus competidores sí tienen.</span></h2>
      <p class="cta-sub">Cada día que pasa es un cliente potencial que no te encuentra en Google. ¡Arranca hoy!</p>
      <div class="cta-actions">
        <a href="#paquetes" class="btn-primary">🔥 Ver paquetes</a>
        <a href="https://wa.me/<?= $cfg['whatsapp'] ?>?text=Hola%2C%20quiero%20info%20sobre%20sitios%20web" target="_blank" class="btn-secondary">💬 Hablar con un asesor</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer section" id="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <div class="footer-logo"><span>🌮</span><span style="color:var(--color-orange)">Taqueros</span><span>Web</span><span style="color:var(--color-gold);font-size:1.2rem">.com</span></div>
        <p class="footer-about">Creamos sitios web profesionales para negocios mexicanos que quieren crecer en internet. Rápidos, bonitos y que sí convierten — como un buen taco bien preparado.</p>
        <div class="social-links">
          <a href="#" class="social-btn" title="Facebook">📘</a>
          <a href="#" class="social-btn" title="Instagram">📸</a>
          <a href="#" class="social-btn" title="TikTok">🎵</a>
          <a href="#" class="social-btn" title="LinkedIn">💼</a>
          <a href="https://wa.me/<?= $cfg['whatsapp'] ?>" class="social-btn" title="WhatsApp">💬</a>
        </div>
      </div>
      <div>
        <div class="footer-col-title">Paquetes</div>
        <ul class="footer-links">
          <?php foreach ($paquetes as $p): ?>
          <li><a href="#paquetes"><?= $p['emoji'] ?> <?= $p['nombre'] ?> — $<?= fmt($p['precio']) ?><?= ($p['precio_desde'] ?? false) ? '+' : '' ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Empresa</div>
        <ul class="footer-links">
          <li><a href="#servicios">Servicios</a></li>
          <li><a href="#proceso">Cómo funciona</a></li>
          <li><a href="#testimonios">Testimonios</a></li>
          <li><a href="#beneficios">Beneficios</a></li>
          <li><a href="#contacto">Contacto</a></li>
          <li><a href="#">Blog</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Contacto</div>
        <ul class="footer-links">
          <li><a href="mailto:<?= $cfg['email'] ?>">📧 <?= $cfg['email'] ?></a></li>
          <li><a href="https://wa.me/<?= $cfg['whatsapp'] ?>" target="_blank">💬 WhatsApp directo</a></li>
          <li><a href="#">📍 Ciudad de México, MX</a></li>
          <li><a href="#">🕐 Lun–Vie 9:00–18:00</a></li>
        </ul>
        <div style="margin-top:1.5rem">
          <div class="footer-col-title">¿Tienes dudas?</div>
          <a href="https://wa.me/<?= $cfg['whatsapp'] ?>?text=Hola%2C%20quiero%20info" target="_blank" class="btn-primary" style="display:inline-flex;padding:0.7rem 1.25rem;font-size:0.85rem">💬 Escríbenos</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-copy">© <?= date('Y') ?> TaquerosWeb.com — Todos los derechos reservados 🌮</div>
      <div class="footer-legal">
        <a href="#">Aviso de privacidad</a>
        <a href="#">Términos y condiciones</a>
        <a href="#">Política de reembolsos</a>
      </div>
    </div>
  </div>
</footer>

<!-- ===== WHATSAPP FLOAT ===== -->
<a href="https://wa.me/<?= $cfg['whatsapp'] ?>?text=Hola%2C%20me%20interesa%20un%20sitio%20web%20de%20TaquerosWeb.com"
   class="whatsapp-float" target="_blank" title="Contactar por WhatsApp">💬</a>

<!-- ===== SUCCESS MODAL ===== -->
<div class="modal-overlay" id="successModal">
  <div class="modal-box">
    <div class="modal-icon">🌮</div>
    <div class="modal-title">¡Pedido Enviado!</div>
    <p class="modal-msg">Tu orden está en la cocina 👨‍🍳<br><br>Un asesor de <strong>TaquerosWeb.com</strong> se comunicará contigo en menos de <strong>24 horas hábiles</strong> para confirmar tu pago y arrancar con el diseño.<br><br>Mientras tanto, revisa tu correo y ten listo tu logo 🎨</p>
    <button class="btn-modal" onclick="closeModal()">¡Perfecto, gracias! 🚀</button>
  </div>
</div>

<!-- ===== DEMO MODAL ===== -->
<div class="demo-modal-overlay" id="demoModal">
  <div class="demo-modal">
    <div class="demo-modal-header">
      <div class="demo-modal-info">
        <div class="demo-modal-badge"><span class="live-dot"></span> Demo interactivo</div>
        <div>
          <div class="demo-modal-title" id="demoModalTitle">Cargando demo...</div>
          <div class="demo-modal-pkg" id="demoModalPkg"></div>
        </div>
      </div>
      <div class="demo-modal-actions">
        <button class="btn-fullscreen" id="btnFullscreen" onclick="openDemoFullscreen()">⛶ Pantalla completa</button>
        <button class="btn-demo-close" onclick="closeDemoModal()">✕</button>
      </div>
    </div>
    <div class="demo-modal-body">
      <div class="demo-loader" id="demoLoader">
        <div class="demo-loader-spinner"></div>
        <div class="demo-loader-text">Cargando demo interactivo...</div>
      </div>
      <iframe class="demo-iframe" id="demoIframe" title="Demo preview" sandbox="allow-scripts allow-same-origin"></iframe>
    </div>
    <div class="demo-modal-footer" id="demoFooter">
      <div class="demo-footer-label">📂 Demos:</div>
      <div class="demo-footer-sep"></div>
      <?php foreach ($paquetes as $key => $p): if ($key === 'premium') continue; ?>
      <button class="demo-thumb"
              data-demo="<?= $p['demo'] ?>"
              data-title="<?= htmlspecialchars($p['demo_biz']) ?>"
              data-pkg="<?= htmlspecialchars($p['nombre']) ?> — $<?= fmt($p['precio']) ?>"
              onclick="switchDemo(this)"><?= $p['emoji'] ?> <?= $p['nombre'] ?></button>
      <?php endforeach; ?>
      <button class="btn-buy-modal" onclick="closeDemoModal();document.getElementById('contacto').scrollIntoView({behavior:'smooth'})">🔥 Comprar ahora</button>
    </div>
  </div>
</div>

<!-- ===== JAVASCRIPT ===== -->
<script>
// ─── Navbar scroll ───
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  navbar.classList.toggle('scrolled', window.scrollY > 50);
});

// ─── Hamburger menu ───
const hamburger = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobileMenu');
const mobileClose = document.getElementById('mobileClose');
hamburger.addEventListener('click', () => mobileMenu.classList.add('open'));
mobileClose.addEventListener('click', () => mobileMenu.classList.remove('open'));
document.querySelectorAll('.mobile-link, .mobile-menu .btn-primary').forEach(link => {
  link.addEventListener('click', () => mobileMenu.classList.remove('open'));
});

// ─── Smooth scroll ───
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    const target = document.querySelector(this.getAttribute('href'));
    if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
  });
});

// ─── Scroll reveal ───
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry, i) => {
    if (entry.isIntersecting) {
      setTimeout(() => entry.target.classList.add('visible'), i * 80);
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

// ─── Pricing card → scroll to form ───
function selectPackage(btn) {
  const card = btn.closest('.pricing-card');
  const pkg = card.dataset.pkg;
  const price = card.dataset.price;
  document.querySelectorAll('.pkg-option').forEach(opt => {
    opt.classList.toggle('active', opt.dataset.pkg === pkg);
  });
  document.getElementById('selPkgName').textContent = pkg;
  document.getElementById('selPkgPrice').textContent = price;
  document.getElementById('contacto').scrollIntoView({ behavior: 'smooth' });
}

// ─── Package selector in form ───
function selectPkg(el) {
  document.querySelectorAll('.pkg-option').forEach(o => o.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('selPkgName').textContent = el.dataset.pkg;
  document.getElementById('selPkgPrice').textContent = el.dataset.price;
}

// ─── Order submission ───
async function submitOrder() {
  const name     = document.getElementById('fName').value.trim();
  const email    = document.getElementById('fEmail').value.trim();
  const phone    = document.getElementById('fPhone').value.trim();
  const biz      = document.getElementById('fBusiness').value.trim();
  const desc     = document.getElementById('fDesc').value.trim();
  const honeypot = document.getElementById('fWebsite').value;
  const pkg      = document.getElementById('selPkgName').textContent.trim();
  const price    = document.getElementById('selPkgPrice').textContent.trim();

  if (honeypot) return; // bot trap

  const requiredFields = [
    { id: 'fName', val: name },
    { id: 'fEmail', val: email },
    { id: 'fPhone', val: phone },
    { id: 'fBusiness', val: biz }
  ];
  let hasError = false;
  requiredFields.forEach(f => {
    const el = document.getElementById(f.id);
    if (!f.val) {
      hasError = true;
      el.style.borderColor = 'var(--color-red)';
      el.style.boxShadow = '0 0 0 3px rgba(232,41,76,0.2)';
      setTimeout(() => { el.style.borderColor = ''; el.style.boxShadow = ''; }, 2500);
    }
  });
  if (hasError) return;

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    const el = document.getElementById('fEmail');
    el.style.borderColor = 'var(--color-red)';
    el.style.boxShadow = '0 0 0 3px rgba(232,41,76,0.2)';
    setTimeout(() => { el.style.borderColor = ''; el.style.boxShadow = ''; }, 2500);
    return;
  }

  const btn = document.querySelector('.btn-buy');
  btn.textContent = '⏳ Enviando tu orden...';
  btn.disabled = true;

  try {
    const res = await fetch('/api/send-order.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name, email, phone, biz, desc, pkg, price })
    });
    const data = await res.json();
    if (data.success) {
      ['fName','fEmail','fPhone','fBusiness','fDesc'].forEach(id => { document.getElementById(id).value = ''; });
      openSuccessModal();
    } else {
      alert('Hubo un problema al enviar tu orden. Por favor intenta de nuevo o escríbenos por WhatsApp.');
    }
  } catch (err) {
    alert('Error de conexión. Por favor verifica tu internet e intenta de nuevo.');
  } finally {
    btn.textContent = '🌮 ¡Ordenar mi sitio web!';
    btn.disabled = false;
  }
}

// ─── Success Modal ───
function openSuccessModal() {
  document.getElementById('successModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeModal() {
  document.getElementById('successModal').classList.remove('open');
  document.body.style.overflow = '';
}
document.getElementById('successModal').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});

// ─── Demo Modal ───
const demoOverlay = document.getElementById('demoModal');
const demoIframe  = document.getElementById('demoIframe');
const demoLoader  = document.getElementById('demoLoader');
const demoTitle   = document.getElementById('demoModalTitle');
const demoPkg     = document.getElementById('demoModalPkg');
let currentDemoUrl = '';

function openDemo(btn) {
  const card    = btn.closest('.pricing-card');
  const demoUrl = card.dataset.demo;
  const bizName = card.dataset.demoBiz || 'Demo';
  const pkgName = card.dataset.pkg;
  const price   = card.dataset.price;

  // ── Mobile: abrir en nueva pestaña, sin iframe ──
  if (window.innerWidth <= 768) {
    window.open(demoUrl, '_blank', 'noopener,noreferrer');
    return;
  }

  // ── Desktop: modal interactivo ──
  loadDemoInModal(demoUrl, bizName, pkgName + ' — ' + price);
}

function loadDemoInModal(url, title, pkgInfo) {
  currentDemoUrl = url;
  demoTitle.textContent = title;
  demoPkg.textContent   = pkgInfo;
  demoLoader.classList.remove('hidden');
  demoIframe.classList.remove('loaded');
  document.querySelectorAll('.demo-thumb').forEach(t => {
    t.classList.toggle('active', t.dataset.demo === url);
  });
  demoIframe.src = url;
  demoIframe.onload = function() {
    demoLoader.classList.add('hidden');
    demoIframe.classList.add('loaded');
  };
  demoOverlay.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function switchDemo(thumb) {
  loadDemoInModal(thumb.dataset.demo, thumb.dataset.title, thumb.dataset.pkg);
}

function closeDemoModal() {
  demoOverlay.classList.remove('open');
  document.body.style.overflow = '';
  setTimeout(() => {
    demoIframe.src = 'about:blank';
    demoIframe.classList.remove('loaded');
    demoLoader.classList.remove('hidden');
  }, 400);
}

function openDemoFullscreen() {
  if (currentDemoUrl) window.open(currentDemoUrl, '_blank', 'noopener,noreferrer');
}

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    if (demoOverlay.classList.contains('open')) closeDemoModal();
    if (document.getElementById('successModal').classList.contains('open')) closeModal();
  }
});
demoOverlay.addEventListener('click', (e) => {
  if (e.target === demoOverlay) closeDemoModal();
});

// ─── Pricing card hover spring ───
document.querySelectorAll('.pricing-card').forEach(card => {
  card.addEventListener('mouseenter', function() { this.style.transition = 'all 0.25s cubic-bezier(0.34,1.56,0.64,1)'; });
  card.addEventListener('mouseleave', function() { this.style.transition = 'all 0.3s cubic-bezier(0.4,0,0.2,1)'; });
});

// ─── Active nav highlight on scroll ───
const sections = document.querySelectorAll('section[id]');
const navLinks  = document.querySelectorAll('.nav-links a');
const sectionObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      navLinks.forEach(link => {
        link.style.color = link.getAttribute('href') === '#' + entry.target.id
          ? 'var(--color-text)' : '';
      });
    }
  });
}, { threshold: 0.4 });
sections.forEach(s => sectionObserver.observe(s));
</script>
</body>
</html>
