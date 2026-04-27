<?php
declare(strict_types=1);
require_once __DIR__ . '/config/app.php';

use App\Core\Auth;
use App\Services\OrderService;
use App\Repositories\PaqueteRepository;

Auth::requireAuth('/login');
$user    = Auth::user();
$service = new OrderService();
$orders  = $service->getClientOrders((int)$user['id']);
$paquetes = (new PaqueteRepository())->findAll();

// Status labels & colors
$estadoConfig = [
    'borrador'       => ['label' => 'Borrador',       'color' => '#8A8278', 'icon' => '📝'],
    'pendiente_pago' => ['label' => 'Pendiente pago', 'color' => '#FFB800', 'icon' => '💳'],
    'pagado'         => ['label' => 'Pagado',         'color' => '#8BDA4F', 'icon' => '✅'],
    'en_proceso'     => ['label' => 'En proceso',     'color' => '#FF6B2B', 'icon' => '⚙️'],
    'revision'       => ['label' => 'En revisión',    'color' => '#9B59B6', 'icon' => '👀'],
    'entregado'      => ['label' => 'Entregado',      'color' => '#2ECC71', 'icon' => '🚀'],
    'cancelado'      => ['label' => 'Cancelado',      'color' => '#E8294C', 'icon' => '❌'],
];

