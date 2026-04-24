<?php
declare(strict_types=1);
require_once __DIR__ . '/config/app.php';

use App\Core\Auth;
Auth::requireGuest('/dashboard');

$verified = isset($_GET['verified']);
$loggedOut = isset($_GET['logout']);
$errorMsg  = htmlspecialchars($_GET['error'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Iniciar sesión — TaquerosWeb</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<style>
:root{--color-bg:#0B0A08;--color-surface:#141210;--color-card:#1A1714;--color-border:rgba(255,255,255,0.07);--color-orange:#FF6B2B;--color-red:#E8294C;--color-gold:#FFB800;--color-lime:#8BDA4F;--color-text:#F5F0E8;--color-muted:#8A8278;--grad-hero:linear-gradient(135deg,#FF6B2B 0%,#E8294C 50%,#7B1FA2 100%);--font-display:'Bebas Neue',sans-serif;--font-body:'Manrope',sans-serif;--radius:16px;--transition:all .3s cubic-bezier(.4,0,.2,1)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--font-body);background:var(--color-bg);color:var(--color-text);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem 1rem;position:relative;overflow:hidden}
body::before{content:'';position:fixed;inset:0;background-image:linear-gradient(rgba(255,107,43,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,107,43,.04) 1px,transparent 1px);background-size:60px 60px;mask-image:radial-gradient(ellipse 80% 80% at 50% 50%,#000 40%,transparent 100%);pointer-events:none}
body::after{content:'';position:fixed;top:-20%;left:50%;transform:translateX(-50%);width:600px;height:600px;background:radial-gradient(circle,rgba(255,107,43,.1) 0%,transparent 70%);pointer-events:none;z-index:0}

.logo-link{display:flex;align-items:center;gap:.5rem;font-family:var(--font-display);font-size:2rem;letter-spacing:1px;text-decoration:none;margin-bottom:2rem;position:relative;z-index:1}
.logo-link span:nth-child(2){color:var(--color-orange)}
.logo-link span:nth-child(4){color:var(--color-gold);font-size:1.1rem;vertical-align:super}

.card{width:100%;max-width:440px;background:var(--color-card);border:1px solid var(--color-border);border-radius:24px;padding:2.5rem;position:relative;z-index:1;box-shadow:0 32px 80px rgba(0,0,0,.6)}

.card-title{font-family:var(--font-display);font-size:2rem;letter-spacing:1px;margin-bottom:.4rem}
.card-sub{font-size:.875rem;color:var(--color-muted);margin-bottom:2rem}

.alert{padding:.85rem 1.1rem;border-radius:10px;font-size:.83rem;margin-bottom:1.5rem;line-height:1.5}
.alert-success{background:rgba(139,218,79,.1);border:1px solid rgba(139,218,79,.25);color:var(--color-lime)}
.alert-error{background:rgba(232,41,76,.1);border:1px solid rgba(232,41,76,.25);color:#f87093}
.alert-info{background:rgba(255,184,0,.1);border:1px solid rgba(255,184,0,.2);color:var(--color-gold)}

.form-group{margin-bottom:1.2rem}
.form-label{display:block;font-size:.82rem;font-weight:600;color:var(--color-muted);margin-bottom:.45rem}
.form-input{width:100%;background:rgba(255,255,255,.04);border:1px solid var(--color-border);border-radius:10px;padding:.8rem 1rem;font-size:.9rem;color:var(--color-text);font-family:var(--font-body);transition:var(--transition);outline:none}
.form-input:focus{border-color:rgba(255,107,43,.5);background:rgba(255,107,43,.04);box-shadow:0 0 0 3px rgba(255,107,43,.1)}
.form-input::placeholder{color:rgba(138,130,120,.5)}
.input-error{border-color:var(--color-red)!important;box-shadow:0 0 0 3px rgba(232,41,76,.15)!important}
.field-error{font-size:.76rem;color:#f87093;margin-top:.3rem}

.btn-submit{width:100%;padding:1rem;border-radius:12px;font-size:1rem;font-weight:800;background:var(--grad-hero);color:#fff;border:none;cursor:pointer;transition:var(--transition);letter-spacing:.02em;position:relative;overflow:hidden}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 16px 48px rgba(255,107,43,.4)}
.btn-submit:disabled{opacity:.6;cursor:not-allowed;transform:none}

.divider{display:flex;align-items:center;gap:1rem;margin:1.5rem 0;color:var(--color-muted);font-size:.8rem}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--color-border)}

.link{color:var(--color-orange);text-decoration:none;font-weight:600;transition:var(--transition)}
.link:hover{color:#ff8c5a}
.text-center{text-align:center;font-size:.85rem;color:var(--color-muted)}

@keyframes spin{to{transform:rotate(360deg)}}
.spinner{display:inline-block;width:18px;height:18px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;vertical-align:middle;margin-right:.4rem}
</style>
</head>
<body>
<a href="/" class="logo-link">
  <span>🌮</span><span>Taqueros</span><span style="color:var(--color-text)">Web</span><span>.com</span>
</a>

<div class="card">
  <h1 class="card-title">Bienvenido 👋</h1>
  <p class="card-sub">Inicia sesión para gestionar tus órdenes y pagos.</p>

  <?php if ($verified): ?>
    <div class="alert alert-success">✅ ¡Correo verificado! Ya puedes iniciar sesión.</div>
  <?php elseif ($loggedOut): ?>
    <div class="alert alert-info">👋 Sesión cerrada correctamente.</div>
  <?php elseif ($errorMsg): ?>
    <div class="alert alert-error">⚠️ <?= $errorMsg ?></div>
  <?php endif; ?>

  <div id="loginAlert" style="display:none" class="alert"></div>

  <form id="loginForm" novalidate>
    <div class="form-group">
      <label class="form-label" for="email">Correo electrónico</label>
      <input type="email" id="email" name="email" class="form-input" placeholder="tu@correo.com" autocomplete="email" required/>
      <div class="field-error" id="err-email"></div>
    </div>
    <div class="form-group">
      <label class="form-label" for="password">Contraseña</label>
      <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" autocomplete="current-password" required/>
      <div class="field-error" id="err-password"></div>
    </div>
    <button type="submit" class="btn-submit" id="submitBtn">🌮 Iniciar sesión</button>
  </form>

  <div class="divider">o</div>
  <p class="text-center">¿Aún no tienes cuenta? <a href="/#contacto" class="link">Compra un paquete</a> y recibe tu acceso.</p>
</div>

<script>
const form = document.getElementById('loginForm');
const submitBtn = document.getElementById('submitBtn');
const alertBox = document.getElementById('loginAlert');

function showAlert(msg, type = 'error') {
  alertBox.className = `alert alert-${type}`;
  alertBox.textContent = msg;
  alertBox.style.display = 'block';
  alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  // Clear
  alertBox.style.display = 'none';
  document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
  document.querySelectorAll('.form-input').forEach(el => el.classList.remove('input-error'));

  const email    = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  let valid = true;

  if (!email) { setFieldError('email', 'El correo es requerido.'); valid = false; }
  if (!password) { setFieldError('password', 'La contraseña es requerida.'); valid = false; }
  if (!valid) return;

  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner"></span> Ingresando...';

  try {
    const res  = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    const data = await res.json();

    if (data.success) {
      showAlert('✅ ' + data.message, 'success');
      setTimeout(() => window.location.href = data.data?.redirect || '/dashboard', 800);
    } else {
      showAlert(data.message || 'Error al iniciar sesión.');
    }
  } catch {
    showAlert('Error de conexión. Intenta de nuevo.');
  } finally {
    submitBtn.disabled = false;
    submitBtn.textContent = '🌮 Iniciar sesión';
  }
});

function setFieldError(field, msg) {
  document.getElementById(field).classList.add('input-error');
  document.getElementById('err-' + field).textContent = msg;
}
</script>
</body>
</html>
