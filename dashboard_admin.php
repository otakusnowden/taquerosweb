<?php
declare(strict_types=1);
require_once __DIR__ . '/config/app.php';

use App\Repositories\OrdenRepository;
use App\Repositories\OrdenAdjuntoRepository;
use App\Repositories\PaqueteRepository;
use App\Repositories\ClienteRepository;

$ordenesRepo = new OrdenRepository();
$adjuntosRepo = new OrdenAdjuntoRepository();
$paquetes = (new PaqueteRepository())->findAll();
$clientes = (new ClienteRepository())->findAll();

$adminError = '';
$adminNotice = '';
$estados = ['borrador', 'pendiente_pago', 'pagado', 'en_proceso', 'revision', 'entregado', 'cancelado'];

function adminIsLoggedIn(): bool
{
    return isset($_SESSION['tw_admin']) && ($_SESSION['tw_admin']['loginAt'] ?? 0) > time() - (int)($_ENV['SESSION_LIFETIME'] ?? 7200);
}

function adminLogin(string $user): void
{
    session_regenerate_id(true);
    $_SESSION['tw_admin'] = [
        'user' => $user,
        'loginAt' => time(),
    ];
}

function adminLogout(): void
{
    unset($_SESSION['tw_admin'], $_SESSION['tw_admin_csrf']);
}

