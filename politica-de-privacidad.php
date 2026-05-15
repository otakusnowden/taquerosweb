<?php
declare(strict_types=1);
require_once __DIR__ . '/config/app.php';
use App\Core\Auth;
$authUser = Auth::user();
$cfg      = require __DIR__ . '/config.php';
$siteUrl  = rtrim($cfg['site_url'], '/');
$siteName = $cfg['site_name'];
$email    = $cfg['email'];
$year     = date('Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>Política de Privacidad — TaquerosWeb.com</title>
<meta name="description" content="Conoce cómo TaquerosWeb recopila, usa y protege tu información personal. Cumplimos con la Ley Federal de Protección de Datos Personales (LFPDPPP) de México." />
<meta name="robots" content="index, follow" />
<link rel="canonical" href="<?= $siteUrl ?>/politica-de-privacidad" />
<meta property="og:title"       content="Política de Privacidad — TaquerosWeb.com" />
<meta property="og:description" content="Aviso de privacidad de TaquerosWeb. Cumplimos con la LFPDPPP de México." />
<meta property="og:url"         content="<?= $siteUrl ?>/politica-de-privacidad" />
<meta property="og:type"        content="website" />
<meta property="og:site_name"   content="<?= $siteName ?>" />

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

<style>
:root {
  --color-bg:      #0B0A08;
  --color-surface: #141210;
  --color-card:    #1A1714;
  --color-border:  rgba(255,255,255,0.07);
  --color-orange:  #FF6B2B;
  --color-gold:    #FFB800;
  --color-text:    #F5F0E8;
  --color-muted:   #8A8278;
  --grad-hero:     linear-gradient(135deg, #FF6B2B 0%, #E8294C 50%, #7B1FA2 100%);
  --font-display:  'Bebas Neue', sans-serif;
  --font-body:     'Manrope', sans-serif;
  --radius-card:   20px;
  --radius-btn:    12px;
  --transition:    all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  --shadow-glow:   0 0 40px rgba(255,107,43,0.25);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; font-size: 16px; }
body { font-family: var(--font-body); background: var(--color-bg); color: var(--color-text); line-height: 1.6; overflow-x: hidden; }
a { color: var(--color-orange); text-decoration: none; }
a:hover { text-decoration: underline; }
img { max-width: 100%; }
button { font-family: var(--font-body); cursor: pointer; border: none; }
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: var(--color-bg); }
::-webkit-scrollbar-thumb { background: var(--color-orange); border-radius: 3px; }
body::before {
  content: '';
  position: fixed; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
  pointer-events: none; z-index: 0; opacity: 0.5;
}

/* ── Navbar ── */
.navbar {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  padding: 1rem 2rem;
  display: flex; align-items: center; justify-content: space-between;
  background: rgba(11,10,8,0.92); backdrop-filter: blur(20px);
  border-bottom: 1px solid var(--color-border);
}
.navbar-logo { display: flex; align-items: center; gap: 0.5rem; font-family: var(--font-display); font-size: 1.8rem; letter-spacing: 1px; text-decoration: none; }
.logo-taqueros { color: var(--color-orange); }
.logo-web { color: var(--color-text); }
.logo-mx { color: var(--color-gold); font-size: 1.1rem; vertical-align: super; }
.btn-nav {
  padding: 0.6rem 1.5rem; border-radius: var(--radius-btn);
  font-size: 0.875rem; font-weight: 700; background: var(--grad-hero); color: #fff;
  transition: var(--transition); display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none;
}
.btn-nav:hover { transform: translateY(-1px); box-shadow: var(--shadow-glow); text-decoration: none; }

/* ── Main layout ── */
.pp-wrap {
  position: relative; z-index: 1;
  max-width: 800px; margin: 0 auto;
  padding: 8rem 1.5rem 5rem;
}

/* ── Hero block ── */
.pp-hero {
  text-align: center;
  margin-bottom: 3rem;
  padding-bottom: 2.5rem;
  border-bottom: 1px solid var(--color-border);
}
.pp-eyebrow {
  display: inline-flex; align-items: center; gap: 0.5rem;
  background: rgba(255,107,43,0.1); border: 1px solid rgba(255,107,43,0.2);
  border-radius: 100px; padding: 0.4rem 1rem;
  font-size: 0.75rem; font-weight: 700; color: var(--color-orange);
  letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.25rem;
}
.pp-title {
  font-family: var(--font-display);
  font-size: clamp(2.2rem, 5vw, 3.2rem);
  letter-spacing: 1px; line-height: 1.05;
  margin-bottom: 0.75rem;
}
.pp-meta { font-size: 0.85rem; color: var(--color-muted); }
.pp-meta strong { color: var(--color-text); }

/* ── TOC ── */
.pp-toc {
  background: var(--color-card);
  border: 1px solid var(--color-border);
  border-left: 3px solid var(--color-orange);
  border-radius: var(--radius-card);
  padding: 1.5rem;
  margin-bottom: 3rem;
}
.pp-toc-title { font-size: 0.75rem; font-weight: 700; color: var(--color-orange); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem; }
.pp-toc ol { list-style: decimal; padding-left: 1.25rem; display: grid; gap: 0.35rem; }
.pp-toc li { font-size: 0.875rem; color: var(--color-muted); }
.pp-toc a { color: var(--color-muted); transition: var(--transition); }
.pp-toc a:hover { color: var(--color-orange); text-decoration: none; }

/* ── Sections ── */
.pp-section { margin-bottom: 3rem; scroll-margin-top: 100px; }
.pp-section-num {
  display: inline-flex; align-items: center; justify-content: center;
  width: 2rem; height: 2rem; border-radius: 50%;
  background: rgba(255,107,43,0.12); border: 1px solid rgba(255,107,43,0.2);
  font-size: 0.75rem; font-weight: 800; color: var(--color-orange);
  margin-bottom: 0.75rem;
}
.pp-section h2 {
  font-family: var(--font-display); font-size: 1.6rem; letter-spacing: 0.5px;
  color: var(--color-text); margin-bottom: 1rem; line-height: 1.2;
}
.pp-section p { font-size: 0.95rem; color: var(--color-muted); line-height: 1.75; margin-bottom: 0.9rem; }
.pp-section p:last-child { margin-bottom: 0; }
.pp-section ul, .pp-section ol { padding-left: 1.4rem; display: grid; gap: 0.5rem; margin: 0.75rem 0; }
.pp-section li { font-size: 0.95rem; color: var(--color-muted); line-height: 1.65; }
.pp-section strong { color: var(--color-text); font-weight: 600; }
.pp-section a { color: var(--color-orange); }

/* ── Highlight box ── */
.pp-box {
  background: var(--color-card);
  border: 1px solid var(--color-border);
  border-radius: 14px;
  padding: 1.25rem 1.5rem;
  margin: 1.25rem 0;
}
.pp-box-label { font-size: 0.72rem; font-weight: 700; color: var(--color-orange); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; }
.pp-box p { margin: 0; }

/* ── Divider ── */
.pp-divider { height: 1px; background: var(--color-border); margin: 2rem 0; }

/* ── Contact card ── */
.pp-contact-card {
  background: var(--color-card);
  border: 1px solid rgba(255,107,43,0.15);
  border-radius: var(--radius-card);
  padding: 1.75rem;
  text-align: center;
  margin-top: 3rem;
}
.pp-contact-card .icon { font-size: 2rem; margin-bottom: 0.75rem; }
.pp-contact-card h3 { font-family: var(--font-display); font-size: 1.5rem; letter-spacing: 0.5px; margin-bottom: 0.5rem; }
.pp-contact-card p { font-size: 0.9rem; color: var(--color-muted); margin-bottom: 1.25rem; }
.btn-primary {
  display: inline-flex; align-items: center; gap: 0.5rem;
  padding: 0.85rem 2rem; border-radius: var(--radius-btn);
  font-size: 0.95rem; font-weight: 700;
  background: var(--grad-hero); color: #fff;
  transition: var(--transition); text-decoration: none;
}
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 16px 48px rgba(255,107,43,0.4); text-decoration: none; }