$pagoStatus = $_GET['pago']   ?? null;
$pagoOrden  = (int)($_GET['orden'] ?? 0);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Mi Panel — TaquerosWeb</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<style>
:root{--color-bg:#0B0A08;--color-surface:#141210;--color-card:#1A1714;--color-border:rgba(255,255,255,.07);--color-orange:#FF6B2B;--color-red:#E8294C;--color-gold:#FFB800;--color-lime:#8BDA4F;--color-text:#F5F0E8;--color-muted:#8A8278;--grad-hero:linear-gradient(135deg,#FF6B2B,#E8294C,#7B1FA2);--font-display:'Bebas Neue',sans-serif;--font-body:'Manrope',sans-serif;--transition:all .3s cubic-bezier(.4,0,.2,1)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--font-body);background:var(--color-bg);color:var(--color-text);min-height:100vh}
a{color:inherit;text-decoration:none}

/* ── Topbar ───────────────────────────────── */
.topbar{background:rgba(11,10,8,.95);border-bottom:1px solid var(--color-border);backdrop-filter:blur(20px);padding:1rem 2rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100}
.topbar-logo{font-family:var(--font-display);font-size:1.6rem;display:flex;align-items:center;gap:.4rem}
.topbar-right{display:flex;align-items:center;gap:1rem}
.user-chip{display:flex;align-items:center;gap:.6rem;background:rgba(255,255,255,.04);border:1px solid var(--color-border);border-radius:100px;padding:.4rem 1rem .4rem .5rem;font-size:.82rem;font-weight:600}
.user-avatar{width:28px;height:28px;border-radius:50%;background:var(--grad-hero);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700}
.btn-logout{padding:.5rem 1rem;border-radius:8px;font-size:.8rem;font-weight:600;background:rgba(232,41,76,.1);border:1px solid rgba(232,41,76,.2);color:#f87093;cursor:pointer;transition:var(--transition)}
.btn-logout:hover{background:rgba(232,41,76,.2)}

/* ── Layout ───────────────────────────────── */
.page{max-width:1100px;margin:0 auto;padding:2.5rem 1.5rem}

.page-header{margin-bottom:2.5rem}
.page-title{font-family:var(--font-display);font-size:2.2rem;letter-spacing:1px;margin-bottom:.35rem}
.page-sub{color:var(--color-muted);font-size:.9rem}

/* ── Alert ────────────────────────────────── */
.alert{padding:.9rem 1.2rem;border-radius:12px;font-size:.85rem;margin-bottom:1.5rem;line-height:1.5}
.alert-success{background:rgba(139,218,79,.1);border:1px solid rgba(139,218,79,.25);color:var(--color-lime)}
.alert-error  {background:rgba(232,41,76,.1) ;border:1px solid rgba(232,41,76,.25) ;color:#f87093}
.alert-info   {background:rgba(255,184,0,.1) ;border:1px solid rgba(255,184,0,.2)  ;color:var(--color-gold)}
#globalAlert{display:none}

/* ── New order button ─────────────────────── */
.btn-new{display:inline-flex;align-items:center;gap:.5rem;padding:.8rem 1.5rem;border-radius:12px;font-size:.9rem;font-weight:700;background:var(--grad-hero);color:#fff;border:none;cursor:pointer;transition:var(--transition)}
.btn-new:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(255,107,43,.4)}

/* ── Empty state ──────────────────────────── */
.empty-state{text-align:center;padding:4rem 2rem;background:var(--color-card);border:1px dashed var(--color-border);border-radius:20px}
.empty-icon{font-size:3.5rem;margin-bottom:1rem}
.empty-title{font-family:var(--font-display);font-size:1.6rem;margin-bottom:.5rem}
.empty-sub{color:var(--color-muted);font-size:.9rem;margin-bottom:2rem}

/* ── Orders grid ──────────────────────────── */
.orders-grid{display:grid;gap:1.25rem}
.order-card{background:var(--color-card);border:1px solid var(--color-border);border-radius:16px;padding:1.75rem;transition:var(--transition);position:relative;overflow:hidden}
.order-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--grad-hero);opacity:0;transition:var(--transition)}
.order-card:hover::before{opacity:1}
.order-card:hover{border-color:rgba(255,107,43,.25);transform:translateY(-2px);box-shadow:0 16px 40px rgba(0,0,0,.4)}

.order-top{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1rem;flex-wrap:wrap}
.order-id{font-size:.75rem;color:var(--color-muted);font-weight:600;margin-bottom:.3rem}
.order-pkg{font-family:var(--font-display);font-size:1.25rem;letter-spacing:.5px;display:flex;align-items:center;gap:.5rem}
.status-badge{display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .85rem;border-radius:100px;font-size:.75rem;font-weight:700;border:1px solid}
.order-price{font-family:var(--font-display);font-size:1.6rem;color:var(--color-gold)}

.order-desc{font-size:.85rem;color:var(--color-muted);line-height:1.6;margin-bottom:1.25rem;padding:.75rem 1rem;background:rgba(255,255,255,.02);border-radius:8px;border:1px solid var(--color-border)}
.attachments{margin:-.4rem 0 1.25rem;display:flex;flex-direction:column;gap:.55rem}
.attachments-title{font-size:.75rem;color:var(--color-muted);font-weight:700;text-transform:uppercase;letter-spacing:.04em}
.attachment-list{display:flex;gap:.5rem;flex-wrap:wrap}
.attachment-link{max-width:220px;display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .7rem;border-radius:8px;background:rgba(255,255,255,.04);border:1px solid var(--color-border);font-size:.78rem;color:var(--color-text);transition:var(--transition)}
.attachment-link:hover{border-color:rgba(255,107,43,.35);color:var(--color-orange)}
.attachment-name{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.form-file{width:100%;background:rgba(255,255,255,.04);border:1px dashed rgba(255,255,255,.18);border-radius:10px;padding:.8rem 1rem;font-size:.85rem;color:var(--color-muted);font-family:var(--font-body)}
.form-help{font-size:.72rem;color:var(--color-muted);margin-top:.35rem;line-height:1.5}

.order-meta{display:flex;gap:1.5rem;flex-wrap:wrap;margin-bottom:1.25rem}
.meta-item{font-size:.78rem;color:var(--color-muted);display:flex;align-items:center;gap:.35rem}

.order-actions{display:flex;gap:.75rem;flex-wrap:wrap}
.btn-action{padding:.6rem 1.25rem;border-radius:10px;font-size:.82rem;font-weight:700;cursor:pointer;transition:var(--transition);border:none;display:inline-flex;align-items:center;gap:.4rem}
.btn-confirm{background:rgba(255,184,0,.15);color:var(--color-gold);border:1px solid rgba(255,184,0,.3)}
.btn-confirm:hover{background:rgba(255,184,0,.25)}
.btn-pay{background:var(--grad-hero);color:#fff}
.btn-pay:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(255,107,43,.4)}
.btn-disabled{background:rgba(255,255,255,.04);color:var(--color-muted);border:1px solid var(--color-border);cursor:default}

/* ── New order modal ──────────────────────── */
.modal-overlay{position:fixed;inset:0;z-index:2000;background:rgba(0,0,0,.9);backdrop-filter:blur(16px);display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .3s ease;padding:1rem}
.modal-overlay.open{opacity:1;pointer-events:all}
.modal-box{width:100%;max-width:560px;background:#1A1714;border:1px solid var(--color-border);border-radius:20px;padding:2.5rem;transform:scale(.94) translateY(16px);transition:transform .35s cubic-bezier(.34,1.56,.64,1);max-height:90vh;overflow-y:auto}
.modal-overlay.open .modal-box{transform:scale(1) translateY(0)}
.modal-title{font-family:var(--font-display);font-size:1.8rem;letter-spacing:1px;margin-bottom:.4rem}
.modal-sub{font-size:.85rem;color:var(--color-muted);margin-bottom:2rem}

.form-group{margin-bottom:1.2rem}
.form-label{display:block;font-size:.82rem;font-weight:600;color:var(--color-muted);margin-bottom:.45rem}
.form-input,.form-select,.form-textarea{width:100%;background:rgba(255,255,255,.04);border:1px solid var(--color-border);border-radius:10px;padding:.8rem 1rem;font-size:.9rem;color:var(--color-text);font-family:var(--font-body);transition:var(--transition);outline:none;appearance:none}
.form-input:focus,.form-select:focus,.form-textarea:focus{border-color:rgba(255,107,43,.5);background:rgba(255,107,43,.04);box-shadow:0 0 0 3px rgba(255,107,43,.1)}
.form-select option{background:#1A1714;color:var(--color-text)}
.form-textarea{resize:vertical;min-height:90px}
.field-error{font-size:.75rem;color:#f87093;margin-top:.3rem}
.btn-modal-submit{width:100%;padding:.95rem;border-radius:12px;font-size:.95rem;font-weight:800;background:var(--grad-hero);color:#fff;border:none;cursor:pointer;transition:var(--transition);letter-spacing:.02em}
.btn-modal-submit:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(255,107,43,.4)}
.btn-modal-submit:disabled{opacity:.6;transform:none;cursor:not-allowed}
.modal-close{position:absolute;top:1.25rem;right:1.25rem;width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.06);border:1px solid var(--color-border);color:var(--color-text);font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:var(--transition)}
.modal-close:hover{background:rgba(232,41,76,.2);border-color:rgba(232,41,76,.4);color:#f87093}

/* ── Spinner ──────────────────────────────── */
@keyframes spin{to{transform:rotate(360deg)}}
.spinner{display:inline-block;width:16px;height:16px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;vertical-align:middle;margin-right:.35rem}
</style>
</head>
<body>

<!-- Topbar -->
<header class="topbar">
  <a href="/" class="topbar-logo">
    <span>🌮</span>
    <span style="color:var(--color-orange)">Taqueros</span><span>Web</span>
  </a>
  <div class="topbar-right">
    <div class="user-chip">
      <div class="user-avatar"><?= strtoupper(mb_substr($user['nombre'], 0, 1)) ?></div>
      <?= htmlspecialchars($user['nombre'] . ' ' . $user['apellidos'], ENT_QUOTES) ?>
    </div>
    <button class="btn-logout" onclick="if(confirm('¿Cerrar sesión?')) window.location='/logout'">Cerrar sesión</button>
  </div>
</header>

<main class="page">

  <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem">
    <div>
      <h1 class="page-title">Mis Órdenes 🌮</h1>
      <p class="page-sub">Hola, <?= htmlspecialchars($user['nombre'], ENT_QUOTES) ?>. Aquí puedes ver y gestionar todas tus órdenes.</p>
    </div>
    <button class="btn-new" onclick="openNewOrderModal()">➕ Nueva orden</button>
  </div>

  <!-- Global alert -->
  <div id="globalAlert" class="alert"></div>

  <!-- Payment return alerts -->
  <?php if ($pagoStatus === 'aprobado'): ?>
    <div class="alert alert-success">🎉 ¡Pago aprobado! Tu orden #<?= $pagoOrden ?> está en proceso. Nuestro equipo te contactará en breve.</div>
  <?php elseif ($pagoStatus === 'error'): ?>
    <div class="alert alert-error">⚠️ El pago fue rechazado o cancelado. Intenta nuevamente o escríbenos por WhatsApp.</div>
  <?php elseif ($pagoStatus === 'pendiente'): ?>
    <div class="alert alert-info">⏳ Tu pago está pendiente de aprobación. Te notificaremos por correo cuando sea confirmado.</div>
  <?php endif; ?>

  <!-- Orders -->
  <?php if (empty($orders)): ?>
    <div class="empty-state">
      <div class="empty-icon">🌮</div>
      <div class="empty-title">Aún no tienes órdenes</div>
      <p class="empty-sub">Crea tu primera orden y ponemos en marcha tu sitio web.</p>
      <button class="btn-new" onclick="openNewOrderModal()">➕ Crear mi primera orden</button>
    </div>
  <?php else: ?>
    <div class="orders-grid">
      <?php foreach ($orders as $o):
        $est = $estadoConfig[$o['estado']] ?? ['label' => $o['estado'], 'color' => '#8A8278', 'icon' => '•'];
        $precio = number_format((float)$o['paquete_precio'], 2);
        $fecha  = date('d/m/Y', strtotime($o['created_at']));
      ?>
      <div class="order-card" id="order-<?= $o['id'] ?>">
        <div class="order-top">
          <div>
            <div class="order-id">Orden #<?= $o['id'] ?> · <?= $fecha ?></div>
            <div class="order-pkg"><?= htmlspecialchars($o['emoji'] . ' ' . $o['paquete_nombre'], ENT_QUOTES) ?></div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.5rem">
            <div class="status-badge" style="color:<?= $est['color'] ?>;background:<?= $est['color'] ?>1a;border-color:<?= $est['color'] ?>44">
              <?= $est['icon'] ?> <?= $est['label'] ?>
            </div>
            <div class="order-price">$<?= $precio ?> MXN</div>
          </div>
        </div>

        <div class="order-desc"><?= htmlspecialchars(html_entity_decode($o['descripcion'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES) ?></div>

        <?php if (!empty($o['adjuntos'])): ?>
          <div class="attachments">
            <div class="attachments-title">Adjuntos del proyecto</div>
            <div class="attachment-list">
              <?php foreach ($o['adjuntos'] as $adj): ?>
                <a class="attachment-link" href="<?= htmlspecialchars($adj['file_path'], ENT_QUOTES) ?>" target="_blank" rel="noopener">
                  <span>📎</span>
                  <span class="attachment-name"><?= htmlspecialchars($adj['original_name'], ENT_QUOTES) ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <div class="order-meta">
          <div class="meta-item">📅 Creada: <?= $fecha ?></div>
          <?php if ($o['updated_at']): ?>
            <div class="meta-item">🔄 Actualizada: <?= date('d/m/Y H:i', strtotime($o['updated_at'])) ?></div>
          <?php endif; ?>
        </div>

        <div class="order-actions">
          <button class="btn-action" style="background:rgba(255,107,43,.1);color:var(--color-orange);border:1px solid rgba(255,107,43,.22)" onclick="openEditOrderModal(<?= $o['id'] ?>)">
            ✏️ Editar detalles
          </button>

          <?php if ($o['estado'] === 'borrador'): ?>
            <button class="btn-action btn-confirm" onclick="confirmOrder(<?= $o['id'] ?>)">
              ✅ Confirmar orden
            </button>
          <?php elseif ($o['estado'] === 'pendiente_pago'): ?>
            <button class="btn-action btn-pay" onclick="payOrder(<?= $o['id'] ?>)">
              💳 Pagar ahora — $<?= $precio ?> MXN
            </button>
          <?php elseif ($o['estado'] === 'pagado'): ?>
            <span class="btn-action btn-disabled">✅ Pago recibido — en producción</span>
          <?php elseif ($o['estado'] === 'en_proceso'): ?>
            <span class="btn-action btn-disabled">⚙️ Equipo trabajando en tu sitio</span>
          <?php elseif ($o['estado'] === 'entregado'): ?>
            <span class="btn-action btn-disabled" style="color:var(--color-lime)">🚀 ¡Sitio entregado!</span>
          <?php endif; ?>

          <a href="https://wa.me/5215662866353?text=Hola%2C+tengo+una+pregunta+sobre+mi+orden+%23<?= $o['id'] ?>"
             target="_blank"
             class="btn-action" style="background:rgba(37,211,102,.1);color:#25D366;border:1px solid rgba(37,211,102,.2)">
            💬 WhatsApp
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</main>

<!-- ── New Order Modal ──────────────────────── -->
<div class="modal-overlay" id="newOrderModal">
  <div class="modal-box" style="position:relative">
    <button class="modal-close" onclick="closeNewOrderModal()">✕</button>
    <h2 class="modal-title">Nueva Orden 🌮</h2>
    <p class="modal-sub">Elige tu paquete y cuéntanos sobre tu proyecto.</p>

    <div id="modalAlert" style="display:none" class="alert"></div>

    <form id="newOrderForm" novalidate>
      <div class="form-group">
        <label class="form-label">Paquete *</label>
        <select id="mo_paquete" class="form-select" required>
          <option value="">— Selecciona un paquete —</option>
          <?php foreach ($paquetes as $p): ?>
            <option value="<?= $p['id'] ?>" data-precio="<?= $p['precio'] ?>">
              <?= htmlspecialchars($p['emoji'] . ' ' . $p['nombre'], ENT_QUOTES) ?> — $<?= number_format((float)$p['precio'], 0) ?> MXN
            </option>
          <?php endforeach; ?>
        </select>
        <div class="field-error" id="merr-paquete"></div>
      </div>

      <div class="form-group">
        <label class="form-label">Describe tu proyecto *</label>
        <textarea id="mo_desc" class="form-textarea" placeholder="¿A qué se dedica tu negocio? ¿Qué necesitas en el sitio? ¿Tienes logo y colores definidos?..." required></textarea>
        <div class="field-error" id="merr-desc"></div>
      </div>

      <div class="form-group">
        <label class="form-label">Adjuntos</label>
        <input id="mo_adjuntos" class="form-file" type="file" multiple accept="image/*,.pdf,.txt,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
        <div class="form-help">Puedes subir imágenes, PDF, documentos, TXT o ZIP. Máximo 10 MB por archivo.</div>
      </div>

      <button type="submit" class="btn-modal-submit" id="newOrderBtn">🛒 Crear orden</button>
    </form>
  </div>
</div>

<!-- Edit Order Modal -->
<div class="modal-overlay" id="editOrderModal">
  <div class="modal-box" style="position:relative">
    <button class="modal-close" onclick="closeEditOrderModal()">✕</button>
    <h2 class="modal-title">Detalles del proyecto</h2>
    <p class="modal-sub">Actualiza la descripción y agrega archivos para que el equipo tenga todo el contexto.</p>

    <div id="editModalAlert" style="display:none" class="alert"></div>

    <form id="editOrderForm" novalidate>
      <input type="hidden" id="eo_id">

      <div class="form-group">
        <label class="form-label">Descripción de la orden *</label>
        <textarea id="eo_desc" class="form-textarea" required></textarea>
        <div class="field-error" id="eerr-desc"></div>
      </div>

      <div class="form-group">
        <label class="form-label">Adjuntos actuales</label>
        <div id="eo_attachments" class="attachment-list"></div>
        <div class="form-help" id="eo_no_attachments">Esta orden todavía no tiene adjuntos.</div>
      </div>

      <div class="form-group">
        <label class="form-label">Agregar adjuntos</label>
        <input id="eo_adjuntos" class="form-file" type="file" multiple accept="image/*,.pdf,.txt,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
        <div class="form-help">Puedes agregar imágenes, referencias, textos, PDFs, documentos o archivos comprimidos.</div>
      </div>

      <button type="submit" class="btn-modal-submit" id="editOrderBtn">Guardar detalles</button>
    </form>
  </div>
</div>

<script>
const ORDER_DETAILS = <?= json_encode(array_column(array_map(static function (array $order): array {
  return [
    'id' => (int)$order['id'],
    'descripcion' => html_entity_decode((string)$order['descripcion'], ENT_QUOTES, 'UTF-8'),
    'adjuntos' => $order['adjuntos'] ?? [],
  ];
}, $orders), null, 'id'), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

// ── New order modal ───────────────────────────────────────────
function openNewOrderModal() {
  document.getElementById('newOrderModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeNewOrderModal() {
  document.getElementById('newOrderModal').classList.remove('open');
  document.body.style.overflow = '';
  document.getElementById('newOrderForm').reset();
  document.getElementById('modalAlert').style.display = 'none';
  document.querySelectorAll('.field-error').forEach(e => e.textContent = '');
}
document.getElementById('newOrderModal').addEventListener('click', e => {
  if (e.target === document.getElementById('newOrderModal')) closeNewOrderModal();
});

document.getElementById('newOrderForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const alertEl  = document.getElementById('modalAlert');
  const paqueteId = document.getElementById('mo_paquete').value;
  const desc      = document.getElementById('mo_desc').value.trim();
  let valid = true;

  alertEl.style.display = 'none';
  ['paquete','desc'].forEach(f => document.getElementById('merr-'+f).textContent = '');

  if (!paqueteId) { document.getElementById('merr-paquete').textContent = 'Selecciona un paquete.'; valid = false; }
  if (!desc)      { document.getElementById('merr-desc').textContent = 'Describe tu proyecto.'; valid = false; }
  if (!valid) return;

  const btn = document.getElementById('newOrderBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span> Creando...';

  try {
    const formData = new FormData();
    formData.append('paquete_id', paqueteId);
    formData.append('descripcion', desc);
    Array.from(document.getElementById('mo_adjuntos').files).forEach(file => {
      formData.append('adjuntos[]', file);
    });

    const res  = await fetch('/api/orders', {
      method: 'POST',
      body: formData
    });
    const data = await res.json();
    if (data.success) {
      closeNewOrderModal();
      showGlobalAlert('✅ Orden creada. Confírmala para avanzar al pago.', 'success');
      setTimeout(() => location.reload(), 1500);
    } else {
      alertEl.className = 'alert alert-error';
      alertEl.textContent = data.message || 'Error al crear la orden.';
      alertEl.style.display = 'block';
    }
  } catch {
    alertEl.className = 'alert alert-error';
    alertEl.textContent = 'Error de conexión.';
    alertEl.style.display = 'block';
  } finally {
    btn.disabled = false;
    btn.textContent = '🛒 Crear orden';
  }
});

// ── Edit order details ─────────────────────────────────────────
function openEditOrderModal(id) {
  const order = ORDER_DETAILS[id];
  if (!order) return;

  document.getElementById('eo_id').value = id;
  document.getElementById('eo_desc').value = order.descripcion || '';
  document.getElementById('eo_adjuntos').value = '';
  document.getElementById('editModalAlert').style.display = 'none';
  document.getElementById('eerr-desc').textContent = '';
  renderEditAttachments(order.adjuntos || []);

  document.getElementById('editOrderModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeEditOrderModal() {
  document.getElementById('editOrderModal').classList.remove('open');
  document.body.style.overflow = '';
  document.getElementById('editOrderForm').reset();
}

document.getElementById('editOrderModal').addEventListener('click', e => {
  if (e.target === document.getElementById('editOrderModal')) closeEditOrderModal();
});

function renderEditAttachments(attachments) {
  const list = document.getElementById('eo_attachments');
  const empty = document.getElementById('eo_no_attachments');
  list.innerHTML = '';
  empty.style.display = attachments.length ? 'none' : 'block';

  attachments.forEach(attachment => {
    const link = document.createElement('a');
    link.className = 'attachment-link';
    link.href = attachment.file_path;
    link.target = '_blank';
    link.rel = 'noopener';
    link.innerHTML = `<span>📎</span><span class="attachment-name"></span>`;
    link.querySelector('.attachment-name').textContent = attachment.original_name;
    list.appendChild(link);
  });
}

document.getElementById('editOrderForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const id = document.getElementById('eo_id').value;
  const desc = document.getElementById('eo_desc').value.trim();
  const alertEl = document.getElementById('editModalAlert');
  const btn = document.getElementById('editOrderBtn');

  alertEl.style.display = 'none';
  document.getElementById('eerr-desc').textContent = '';
  if (!desc) {
    document.getElementById('eerr-desc').textContent = 'Describe tu proyecto.';
    return;
  }

  const formData = new FormData();
  formData.append('descripcion', desc);
  Array.from(document.getElementById('eo_adjuntos').files).forEach(file => {
    formData.append('adjuntos[]', file);
  });

  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span> Guardando...';

  try {
    const res = await fetch(`/api/orders/${id}/details`, { method: 'POST', body: formData });
    const data = await res.json();
    if (data.success) {
      closeEditOrderModal();
      showGlobalAlert('✅ Detalles de la orden actualizados.', 'success');
      setTimeout(() => location.reload(), 1000);
    } else {
      alertEl.className = 'alert alert-error';
      alertEl.textContent = data.message || 'Error al actualizar la orden.';
      alertEl.style.display = 'block';
    }
  } catch {
    alertEl.className = 'alert alert-error';
    alertEl.textContent = 'Error de conexión.';
    alertEl.style.display = 'block';
  } finally {
    btn.disabled = false;
    btn.textContent = 'Guardar detalles';
  }
});

// ── Confirm order ─────────────────────────────────────────────
async function confirmOrder(id) {
  if (!confirm('¿Confirmar la orden #' + id + '? Pasará a "Pendiente de pago".')) return;
  try {
    const res  = await fetch(`/api/orders/${id}/confirm`, { method: 'POST' });
    const data = await res.json();
    if (data.success) {
      showGlobalAlert('✅ Orden confirmada. Ahora puedes proceder al pago.', 'success');
      setTimeout(() => location.reload(), 1200);
    } else {
      showGlobalAlert(data.message, 'error');
    }
  } catch { showGlobalAlert('Error de conexión.', 'error'); }
}

// ── Pay order ─────────────────────────────────────────────────
async function payOrder(id) {
  const btn = document.querySelector(`#order-${id} .btn-pay`);
  if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner"></span> Preparando pago...'; }

  try {
    const res  = await fetch(`/api/orders/${id}/pay`, { method: 'POST' });
    const data = await res.json();
    if (data.success && data.data?.init_point) {
      window.location.href = data.data.init_point;
    } else {
      showGlobalAlert(data.message || 'Error al iniciar el pago.', 'error');
      if (btn) { btn.disabled = false; btn.textContent = '💳 Pagar ahora'; }
    }
  } catch {
    showGlobalAlert('Error de conexión.', 'error');
    if (btn) { btn.disabled = false; btn.textContent = '💳 Pagar ahora'; }
  }
}

// ── Global alert ──────────────────────────────────────────────
function showGlobalAlert(msg, type = 'info') {
  const el = document.getElementById('globalAlert');
  el.className = `alert alert-${type}`;
  el.textContent = msg;
  el.style.display = 'block';
  el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  setTimeout(() => el.style.display = 'none', 5000);
}
</script>
</body>
</html>