function adminCsrfToken(): string
{
    if (empty($_SESSION['tw_admin_csrf'])) {
        $_SESSION['tw_admin_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['tw_admin_csrf'];
}

function adminPasswordMatches(string $password): bool
{
    $hash = (string)($_ENV['ADMIN_PASSWORD_HASH'] ?? '');
    if ($hash !== '') {
        return password_verify($password, $hash);
    }

    $plain = (string)($_ENV['ADMIN_PASSWORD'] ?? '');
    return $plain !== '' && hash_equals($plain, $password);
}

function cleanDescription(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

if (($_GET['action'] ?? '') === 'logout') {
    adminLogout();
    header('Location: /dashboard_admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $user = trim((string)($_POST['user'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $envUser = (string)($_ENV['ADMIN_USER'] ?? '');

    if ($envUser === '' || (($_ENV['ADMIN_PASSWORD'] ?? '') === '' && ($_ENV['ADMIN_PASSWORD_HASH'] ?? '') === '')) {
        $adminError = 'Configura ADMIN_USER y ADMIN_PASSWORD o ADMIN_PASSWORD_HASH en .env.';
    } elseif (!hash_equals($envUser, $user) || !adminPasswordMatches($password)) {
        $adminError = 'Usuario o contraseña incorrectos.';
    } else {
        adminLogin($user);
        header('Location: /dashboard_admin.php');
        exit;
    }
}

if (adminIsLoggedIn() && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_order') {
    if (!hash_equals(adminCsrfToken(), (string)($_POST['csrf'] ?? ''))) {
        $adminError = 'La sesión expiró. Intenta de nuevo.';
    } else {
        $ordenId = (int)($_POST['orden_id'] ?? 0);
        $paqueteId = (int)($_POST['paquete_id'] ?? 0);
        $clienteId = (int)($_POST['cliente_id'] ?? 0);
        $estado = (string)($_POST['estado'] ?? '');
        $descripcion = cleanDescription((string)($_POST['descripcion'] ?? ''));
        $mpPreference = trim((string)($_POST['mp_preference_id'] ?? ''));
        $createdAtInput = trim((string)($_POST['created_at'] ?? ''));
        $createdAt = str_replace('T', ' ', $createdAtInput);

        if ($ordenId <= 0 || $paqueteId <= 0 || $clienteId <= 0 || $descripcion === '' || !in_array($estado, $estados, true) || $createdAt === '') {
            $adminError = 'Completa todos los campos requeridos de la orden.';
        } else {
            try {
                $ordenesRepo->updateAdminFields($ordenId, [
                    'paquete_id' => $paqueteId,
                    'cliente_id' => $clienteId,
                    'descripcion' => $descripcion,
                    'estado' => $estado,
                    'mp_preference_id' => $mpPreference,
                    'created_at' => $createdAt,
                ]);
                $adminNotice = "Orden #{$ordenId} actualizada.";
            } catch (Throwable $e) {
                $adminError = 'No se pudo actualizar la orden.';
            }
        }
    }
}

$orders = adminIsLoggedIn() ? $ordenesRepo->findAllForAdmin() : [];
$attachmentsByOrder = [];
if (!empty($orders)) {
    foreach ($adjuntosRepo->findByOrdenIds(array_column($orders, 'id')) as $attachment) {
        $attachmentsByOrder[(int)$attachment['orden_id']][] = $attachment;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Admin Ordenes - TaquerosWeb</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<style>
:root{--bg:#0B0A08;--card:#1A1714;--line:rgba(255,255,255,.08);--text:#F5F0E8;--muted:#8A8278;--orange:#FF6B2B;--red:#E8294C;--gold:#FFB800;--lime:#8BDA4F;--font-display:'Bebas Neue',sans-serif;--font-body:'Manrope',sans-serif}
*{box-sizing:border-box;margin:0;padding:0}
body{min-height:100vh;background:var(--bg);color:var(--text);font-family:var(--font-body)}
a{color:inherit;text-decoration:none}
.login-wrap{min-height:100vh;display:grid;place-items:center;padding:1.5rem}
.login-card{width:100%;max-width:430px;background:var(--card);border:1px solid var(--line);border-radius:20px;padding:2rem;box-shadow:0 28px 70px rgba(0,0,0,.55)}
.brand,.title{font-family:var(--font-display);letter-spacing:.5px}
.brand{font-size:1.8rem;margin-bottom:1.5rem}.brand span{color:var(--orange)}
.title{font-size:2rem;margin-bottom:.35rem}.sub{color:var(--muted);font-size:.9rem;margin-bottom:1.5rem}
.field{margin-bottom:1rem}.label{display:block;color:var(--muted);font-size:.78rem;font-weight:700;margin-bottom:.4rem}
.input,.select,.textarea{width:100%;background:rgba(255,255,255,.04);border:1px solid var(--line);border-radius:9px;color:var(--text);font-family:var(--font-body);font-size:.88rem;padding:.7rem .85rem;outline:none}
.textarea{min-height:120px;resize:vertical;line-height:1.5}
.input:focus,.select:focus,.textarea:focus{border-color:rgba(255,107,43,.55);box-shadow:0 0 0 3px rgba(255,107,43,.1)}
.select option{background:#1A1714;color:var(--text)}
.btn{border:0;border-radius:10px;padding:.75rem 1rem;background:linear-gradient(135deg,var(--orange),var(--red));color:#fff;font-weight:800;cursor:pointer;font-family:var(--font-body)}
.btn-secondary{background:rgba(255,255,255,.06);border:1px solid var(--line);color:var(--text)}
.alert{padding:.8rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:.84rem}.error{background:rgba(232,41,76,.1);border:1px solid rgba(232,41,76,.28);color:#ff8aa4}.ok{background:rgba(139,218,79,.1);border:1px solid rgba(139,218,79,.28);color:var(--lime)}
.topbar{position:sticky;top:0;z-index:10;background:rgba(11,10,8,.96);border-bottom:1px solid var(--line);padding:1rem 1.5rem;display:flex;justify-content:space-between;align-items:center;gap:1rem}
.page{max-width:1280px;margin:0 auto;padding:2rem 1.25rem}.page-head{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem}
.count{color:var(--muted);font-size:.9rem}.orders{display:grid;gap:1rem}
.order{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:1.2rem}
.order-grid{display:grid;grid-template-columns:1fr 1.1fr;gap:1.25rem}
.meta-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;margin-top:1rem}
.meta{background:rgba(255,255,255,.03);border:1px solid var(--line);border-radius:9px;padding:.65rem}.meta b{display:block;font-size:.72rem;color:var(--muted);margin-bottom:.2rem}.meta span{font-size:.86rem;word-break:break-word}
.order-id{font-family:var(--font-display);font-size:1.45rem}.badge{display:inline-flex;border:1px solid rgba(255,184,0,.25);background:rgba(255,184,0,.08);color:var(--gold);border-radius:999px;padding:.3rem .65rem;font-size:.74rem;font-weight:800;margin-top:.3rem}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem}
.attachments{display:flex;flex-wrap:wrap;gap:.45rem;margin-top:.75rem}.attachment{max-width:230px;display:inline-flex;gap:.35rem;align-items:center;border:1px solid var(--line);background:rgba(255,255,255,.04);border-radius:8px;padding:.42rem .6rem;font-size:.76rem;color:var(--text)}.attachment span:last-child{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
@media (max-width:860px){.order-grid,.form-row,.meta-grid{grid-template-columns:1fr}.topbar{align-items:flex-start;flex-direction:column}}
</style>
</head>
<body>
<?php if (!adminIsLoggedIn()): ?>
  <main class="login-wrap">
    <section class="login-card">
      <div class="brand"><span>Taqueros</span>Web Admin</div>
      <h1 class="title">Acceso administrador</h1>
      <p class="sub">Ingresa con el usuario definido en el archivo .env.</p>
      <?php if ($adminError): ?><div class="alert error"><?= htmlspecialchars($adminError, ENT_QUOTES) ?></div><?php endif; ?>
      <form method="post" autocomplete="off">
        <input type="hidden" name="action" value="login">
        <div class="field">
          <label class="label" for="user">Usuario</label>
          <input class="input" id="user" name="user" required>
        </div>
        <div class="field">
          <label class="label" for="password">Contraseña</label>
          <input class="input" id="password" name="password" type="password" required>
        </div>
        <button class="btn" type="submit" style="width:100%">Entrar</button>
      </form>
    </section>
  </main>
<?php else: ?>
  <header class="topbar">
    <div class="brand"><span>Taqueros</span>Web Admin</div>
    <a class="btn btn-secondary" href="/dashboard_admin.php?action=logout">Cerrar sesión</a>
  </header>
  <main class="page">
    <div class="page-head">
      <div>
        <h1 class="title">Ordenes existentes</h1>
        <p class="count"><?= count($orders) ?> orden(es) registradas con datos de cliente y proyecto.</p>
      </div>
    </div>
    <?php if ($adminNotice): ?><div class="alert ok"><?= htmlspecialchars($adminNotice, ENT_QUOTES) ?></div><?php endif; ?>
    <?php if ($adminError): ?><div class="alert error"><?= htmlspecialchars($adminError, ENT_QUOTES) ?></div><?php endif; ?>

    <section class="orders">
      <?php foreach ($orders as $order):
        $ordenId = (int)$order['id'];
        $fechaOrden = date('Y-m-d\TH:i', strtotime((string)$order['created_at']));
        $adjuntos = $attachmentsByOrder[$ordenId] ?? [];
      ?>
        <article class="order">
          <div class="order-grid">
            <div>
              <div class="order-id">Orden #<?= $ordenId ?></div>
              <div class="badge"><?= htmlspecialchars($order['estado'], ENT_QUOTES) ?></div>
              <div class="meta-grid">
                <div class="meta"><b>Cliente</b><span><?= htmlspecialchars($order['cliente_nombre'] . ' ' . $order['cliente_apellidos'], ENT_QUOTES) ?></span></div>
                <div class="meta"><b>Correo</b><span><?= htmlspecialchars($order['cliente_email'], ENT_QUOTES) ?></span></div>
                <div class="meta"><b>Telefono</b><span><?= htmlspecialchars($order['cliente_telefono'], ENT_QUOTES) ?></span></div>
                <div class="meta"><b>Fecha de creacion</b><span><?= date('d/m/Y H:i', strtotime((string)$order['created_at'])) ?></span></div>
                <div class="meta"><b>Paquete</b><span><?= htmlspecialchars($order['paquete_nombre'], ENT_QUOTES) ?> - $<?= number_format((float)$order['paquete_precio'], 2) ?></span></div>
                <div class="meta"><b>Cliente desde</b><span><?= date('d/m/Y H:i', strtotime((string)$order['cliente_created_at'])) ?></span></div>
              </div>
              <?php if (!empty($adjuntos)): ?>
                <div class="attachments">
                  <?php foreach ($adjuntos as $adjunto): ?>
                    <a class="attachment" href="<?= htmlspecialchars($adjunto['file_path'], ENT_QUOTES) ?>" target="_blank" rel="noopener">
                      <span>📎</span><span><?= htmlspecialchars($adjunto['original_name'], ENT_QUOTES) ?></span>
                    </a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>

            <form method="post">
              <input type="hidden" name="action" value="update_order">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars(adminCsrfToken(), ENT_QUOTES) ?>">
              <input type="hidden" name="orden_id" value="<?= $ordenId ?>">
              <div class="field">
                <label class="label">Cliente asignado</label>
                <select class="select" name="cliente_id" required>
                  <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id'] ?>" <?= (int)$cliente['id'] === (int)$order['cliente_id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellidos'] . ' - ' . $cliente['email'], ENT_QUOTES) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-row">
                <div class="field">
                  <label class="label">Paquete</label>
                  <select class="select" name="paquete_id" required>
                    <?php foreach ($paquetes as $paquete): ?>
                      <option value="<?= $paquete['id'] ?>" <?= (int)$paquete['id'] === (int)$order['paquete_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($paquete['emoji'] . ' ' . $paquete['nombre'], ENT_QUOTES) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="field">
                  <label class="label">Estado</label>
                  <select class="select" name="estado" required>
                    <?php foreach ($estados as $estado): ?>
                      <option value="<?= $estado ?>" <?= $estado === $order['estado'] ? 'selected' : '' ?>><?= htmlspecialchars($estado, ENT_QUOTES) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="field">
                <label class="label">Descripcion</label>
                <textarea class="textarea" name="descripcion" required><?= htmlspecialchars(html_entity_decode((string)$order['descripcion'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES) ?></textarea>
              </div>
              <div class="form-row">
                <div class="field">
                  <label class="label">MP Preference ID</label>
                  <input class="input" name="mp_preference_id" value="<?= htmlspecialchars((string)($order['mp_preference_id'] ?? ''), ENT_QUOTES) ?>">
                </div>
                <div class="field">
                  <label class="label">Fecha de creacion</label>
                  <input class="input" type="datetime-local" name="created_at" value="<?= htmlspecialchars($fechaOrden, ENT_QUOTES) ?>" required>
                </div>
              </div>
              <button class="btn" type="submit">Guardar cambios</button>
            </form>
          </div>
        </article>
      <?php endforeach; ?>
    </section>
  </main>
<?php endif; ?>
</body>
</html>
