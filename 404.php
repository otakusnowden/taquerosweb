<?php
declare(strict_types=1);
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>404 — Página no encontrada · TaquerosWeb.com</title>
<meta name="description" content="La página que buscas no existe o fue movida. Regresa al inicio de TaquerosWeb." />
<meta name="robots" content="noindex, follow" />
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<style>
:root {
  --color-bg:     #0B0A08;
  --color-card:   #1A1714;
  --color-border: rgba(255,255,255,0.07);
  --color-orange: #FF6B2B;
  --color-gold:   #FFB800;
  --color-text:   #F5F0E8;
  --color-muted:  #8A8278;
  --color-lime:   #8BDA4F;
  --grad-hero:    linear-gradient(135deg, #FF6B2B 0%, #E8294C 50%, #7B1FA2 100%);
  --font-display: 'Bebas Neue', sans-serif;
  --font-body:    'Manrope', sans-serif;
  --radius-btn:   12px;
  --transition:   all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  --shadow-glow:  0 0 40px rgba(255,107,43,0.25);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  font-family: var(--font-body);
  background: var(--color-bg);
  color: var(--color-text);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  position: relative;
}
a { color: inherit; text-decoration: none; }

/* ── Noise texture ── */
body::before {
  content: '';
  position: fixed; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
  pointer-events: none; z-index: 0; opacity: 0.5;
}

/* ── Radial glow BG ── */
body::after {
  content: '';
  position: fixed;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  width: 700px; height: 700px;
  background: radial-gradient(circle, rgba(255,107,43,0.07) 0%, transparent 70%);
  pointer-events: none; z-index: 0;
}

/* ── Grid lines ── */
.grid-bg {
  position: fixed; inset: 0; z-index: 0; pointer-events: none;
  background-image:
    linear-gradient(rgba(255,107,43,0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,107,43,0.03) 1px, transparent 1px);
  background-size: 60px 60px;
  mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);
}

/* ── Navbar ── */
.navbar {
  position: fixed; top: 0; left: 0; right: 0; z-index: 100;
  padding: 1rem 2rem;
  display: flex; align-items: center; justify-content: space-between;
  background: rgba(11,10,8,0.85); backdrop-filter: blur(20px);
  border-bottom: 1px solid var(--color-border);
}
.navbar-logo {
  font-family: var(--font-display); font-size: 1.8rem; letter-spacing: 1px;
  display: flex; align-items: center; gap: 0.4rem;
}
.logo-t { color: var(--color-orange); }
.logo-w { color: var(--color-text); }
.logo-m { color: var(--color-gold); font-size: 1.1rem; vertical-align: super; }

/* ── Main card ── */
.err-wrap {
  position: relative; z-index: 1;
  display: flex; flex-direction: column; align-items: center;
  text-align: center;
  padding: 2rem 1.5rem;
  max-width: 560px;
  animation: fadeInUp 0.7s ease both;
}

/* ── Big 404 number ── */
.err-number {
  font-family: var(--font-display);
  font-size: clamp(7rem, 22vw, 13rem);
  line-height: 0.9;
  letter-spacing: 4px;
  background: var(--grad-hero);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 0.5rem;
  position: relative;
  animation: float 4s ease-in-out infinite;
  filter: drop-shadow(0 0 40px rgba(255,107,43,0.3));
}

/* ── Floating taco ── */
.err-taco {
  font-size: clamp(2.5rem, 8vw, 4rem);
  position: absolute;
  top: 0.5rem; right: -1.5rem;
  animation: spinWobble 6s ease-in-out infinite;
  filter: drop-shadow(0 4px 12px rgba(0,0,0,0.5));
  user-select: none;
}

/* ── Badge ── */
.err-badge {
  display: inline-flex; align-items: center; gap: 0.5rem;
  background: rgba(255,107,43,0.1); border: 1px solid rgba(255,107,43,0.2);
  border-radius: 100px; padding: 0.35rem 1rem;
  font-size: 0.72rem; font-weight: 700; color: var(--color-orange);
  letter-spacing: 0.1em; text-transform: uppercase;
  margin-bottom: 1.25rem;
}

/* ── Headline ── */
.err-title {
  font-family: var(--font-display);
  font-size: clamp(1.6rem, 5vw, 2.4rem);
  letter-spacing: 1px;
  line-height: 1.1;
  margin-bottom: 1rem;
}

.err-sub {
  font-size: 1rem;
  color: var(--color-muted);
  line-height: 1.7;
  margin-bottom: 2rem;
  max-width: 420px;
}