/* ── Footer ── */
.pp-footer {
  border-top: 1px solid var(--color-border);
  padding: 2rem 1.5rem;
  text-align: center;
  position: relative; z-index: 1;
}
.pp-footer-logo { font-family: var(--font-display); font-size: 1.5rem; letter-spacing: 1px; margin-bottom: 0.75rem; }
.pp-footer p { font-size: 0.82rem; color: var(--color-muted); }
.pp-footer a { color: var(--color-muted); }
.pp-footer a:hover { color: var(--color-orange); }

@media (max-width: 768px) {
  .navbar { padding: 1rem; }
  .pp-wrap { padding: 7rem 1.25rem 4rem; }
}
</style>
</head>
<body>

<!-- ── Navbar ── -->
<nav class="navbar" aria-label="Navegación principal">
  <a href="/" class="navbar-logo" aria-label="TaquerosWeb inicio">
    <span>🌮</span>
    <span class="logo-taqueros">Taqueros</span><span class="logo-web">Web</span><span class="logo-mx">.com</span>
  </a>
  <a href="/" class="btn-nav">← Inicio</a>
</nav>

<!-- ── Content ── -->
<main class="pp-wrap" id="main-content">

  <!-- Hero -->
  <div class="pp-hero">
    <div class="pp-eyebrow">🔒 Aviso legal</div>
    <h1 class="pp-title">Política de Privacidad</h1>
    <p class="pp-meta">
      <strong>TaquerosWeb.com</strong> &nbsp;·&nbsp; Última actualización: <?= date('d \d\e F \d\e Y', mktime(0,0,0, (int)date('n'), (int)date('j'), (int)date('Y'))) ?>
    </p>
  </div>

  <!-- TOC -->
  <nav class="pp-toc" aria-label="Índice de contenido">
    <div class="pp-toc-title">Contenido</div>
    <ol>
      <li><a href="#s1">¿Quiénes somos?</a></li>
      <li><a href="#s2">Datos que recopilamos</a></li>
      <li><a href="#s3">Uso de Google Analytics</a></li>
      <li><a href="#s4">Cookies</a></li>
      <li><a href="#s5">Formularios de contacto</a></li>
      <li><a href="#s6">WhatsApp</a></li>
      <li><a href="#s7">Protección de datos</a></li>
      <li><a href="#s8">Derechos ARCO</a></li>
      <li><a href="#s9">Servicios de terceros</a></li>
      <li><a href="#s10">Cambios en esta política</a></li>
      <li><a href="#s11">Contacto</a></li>
    </ol>
  </nav>

  <!-- Intro -->
  <section class="pp-section" id="s1">
    <div class="pp-section-num">1</div>
    <h2>¿Quiénes somos?</h2>
    <p>
      <strong>TaquerosWeb</strong> es una empresa mexicana dedicada al diseño y desarrollo de sitios web profesionales para negocios y emprendedores. Operamos bajo el dominio <strong>taquerosweb.com</strong> y nos comprometemos a respetar y proteger la privacidad de todas las personas que interactúan con nuestro sitio.
    </p>
    <p>
      La presente Política de Privacidad tiene como objetivo informarte sobre el tratamiento que damos a tus datos personales, en cumplimiento con la <strong>Ley Federal de Protección de Datos Personales en Posesión de los Particulares (LFPDPPP)</strong> y su Reglamento vigentes en México.
    </p>
    <div class="pp-box">
      <div class="pp-box-label">Responsable del tratamiento</div>
      <p><strong>TaquerosWeb.com</strong> — Ciudad de México, México<br>Correo: <a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a></p>
    </div>
  </section>

  <div class="pp-divider"></div>

  <!-- Datos recopilados -->
  <section class="pp-section" id="s2">
    <div class="pp-section-num">2</div>
    <h2>Datos que recopilamos</h2>
    <p>En función de cómo uses nuestro sitio, podemos recopilar los siguientes tipos de información:</p>
    <ul>
      <li><strong>Datos de identificación:</strong> nombre, apellidos, correo electrónico y número de teléfono (cuando llenas nuestro formulario de contacto o registro).</li>
      <li><strong>Datos de uso:</strong> páginas visitadas, tiempo en el sitio, clics, dispositivo y navegador (a través de Google Analytics).</li>
      <li><strong>Datos técnicos:</strong> dirección IP (anonimizada), tipo de navegador, sistema operativo y resolución de pantalla.</li>
      <li><strong>Comunicaciones:</strong> mensajes que nos envías por WhatsApp o correo electrónico.</li>
    </ul>
    <p>No recopilamos datos bancarios, información de tarjetas de crédito ni datos sensibles a través de este sitio.</p>
  </section>

  <div class="pp-divider"></div>

  <!-- Google Analytics -->
  <section class="pp-section" id="s3">
    <div class="pp-section-num">3</div>
    <h2>Uso de Google Analytics</h2>
    <p>
      Utilizamos <strong>Google Analytics 4</strong> y <strong>Google Tag Manager</strong>, servicios de análisis web proporcionados por Google LLC. Estas herramientas nos permiten entender cómo los visitantes usan nuestro sitio, qué páginas son más populares y cómo mejorar la experiencia general.
    </p>
    <p>Google Analytics recopila datos de uso de forma <strong>anónima y agregada</strong>. Hemos configurado el sitio para:</p>
    <ul>
      <li>Aplicar <strong>anonimización de IP</strong> para no registrar tu dirección IP completa.</li>
      <li>Respetar el <strong>modo de consentimiento</strong> (Consent Mode v2) de Google: las funciones de análisis solo se activan cuando el usuario acepta las cookies.</li>
      <li>No compartir datos con fines publicitarios de Google.</li>
    </ul>
    <p>
      Puedes conocer más sobre cómo Google trata los datos en su
      <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Política de Privacidad</a>.
      También puedes instalar el
      <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener noreferrer">complemento de inhabilitación de Google Analytics</a>
      para tu navegador.
    </p>
  </section>

  <div class="pp-divider"></div>

  <!-- Cookies -->
  <section class="pp-section" id="s4">
    <div class="pp-section-num">4</div>
    <h2>Cookies</h2>
    <p>
      Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas un sitio web. Usamos cookies para mejorar el funcionamiento del sitio y analizar su uso.
    </p>
    <p>Tipos de cookies que utilizamos:</p>
    <ul>
      <li><strong>Cookies de sesión:</strong> necesarias para funciones de autenticación (si creas una cuenta). Se eliminan al cerrar el navegador.</li>
      <li><strong>Cookies analíticas:</strong> proporcionadas por Google Analytics para medir el tráfico y el comportamiento de los visitantes. Solo se activan si aceptas las cookies.</li>
      <li><strong>Cookies de preferencias:</strong> almacenamos localmente tu decisión de aceptar o no las cookies (<code>tw_cookies_ok</code>) para no mostrarte el aviso repetidamente.</li>
    </ul>
    <p>Puedes gestionar o eliminar las cookies desde la configuración de tu navegador en cualquier momento. Ten en cuenta que deshabilitar ciertas cookies puede afectar el funcionamiento de algunas funciones del sitio.</p>
    <div class="pp-box">
      <div class="pp-box-label">Cómo gestionar cookies</div>
      <p>Accede a la configuración de cookies de tu navegador: <strong>Chrome</strong> › Configuración › Privacidad y seguridad · <strong>Firefox</strong> › Preferencias › Privacidad · <strong>Safari</strong> › Preferencias › Privacidad.</p>
    </div>
  </section>

  <div class="pp-divider"></div>

  <!-- Formularios -->
  <section class="pp-section" id="s5">
    <div class="pp-section-num">5</div>
    <h2>Formularios de contacto y registro</h2>
    <p>
      Cuando rellenas nuestro formulario de registro o de contacto, recopilamos los datos que proporcionas voluntariamente (nombre, correo, teléfono y descripción de tu proyecto). Esta información se usa exclusivamente para:
    </p>
    <ul>
      <li>Procesar tu solicitud de servicio o cotización.</li>
      <li>Enviarte información sobre el estado de tu pedido.</li>
      <li>Contactarte para dar seguimiento a tu proyecto.</li>
    </ul>
    <p>No usamos esta información para enviar publicidad no solicitada ni la vendemos a terceros.</p>
  </section>

  <div class="pp-divider"></div>

  <!-- WhatsApp -->
  <section class="pp-section" id="s6">
    <div class="pp-section-num">6</div>
    <h2>WhatsApp</h2>
    <p>
      Nuestro sitio incluye un botón de contacto directo mediante <strong>WhatsApp</strong>. Si decides utilizarlo, serás redirigido a la aplicación de WhatsApp (propiedad de Meta Platforms, Inc.), donde la comunicación queda sujeta a los términos y la política de privacidad de dicha plataforma.
    </p>
    <p>
      Los mensajes que nos envíes por WhatsApp son gestionados exclusivamente por nuestro equipo para atender tu consulta y no son compartidos con terceros.
      Puedes consultar la <a href="https://www.whatsapp.com/legal/privacy-policy" target="_blank" rel="noopener noreferrer">Política de Privacidad de WhatsApp</a>.
    </p>
  </section>

  <div class="pp-divider"></div>

  <!-- Protección -->
  <section class="pp-section" id="s7">
    <div class="pp-section-num">7</div>
    <h2>Protección de datos</h2>
    <p>
      Implementamos medidas técnicas y organizativas razonables para proteger tus datos personales contra accesos no autorizados, pérdida o divulgación indebida:
    </p>
    <ul>
      <li>Transmisión cifrada mediante <strong>HTTPS/TLS</strong>.</li>
      <li>Almacenamiento seguro con acceso restringido por roles.</li>
      <li>Contraseñas almacenadas con <strong>hash bcrypt</strong> (nunca en texto plano).</li>
      <li>Encabezados de seguridad HTTP configurados en servidor.</li>
    </ul>
    <p>
      Conservamos tus datos únicamente durante el tiempo necesario para cumplir con la finalidad para la que fueron recopilados o según lo requiera la ley.
    </p>
  </section>

  <div class="pp-divider"></div>

  <!-- ARCO -->
  <section class="pp-section" id="s8">
    <div class="pp-section-num">8</div>
    <h2>Derechos ARCO</h2>
    <p>
      De conformidad con la <strong>LFPDPPP</strong>, tienes derecho a ejercer en cualquier momento los siguientes derechos sobre tus datos personales:
    </p>
    <ul>
      <li><strong>Acceso:</strong> conocer qué datos personales tuyos tenemos y cómo los usamos.</li>
      <li><strong>Rectificación:</strong> solicitar la corrección de datos inexactos o incompletos.</li>
      <li><strong>Cancelación:</strong> pedir la eliminación de tus datos cuando ya no sean necesarios o cuando hayas retirado tu consentimiento.</li>
      <li><strong>Oposición:</strong> oponerte al tratamiento de tus datos para determinadas finalidades.</li>
    </ul>
    <div class="pp-box">
      <div class="pp-box-label">Cómo ejercer tus derechos</div>
      <p>Envía tu solicitud al correo <a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a> con el asunto <strong>"Derechos ARCO"</strong>, indicando tu nombre completo, descripción de la solicitud y copia de tu identificación oficial. Responderemos en un plazo máximo de <strong>20 días hábiles</strong>.</p>
    </div>
  </section>

  <div class="pp-divider"></div>

  <!-- Terceros -->
  <section class="pp-section" id="s9">
    <div class="pp-section-num">9</div>
    <h2>Servicios de terceros</h2>
    <p>Para operar correctamente, nuestro sitio integra servicios de terceros que pueden tener acceso limitado a ciertos datos:</p>
    <ul>
      <li><strong>Google Analytics / Tag Manager</strong> (Google LLC) — análisis de uso y estadísticas del sitio.</li>
      <li><strong>MercadoPago</strong> (Mercado Libre S. de R.L. de C.V.) — procesamiento seguro de pagos; los datos de pago son manejados exclusivamente por MercadoPago.</li>
      <li><strong>WhatsApp / Meta</strong> — canal de comunicación con usuarios (cuando el usuario inicia el contacto).</li>
      <li><strong>Proveedor de correo</strong> — envío de correos transaccionales (verificación de cuenta, confirmaciones de pedido).</li>
    </ul>
    <p>Ninguno de estos proveedores está autorizado a usar tus datos para fines distintos a los aquí descritos. Cada uno cuenta con sus propias políticas de privacidad y medidas de seguridad.</p>
  </section>

  <div class="pp-divider"></div>

  <!-- Cambios -->
  <section class="pp-section" id="s10">
    <div class="pp-section-num">10</div>
    <h2>Cambios en esta política</h2>
    <p>
      Nos reservamos el derecho de actualizar esta Política de Privacidad en cualquier momento. Cualquier cambio relevante será notificado mediante un aviso visible en nuestro sitio web y, de ser posible, por correo electrónico a los usuarios registrados.
    </p>
    <p>
      La fecha de la última actualización siempre estará indicada al inicio de este documento. Te recomendamos revisarla periódicamente.
    </p>
  </section>

  <div class="pp-divider"></div>

  <!-- Contacto -->
  <section class="pp-section" id="s11">
    <div class="pp-section-num">11</div>
    <h2>Contacto</h2>
    <p>Si tienes preguntas, dudas o solicitudes relacionadas con esta política o con el tratamiento de tus datos personales, comunícate con nosotros:</p>
    <ul>
      <li><strong>Correo:</strong> <a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a></li>
      <li><strong>WhatsApp:</strong> <a href="https://wa.me/<?= $cfg['whatsapp'] ?>" target="_blank" rel="noopener noreferrer">Escribir por WhatsApp</a></li>
    </ul>
  </section>

  <!-- Contact CTA -->
  <div class="pp-contact-card">
    <div class="icon">🔒</div>
    <h3>Tu privacidad nos importa</h3>
    <p>¿Tienes dudas sobre cómo manejamos tus datos? Estamos aquí para ayudarte.</p>
    <a href="mailto:<?= htmlspecialchars($email) ?>" class="btn-primary">📧 Contactar al equipo</a>
  </div>

</main>

<!-- ── Footer ── -->
<footer class="pp-footer">
  <div class="pp-footer-logo">
    <span>🌮</span> <span style="color:var(--color-orange)">Taqueros</span><span>Web</span><span style="color:var(--color-gold)">.com</span>
  </div>
  <p>© <?= $year ?> TaquerosWeb.com — Todos los derechos reservados &nbsp;·&nbsp; <a href="/">Inicio</a> &nbsp;·&nbsp; <a href="/politica-de-privacidad">Privacidad</a></p>
</footer>

</body>
</html>
