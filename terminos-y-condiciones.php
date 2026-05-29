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

<title>Términos y Condiciones — TaquerosWeb.com</title>
<meta name="description" content="Términos y condiciones del servicio de TaquerosWeb: desarrollo de sitios web, diseño, mantenimiento, pagos, entregas, garantías y legislación aplicable en México." />
<meta name="robots" content="index, follow" />
<link rel="canonical" href="<?= $siteUrl ?>/terminos-y-condiciones" />
<meta property="og:title"       content="Términos y Condiciones — TaquerosWeb.com" />
<meta property="og:description" content="Términos y condiciones del servicio de TaquerosWeb. Desarrollo web profesional en México." />
<meta property="og:url"         content="<?= $siteUrl ?>/terminos-y-condiciones" />
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
    <div class="pp-eyebrow">📄 Aviso legal</div>
    <h1 class="pp-title">Términos y Condiciones</h1>
    <p class="pp-meta">
      <strong>TaquerosWeb.com</strong> &nbsp;·&nbsp; Última actualización: <?= date('d \d\e F \d\e Y', mktime(0,0,0, (int)date('n'), (int)date('j'), (int)date('Y'))) ?>
    </p>
  </div>

  <!-- TOC -->
  <nav class="pp-toc" aria-label="Índice de contenido">
    <div class="pp-toc-title">Contenido</div>
    <ol>
      <li><a href="#s1">Objeto del servicio</a></li>
      <li><a href="#s2">Contratación y pagos</a></li>
      <li><a href="#s3">Entrega del proyecto</a></li>
      <li><a href="#s4">Responsabilidades del cliente</a></li>
      <li><a href="#s5">Limitación de responsabilidad</a></li>
      <li><a href="#s6">Disponibilidad y funcionamiento</a></li>
      <li><a href="#s7">Propiedad intelectual</a></li>
      <li><a href="#s8">Garantías y soporte</a></li>
      <li><a href="#s9">Cancelaciones y reembolsos</a></li>
      <li><a href="#s10">Legislación aplicable</a></li>
      <li><a href="#s11">Contacto</a></li>
    </ol>
  </nav>

  <!-- Intro -->
  <section class="pp-section">
    <p>
      Los presentes Términos y Condiciones (en adelante, los <strong>"Términos"</strong>) regulan la contratación y prestación de los servicios ofrecidos por <strong>TaquerosWeb</strong> (en adelante, el <strong>"Proveedor"</strong>) a cualquier persona física o moral que contrate dichos servicios (en adelante, el <strong>"Cliente"</strong>). Al realizar un pago, confirmar una orden o contratar cualquier servicio, el Cliente declara haber leído, entendido y aceptado en su totalidad estos Términos.
    </p>
  </section>

  <div class="pp-divider"></div>

  <!-- 1 Objeto del servicio -->
  <section class="pp-section" id="s1">
    <div class="pp-section-num">1</div>
    <h2>Objeto del servicio</h2>
    <p>El Proveedor se dedica a la prestación de servicios digitales, que de manera enunciativa más no limitativa incluyen:</p>
    <ul>
      <li><strong>Desarrollo de sitios web</strong> profesionales, landing pages, catálogos y tiendas en línea.</li>
      <li><strong>Diseño web</strong> e interfaces, así como la adaptación visual a la imagen del negocio del Cliente.</li>
      <li><strong>Mantenimiento y soporte</strong> técnico, conforme al alcance y vigencia que se contrate.</li>
      <li><strong>Servicios digitales relacionados</strong> como optimización, integraciones y asesoría.</li>
    </ul>
    <p>El alcance específico de cada proyecto será el correspondiente al paquete o servicio contratado por el Cliente. Cualquier funcionalidad, sección o entregable no descrito expresamente en el paquete contratado se considera fuera del alcance y estará sujeto a cotización adicional.</p>
  </section>

  <div class="pp-divider"></div>

  <!-- 2 Contratación y pagos -->
  <section class="pp-section" id="s2">
    <div class="pp-section-num">2</div>
    <h2>Contratación y pagos</h2>
    <p>La contratación de los servicios se formaliza una vez que el Cliente confirma su orden y realiza el pago correspondiente a través de los medios habilitados por el Proveedor.</p>
    <ul>
      <li><strong>Formas de pago aceptadas:</strong> pago con tarjeta de crédito o débito (procesado por nuestra plataforma de pagos) y transferencia electrónica (SPEI).</li>
      <li><strong>Confirmación de la contratación:</strong> el servicio se considera contratado y el Proveedor iniciará trabajos únicamente <strong>una vez recibido y confirmado el pago</strong>. En el caso de transferencias, la confirmación queda sujeta a la validación del depósito por parte del Proveedor.</li>
      <li>Los precios están expresados en pesos mexicanos (MXN) e incluyen únicamente lo descrito en el paquete contratado.</li>
    </ul>
    <div class="pp-box">
      <div class="pp-box-label">Consecuencias por falta de pago</div>
      <p>La falta de pago, el pago incompleto o el rechazo o contracargo del mismo facultan al Proveedor para <strong>suspender o no iniciar</strong> los trabajos, retener entregables, suspender la publicación o el funcionamiento del sitio y/o cancelar la orden, sin responsabilidad alguna para el Proveedor.</p>
    </div>
  </section>

  <div class="pp-divider"></div>

  <!-- 3 Entrega del proyecto -->
  <section class="pp-section" id="s3">
    <div class="pp-section-num">3</div>
    <h2>Entrega del proyecto</h2>
    <p>Los tiempos de entrega indicados en cada paquete son <strong>estimados</strong> y se expresan en días hábiles. Dichos plazos comienzan a contar a partir de que el Proveedor recibe el pago confirmado <strong>y</strong> la totalidad de la información y materiales necesarios por parte del Cliente.</p>
    <ul>
      <li><strong>Dependencia de la información del Cliente:</strong> los tiempos de entrega están sujetos a que el Cliente proporcione de manera oportuna textos, imágenes, logotipos, accesos y cualquier contenido requerido. Cualquier demora en la entrega de esta información extenderá proporcionalmente el plazo de entrega.</li>
      <li><strong>Cambios solicitados por el Cliente:</strong> las solicitudes de cambios, ajustes o contenido adicional durante el desarrollo podrán generar retrasos en la entrega y, en su caso, costos adicionales.</li>
      <li><strong>Aprobación y liberación:</strong> una vez entregada la versión final, el Cliente contará con un plazo razonable para revisarla y aprobarla. De no recibir observaciones dentro de dicho plazo, el proyecto se considerará <strong>aprobado y liberado</strong> de forma automática.</li>
    </ul>
  </section>

  <div class="pp-divider"></div>

  <!-- 4 Responsabilidades del cliente -->
  <section class="pp-section" id="s4">
    <div class="pp-section-num">4</div>
    <h2>Responsabilidades del cliente</h2>
    <p>Para la correcta prestación del servicio, el Cliente se obliga a:</p>
    <ul>
      <li>Proporcionar información <strong>veraz, completa y actualizada</strong>, siendo el único responsable de su exactitud y legalidad.</li>
      <li>Entregar el contenido, imágenes, textos, logotipos y accesos (hosting, dominio, redes sociales, etc.) necesarios para el desarrollo del proyecto.</li>
      <li>Contar con los derechos y licencias sobre todo el material que entregue, liberando al Proveedor de cualquier reclamación de terceros por su uso.</li>
      <li>Revisar y aprobar los avances en un tiempo razonable, así como mantener una comunicación oportuna durante el desarrollo.</li>
    </ul>
  </section>

  <div class="pp-divider"></div>

  <!-- 5 Limitación de responsabilidad -->
  <section class="pp-section" id="s5">
    <div class="pp-section-num">5</div>
    <h2>Limitación de responsabilidad</h2>
    <p>El Proveedor presta un servicio de desarrollo y diseño web de carácter técnico y creativo. En consecuencia, el Cliente reconoce y acepta expresamente que:</p>
    <ul>
      <li>El Proveedor <strong>no garantiza un número específico de ventas</strong> ni resultados económicos derivados del sitio web.</li>
      <li>El Proveedor <strong>no garantiza la generación de prospectos</strong> o clientes potenciales (leads).</li>
      <li>El Proveedor <strong>no garantiza posicionamiento en Google</strong> ni en ningún otro buscador, salvo que se contrate de forma expresa y por separado un servicio de posicionamiento (SEO).</li>
      <li>El Proveedor <strong>no garantiza incremento de ingresos, conversiones</strong> ni resultados comerciales de ningún tipo.</li>
      <li>El éxito comercial del Cliente depende de múltiples factores externos ajenos al control del Proveedor, tales como el mercado, la competencia, el producto o servicio del Cliente, su atención al público y sus estrategias comerciales.</li>
    </ul>
    <p>En la máxima medida permitida por la ley, la responsabilidad total del Proveedor frente al Cliente por cualquier concepto se limitará al monto efectivamente pagado por el servicio que dio origen a la reclamación. El Proveedor no será responsable por daños indirectos, incidentales, lucro cesante o pérdida de oportunidades de negocio.</p>
  </section>

  <div class="pp-divider"></div>

  <!-- 6 Disponibilidad y funcionamiento -->
  <section class="pp-section" id="s6">
    <div class="pp-section-num">6</div>
    <h2>Disponibilidad y funcionamiento</h2>
    <p>Aunque el Proveedor implementa buenas prácticas para mantener los sitios funcionando correctamente, <strong>no se garantiza una disponibilidad ininterrumpida</strong> del servicio ni del sitio web.</p>
    <p>El Cliente reconoce que pueden presentarse fallas, interrupciones o indisponibilidad derivadas de causas ajenas al Proveedor, entre ellas:</p>
    <ul>
      <li>Proveedores de <strong>hosting</strong>, servidores o servicios de alojamiento.</li>
      <li>Fallas de <strong>conexión a internet</strong>, configuración de <strong>DNS</strong> o registro de dominios.</li>
      <li><strong>Servicios de terceros</strong> integrados (pasarelas de pago, mapas, analítica, plugins, APIs, etc.).</li>
      <li>Casos fortuitos o de <strong>fuerza mayor</strong>.</li>
    </ul>
    <p>El Proveedor no será responsable por daños o perjuicios derivados de dichas causas.</p>
  </section>

  <div class="pp-divider"></div>

  <!-- 7 Propiedad intelectual -->
  <section class="pp-section" id="s7">
    <div class="pp-section-num">7</div>
    <h2>Propiedad intelectual</h2>
    <ul>
      <li>El <strong>Cliente conserva la propiedad</strong> de los contenidos que proporciona (textos, imágenes, logotipos y marcas de su titularidad).</li>
      <li>El <strong>Proveedor conserva la titularidad</strong> de sus metodologías, conocimientos, herramientas, código base, componentes reutilizables, librerías y desarrollos propios, los cuales no se transfieren al Cliente en ningún caso.</li>
      <li>La <strong>transferencia de derechos</strong> sobre el sitio web entregado a favor del Cliente operará únicamente una vez que el proyecto haya sido <strong>liquidado en su totalidad</strong>, y se limita al producto final entregado, excluyendo los elementos propios del Proveedor señalados en el punto anterior.</li>
      <li>El Proveedor se reserva el derecho de incluir el proyecto en su portafolio y de mostrar una referencia o crédito discreto, salvo pacto expreso en contrario.</li>
    </ul>
  </section>

  <div class="pp-divider"></div>

  <!-- 8 Garantías y soporte -->
  <section class="pp-section" id="s8">
    <div class="pp-section-num">8</div>
    <h2>Garantías y soporte</h2>
    <p>El Proveedor otorga una garantía limitada orientada a corregir <strong>fallas o errores técnicos imputables al desarrollo</strong> realizado, durante el periodo que se indique en el paquete contratado.</p>
    <ul>
      <li><strong>Incluido en la garantía:</strong> corrección de errores de funcionamiento, fallas de visualización o defectos atribuibles al trabajo del Proveedor sobre el alcance originalmente entregado.</li>
      <li><strong>No incluido (se consideran cambios adicionales sujetos a cotización):</strong> nuevas funcionalidades, secciones o páginas adicionales, rediseños, cambios de contenido posteriores a la liberación, integraciones nuevas, así como fallas derivadas de terceros, del hosting, de modificaciones realizadas por el Cliente o por personas distintas al Proveedor.</li>
    </ul>
    <p>El soporte y mantenimiento continuo, una vez vencida la garantía o fuera de su alcance, se prestará bajo el esquema y costo que las partes acuerden por separado.</p>
  </section>

  <div class="pp-divider"></div>

  <!-- 9 Cancelaciones y reembolsos -->
  <section class="pp-section" id="s9">
    <div class="pp-section-num">9</div>
    <h2>Cancelaciones y reembolsos</h2>
    <p>El Cliente podrá solicitar la cancelación de un proyecto mediante aviso por escrito al Proveedor. Las condiciones de reembolso dependerán del grado de avance del proyecto al momento de la solicitud:</p>
    <ul>
      <li>Una vez <strong>iniciados los trabajos</strong>, los montos pagados cubren el tiempo, recursos y trabajo ya invertidos, por lo que <strong>no son reembolsables</strong> en la proporción correspondiente al avance realizado.</li>
      <li>Los proyectos <strong>entregados, aprobados o liberados</strong> no son sujetos a reembolso.</li>
      <li>Los costos pagados a terceros (dominios, hosting, licencias, servicios externos) <strong>no son reembolsables</strong> bajo ninguna circunstancia.</li>
    </ul>
    <p>Cualquier reembolso procedente se calculará descontando el trabajo ya realizado y los gastos incurridos, y será determinado de forma razonable por el Proveedor.</p>
  </section>

  <div class="pp-divider"></div>

  <!-- 10 Legislación aplicable -->
  <section class="pp-section" id="s10">
    <div class="pp-section-num">10</div>
    <h2>Legislación aplicable</h2>
    <p>
      Los presentes Términos y la prestación de los servicios se rigen por las <strong>leyes vigentes de los Estados Unidos Mexicanos</strong>.
    </p>
    <p>
      Para la interpretación, cumplimiento y resolución de cualquier controversia derivada de estos Términos, las partes procurarán en primer lugar una <strong>solución amistosa</strong>. De no lograrse, se someterán a la jurisdicción de los <strong>tribunales competentes de los Estados Unidos Mexicanos</strong>, renunciando expresamente a cualquier otro fuero que pudiera corresponderles en razón de su domicilio presente o futuro.
    </p>
  </section>

  <div class="pp-divider"></div>

  <!-- 11 Contacto -->
  <section class="pp-section" id="s11">
    <div class="pp-section-num">11</div>
    <h2>Contacto</h2>
    <p>Si tienes preguntas o dudas sobre estos Términos y Condiciones, puedes comunicarte con nosotros:</p>
    <ul>
      <li><strong>Correo:</strong> <a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a></li>
      <li><strong>WhatsApp:</strong> <a href="https://wa.me/<?= $cfg['whatsapp'] ?>" target="_blank" rel="noopener noreferrer">Escribir por WhatsApp</a></li>
    </ul>
  </section>

  <!-- Contact CTA -->
  <div class="pp-contact-card">
    <div class="icon">📄</div>
    <h3>¿Listo para empezar tu proyecto?</h3>
    <p>Si tienes dudas sobre nuestros servicios o estos términos, con gusto te ayudamos.</p>
    <a href="mailto:<?= htmlspecialchars($email) ?>" class="btn-primary">📧 Contactar al equipo</a>
  </div>

</main>

<!-- ── Footer ── -->
<footer class="pp-footer">
  <div class="pp-footer-logo">
    <span>🌮</span> <span style="color:var(--color-orange)">Taqueros</span><span>Web</span><span style="color:var(--color-gold)">.com</span>
  </div>
  <p>© <?= $year ?> TaquerosWeb.com — Todos los derechos reservados &nbsp;·&nbsp; <a href="/">Inicio</a> &nbsp;·&nbsp; <a href="/politica-de-privacidad">Privacidad</a> &nbsp;·&nbsp; <a href="/terminos-y-condiciones">Términos</a></p>
</footer>

</body>
</html>