/* ── CTA Button ── */
.btn-home {
  display: inline-flex; align-items: center; gap: 0.6rem;
  padding: 0.9rem 2rem; border-radius: var(--radius-btn);
  font-size: 1rem; font-weight: 700; font-family: var(--font-body);
  background: var(--grad-hero); color: #fff;
  transition: var(--transition); position: relative; overflow: hidden;
  margin-bottom: 1.25rem;
}
.btn-home::before { content: ''; position: absolute; inset: 0; background: rgba(255,255,255,0); transition: var(--transition); }
.btn-home:hover { transform: translateY(-2px); box-shadow: 0 16px 48px rgba(255,107,43,0.4); }
.btn-home:hover::before { background: rgba(255,255,255,0.08); }
.btn-home:focus-visible { outline: 2px solid var(--color-orange); outline-offset: 3px; }

.err-links { display: flex; align-items: center; gap: 1.5rem; font-size: 0.85rem; color: var(--color-muted); }
.err-links a { color: var(--color-muted); transition: var(--transition); }
.err-links a:hover { color: var(--color-orange); }
.err-links span { color: var(--color-border); }

/* ── Decorative chips floating behind ── */
.deco-chip {
  position: fixed;
  background: var(--color-card);
  border: 1px solid var(--color-border);
  border-radius: 10px;
  padding: 0.5rem 0.9rem;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--color-muted);
  pointer-events: none;
  z-index: 0;
  opacity: 0;
  animation: chipFloat 8s ease-in-out infinite;
}
.deco-chip:nth-child(1) { top: 20%; left: 8%;  animation-delay: 0s;   animation-duration: 9s; }
.deco-chip:nth-child(2) { top: 35%; right: 6%; animation-delay: 2s;   animation-duration: 11s; }
.deco-chip:nth-child(3) { bottom: 25%; left: 10%; animation-delay: 1s; animation-duration: 10s; }
.deco-chip:nth-child(4) { bottom: 30%; right: 8%; animation-delay: 3s; animation-duration: 8s; }

/* ── Animations ── */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(28px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes float {
  0%, 100% { transform: translateY(0); }
  50%       { transform: translateY(-12px); }
}
@keyframes spinWobble {
  0%, 100% { transform: rotate(-8deg) translateY(0); }
  25%       { transform: rotate(12deg) translateY(-6px); }
  50%       { transform: rotate(-6deg) translateY(-2px); }
  75%       { transform: rotate(10deg) translateY(-8px); }
}
@keyframes chipFloat {
  0%   { opacity: 0; transform: translateY(0); }
  15%  { opacity: 1; }
  85%  { opacity: 1; }
  100% { opacity: 0; transform: translateY(-20px); }
}

@media (max-width: 480px) {
  .err-taco { right: -0.5rem; font-size: 2rem; }
  .err-links { flex-direction: column; gap: 0.75rem; }
  .err-links span { display: none; }
  .deco-chip { display: none; }
}
</style>
</head>
<body>

<div class="grid-bg" aria-hidden="true"></div>

<!-- Floating ambient chips -->
<div class="deco-chip" aria-hidden="true">🌮 Página no encontrada</div>
<div class="deco-chip" aria-hidden="true">⚡ Error 404</div>
<div class="deco-chip" aria-hidden="true">🔍 Ruta inválida</div>
<div class="deco-chip" aria-hidden="true">🛠️ Desarrollado con amor</div>

<!-- Navbar -->
<nav class="navbar" aria-label="Navegación">
  <a href="/" class="navbar-logo" aria-label="TaquerosWeb — Ir al inicio">
    <span>🌮</span>
    <span class="logo-t">Taqueros</span><span class="logo-w">Web</span><span class="logo-m">.com</span>
  </a>
</nav>

<!-- Main content -->
<main class="err-wrap" id="main-content">

  <div class="err-badge">
    <span>🌮</span> Error 404
  </div>

  <div style="position:relative; display:inline-block;">
    <div class="err-number" aria-label="Error 404">404</div>
    <span class="err-taco" aria-hidden="true">🌮</span>
  </div>

  <h1 class="err-title">Esta página se fue a comer tacos</h1>

  <p class="err-sub">
    Ups… parece que esta dirección no existe o fue movida. No te preocupes, esto le pasa hasta al mejor taquero.
  </p>

  <a href="/" class="btn-home">
    🏠 Volver al inicio
  </a>

  <div class="err-links">
    <a href="/#Paquetes Web">Ver paquetes</a>
    <span aria-hidden="true">·</span>
    <a href="/#contacto">Contacto</a>
    <span aria-hidden="true">·</span>
    <a href="https://wa.me/5215662866353?text=Hola%2C%20encontr%C3%A9%20una%20p%C3%A1gina%20rota" target="_blank" rel="noopener noreferrer">Reportar</a>
  </div>

</main>

</body>
</html>
