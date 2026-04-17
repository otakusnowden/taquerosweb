<?php
/**
 * send-order.php – Endpoint de envío de órdenes
 * TaquerosWeb.com
 *
 * Dependencia: PHPMailer (instalar vía Composer o subir manualmente)
 * Composer:  composer require phpmailer/phpmailer
 * Manual:    descargar de https://github.com/PHPMailer/PHPMailer
 *            y subir la carpeta src/ junto a este archivo.
 */

declare(strict_types=1);

// ── Cabeceras ────────────────────────────────────────────────────────────────
header('Content-Type: application/json; charset=utf-8');

// CORS: permite peticiones solo desde tu propio dominio en producción.
// Cambia '*' por 'https://taquerosweb.com' si quieres restringir el origen.
$allowedOrigin = 'https://taquerosweb.com';
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin === $allowedOrigin) {
    header("Access-Control-Allow-Origin: $allowedOrigin");
} else {
    // En local/dev puedes comentar estas líneas y poner '*' temporalmente.
    header("Access-Control-Allow-Origin: $allowedOrigin");
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Pre-flight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ── Solo POST ────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}

// ── Leer y decodificar JSON ──────────────────────────────────────────────────
$raw = file_get_contents('php://input');
if (empty($raw)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Cuerpo vacío.']);
    exit;
}

$data = json_decode($raw, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'JSON inválido.']);
    exit;
}

// ── Cargar configuración ─────────────────────────────────────────────────────
$cfg = require __DIR__ . '/config.php';

// ── Función de sanitización ──────────────────────────────────────────────────
function sanitize(mixed $value, int $maxLen): string {
    if (!is_string($value)) return '';
    $value = strip_tags(trim($value));
    return mb_substr($value, 0, $maxLen);
}

// ── Sanitizar y validar campos ───────────────────────────────────────────────
$ml   = $cfg['max_lengths'];
$name  = sanitize($data['name']  ?? '', $ml['name']);
$email = sanitize($data['email'] ?? '', $ml['email']);
$phone = sanitize($data['phone'] ?? '', $ml['phone']);
$biz   = sanitize($data['biz']   ?? '', $ml['biz']);
$desc  = sanitize($data['desc']  ?? '', $ml['desc']);
$pkg   = sanitize($data['pkg']   ?? '', $ml['pkg']);
$price = sanitize($data['price'] ?? '', $ml['price']);

// Campos requeridos
if (!$name || !$email || !$phone || !$biz) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Faltan campos requeridos.']);
    exit;
}

// Validar formato email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Correo electrónico inválido.']);
    exit;
}

// ── Cargar PHPMailer ─────────────────────────────────────────────────────────
// Opción A – instalado con Composer (recomendado en cPanel con soporte SSH)
$composerAutoload = __DIR__ . '/../vendor/autoload.php';
// Opción B – subido manualmente junto a este archivo
$manualAutoload   = __DIR__ . '/PHPMailer/src/PHPMailer.php';

if (file_exists($composerAutoload)) {
    require $composerAutoload;
} elseif (file_exists($manualAutoload)) {
    require $manualAutoload;
    require __DIR__ . '/PHPMailer/src/SMTP.php';
    require __DIR__ . '/PHPMailer/src/Exception.php';
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'PHPMailer no encontrado en el servidor.']);
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ── Fecha y hora de la orden ─────────────────────────────────────────────────
$timestamp = (new DateTime('now', new DateTimeZone('America/Mexico_City')))->format('d/m/Y H:i:s');

// ── Armar el cuerpo HTML del correo ─────────────────────────────────────────
$descHtml = $desc ? nl2br(htmlspecialchars($desc, ENT_QUOTES, 'UTF-8')) : '<em style="color:#999">No especificado</em>';

$emailBody = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nueva Orden – TaquerosWeb</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:30px 0">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08)">

          <!-- Header -->
          <tr>
            <td style="background:linear-gradient(135deg,#e8292c 0%,#ff6b35 100%);padding:30px 40px;text-align:center">
              <div style="font-size:2.5rem">🌮</div>
              <h1 style="color:#fff;margin:8px 0 4px;font-size:1.5rem;letter-spacing:-0.5px">¡Nueva Orden Recibida!</h1>
              <p style="color:rgba(255,255,255,.85);margin:0;font-size:0.9rem">TaquerosWeb.com · {$timestamp} (CDMX)</p>
            </td>
          </tr>

          <!-- Paquete -->
          <tr>
            <td style="padding:28px 40px 0">
              <table width="100%" cellpadding="12" cellspacing="0" style="background:#fff8f0;border-radius:8px;border:1px solid #ffe0c0">
                <tr>
                  <td>
                    <div style="font-size:0.75rem;color:#999;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Paquete seleccionado</div>
                    <div style="font-size:1.2rem;font-weight:700;color:#e8292c">{$pkg}</div>
                    <div style="font-size:1rem;font-weight:600;color:#333;margin-top:2px">{$price}</div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Datos del cliente -->
          <tr>
            <td style="padding:24px 40px 0">
              <h2 style="font-size:0.85rem;text-transform:uppercase;letter-spacing:1px;color:#999;margin:0 0 14px;border-bottom:1px solid #eee;padding-bottom:8px">Datos del cliente</h2>
              <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="padding:7px 0;border-bottom:1px solid #f5f5f5">
                    <span style="color:#666;font-size:0.85rem;min-width:120px;display:inline-block">Nombre</span>
                    <strong style="color:#222">{$name}</strong>
                  </td>
                </tr>
                <tr>
                  <td style="padding:7px 0;border-bottom:1px solid #f5f5f5">
                    <span style="color:#666;font-size:0.85rem;min-width:120px;display:inline-block">Correo</span>
                    <a href="mailto:{$email}" style="color:#e8292c;text-decoration:none;font-weight:600">{$email}</a>
                  </td>
                </tr>
                <tr>
                  <td style="padding:7px 0;border-bottom:1px solid #f5f5f5">
                    <span style="color:#666;font-size:0.85rem;min-width:120px;display:inline-block">Teléfono / WA</span>
                    <a href="https://wa.me/{$phone}" style="color:#25d366;text-decoration:none;font-weight:600">{$phone}</a>
                  </td>
                </tr>
                <tr>
                  <td style="padding:7px 0">
                    <span style="color:#666;font-size:0.85rem;min-width:120px;display:inline-block">Negocio</span>
                    <strong style="color:#222">{$biz}</strong>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Descripción -->
          <tr>
            <td style="padding:20px 40px 0">
              <h2 style="font-size:0.85rem;text-transform:uppercase;letter-spacing:1px;color:#999;margin:0 0 10px;border-bottom:1px solid #eee;padding-bottom:8px">Descripción del negocio</h2>
              <p style="margin:0;color:#444;line-height:1.6;font-size:0.95rem">{$descHtml}</p>
            </td>
          </tr>

          <!-- CTA -->
          <tr>
            <td style="padding:28px 40px;text-align:center">
              <a href="https://wa.me/52{$phone}" style="display:inline-block;background:#25d366;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:700;font-size:0.95rem">
                💬 Responder por WhatsApp
              </a>
              &nbsp;&nbsp;
              <a href="mailto:{$email}" style="display:inline-block;background:#e8292c;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:700;font-size:0.95rem">
                ✉️ Responder por Correo
              </a>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#f9f9f9;padding:16px 40px;text-align:center;border-top:1px solid #eee">
              <p style="margin:0;font-size:0.75rem;color:#bbb">Este correo fue generado automáticamente por el formulario de taquerosweb.com</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;

// ── Enviar con PHPMailer ─────────────────────────────────────────────────────
try {
    $mail = new PHPMailer(true);  // true = habilita excepciones

    // Servidor SMTP
    $mail->isSMTP();
    $mail->Host        = $cfg['smtp_host'];
    $mail->SMTPAuth    = $cfg['smtp_auth'];
    $mail->Username    = $cfg['smtp_user'];
    $mail->Password    = $cfg['smtp_pass'];
    $mail->SMTPSecure  = $cfg['smtp_secure'];   // PHPMailer::ENCRYPTION_SMTPS para SSL
    $mail->Port        = $cfg['smtp_port'];
    $mail->CharSet     = 'UTF-8';
    $mail->SMTPDebug   = SMTP::DEBUG_OFF;       // DEBUG_SERVER para depurar en local

    // Remitente y destinatario
    $mail->setFrom($cfg['mail_from'], $cfg['mail_from_name']);
    $mail->addAddress($cfg['mail_to'], $cfg['mail_to_name']);

    // Reply-To apunta al cliente para responder directo
    $mail->addReplyTo($email, $name);

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = "Nueva orden desde taquerosweb.com – $biz ($pkg)";
    $mail->Body    = $emailBody;
    $mail->AltBody = "Nueva orden de: $name | $email | $phone | $biz | $pkg $price\n\n$desc";

    $mail->send();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // No exponer detalles internos al cliente; loguear en servidor
    error_log('[TaquerosWeb] PHPMailer error: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al enviar el correo.']);
}
