<?php
declare(strict_types=1);

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailException;

final class EmailService
{
    private function buildMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host        = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth    = true;
        $mail->Username    = $_ENV['MAIL_USER'];
        $mail->Password    = $_ENV['MAIL_PASS'];
        $mail->SMTPSecure  = $_ENV['MAIL_ENCRYPTION'] === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port        = (int)$_ENV['MAIL_PORT'];
        $mail->CharSet     = 'UTF-8';
        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
        return $mail;
    }

    /** 1. Email verification for new client */
    public function sendVerificationEmail(string $toEmail, string $nombre, string $token): bool
    {
        $verifyUrl = APP_URL . '/verificar-email?token=' . urlencode($token);

        try {
            $mail = $this->buildMailer();
            $mail->addAddress($toEmail, $nombre);
            $mail->Subject = '🌮 Confirma tu correo — TaquerosWeb';
            $mail->isHTML(true);
            $mail->Body    = $this->templateVerification($nombre, $verifyUrl);
            $mail->AltBody = "Hola {$nombre}, confirma tu correo: {$verifyUrl}";
            $mail->send();
            return true;
        } catch (MailException) {
            error_log("EmailService::sendVerificationEmail failed for {$toEmail}: " . $mail->ErrorInfo);
            return false;
        }
    }

    /** 2. Admin notification on new order */
    public function sendAdminNewOrder(array $cliente, array $paquete, array $orden): bool
    {
        try {
            $mail = $this->buildMailer();
            $mail->addAddress($_ENV['MAIL_ADMIN']);
            $mail->Subject = "🔥 Nueva orden #{$orden['id']} — {$cliente['nombre']} {$cliente['apellidos']}";
            $mail->isHTML(true);
            $mail->Body    = $this->templateAdminNewOrder($cliente, $paquete, $orden);
            $mail->AltBody = "Nueva orden de {$cliente['nombre']} — Paquete: {$paquete['nombre']} — Precio: \${$paquete['precio']} MXN";
            $mail->send();
            return true;
        } catch (MailException) {
            error_log("EmailService::sendAdminNewOrder failed: " . $mail->ErrorInfo);
            return false;
        }
    }

    /** 3. Order confirmed → pending payment */
    public function sendOrderConfirmed(string $toEmail, string $nombre, array $orden, array $paquete): bool
    {
        try {
            $mail = $this->buildMailer();
            $mail->addAddress($toEmail, $nombre);
            $mail->Subject = "✅ Orden #{$orden['id']} confirmada — TaquerosWeb";
            $mail->isHTML(true);
            $mail->Body    = $this->templateOrderConfirmed($nombre, $orden, $paquete);
            $mail->AltBody = "Tu orden #{$orden['id']} ha sido confirmada. Procede al pago en " . APP_URL . '/dashboard';
            $mail->send();
            return true;
        } catch (MailException) {
            error_log("EmailService::sendOrderConfirmed failed: " . $mail->ErrorInfo);
            return false;
        }
    }

    /** 4. Payment approved */
    public function sendPaymentApproved(string $toEmail, string $nombre, array $orden, array $paquete): bool
    {
        try {
            $mail = $this->buildMailer();
            $mail->addAddress($toEmail, $nombre);
            $mail->Subject = "🎉 ¡Pago recibido! Orden #{$orden['id']} — TaquerosWeb";
            $mail->isHTML(true);
            $mail->Body    = $this->templatePaymentApproved($nombre, $orden, $paquete);
            $mail->AltBody = "¡Listo! Recibimos tu pago. Empezamos con tu sitio web hoy. Orden #{$orden['id']}";
            $mail->send();
            return true;
        } catch (MailException) {
            error_log("EmailService::sendPaymentApproved failed: " . $mail->ErrorInfo);
            return false;
        }
    }

    /** 5. Admin notification: cliente avisó que realizó transferencia SPEI */
    public function sendAdminSpeiNotification(array $cliente, array $paquete, array $orden, string $concepto): bool
    {
        try {
            $mail = $this->buildMailer();
            $mail->addAddress($_ENV['MAIL_ADMIN']);
            $mail->Subject = "[Pago por validar] Orden {$concepto}";
            $mail->isHTML(true);
            $mail->Body    = $this->templateAdminSpeiNotification($cliente, $paquete, $orden, $concepto);
            $mail->AltBody = "El cliente {$cliente['nombre']} {$cliente['apellidos']} notificó una transferencia SPEI por la orden #{$orden['id']} (concepto {$concepto}). Verifica el depósito y actualiza el estado.";
            $mail->send();
            return true;
        } catch (MailException) {
            error_log("EmailService::sendAdminSpeiNotification failed: " . $mail->ErrorInfo);
            return false;
        }
    }

    // ─── HTML Templates ──────────────────────────────────────────────────────

    private function baseWrap(string $content): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>TaquerosWeb</title>
</head>
<body style="margin:0;padding:0;background:#0B0A08;font-family:'Helvetica Neue',Arial,sans-serif">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#0B0A08;padding:40px 20px">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%">
      <!-- Header -->
      <tr>
        <td style="background:linear-gradient(135deg,#FF6B2B,#E8294C,#7B1FA2);padding:32px;border-radius:16px 16px 0 0;text-align:center">
          <h1 style="margin:0;color:#fff;font-size:28px;letter-spacing:2px">🌮 TaquerosWeb.com</h1>
          <p style="margin:8px 0 0;color:rgba(255,255,255,0.8);font-size:14px">Sitios Web que Sí Venden</p>
        </td>
      </tr>
      <!-- Body -->
      <tr>
        <td style="background:#1A1714;padding:40px 32px;border:1px solid rgba(255,255,255,0.07);border-top:none">
          {$content}
        </td>
      </tr>
      <!-- Footer -->
      <tr>
        <td style="background:#141210;padding:24px 32px;border-radius:0 0 16px 16px;border:1px solid rgba(255,255,255,0.07);border-top:none;text-align:center">
          <p style="margin:0;color:#8A8278;font-size:12px">© 2026 TaquerosWeb.com · Ciudad de México · <a href="mailto:hola@taquerosweb.com" style="color:#FF6B2B">hola@taquerosweb.com</a></p>
          <p style="margin:8px 0 0;color:#555;font-size:11px">Este correo fue enviado automáticamente. Por favor no respondas a este mensaje.</p>
        </td>
      </tr>
    </table>
  </td></tr>
</table>
</body>
</html>
HTML;
    }

    private function templateVerification(string $nombre, string $url): string
    {
        $content = <<<HTML
<h2 style="color:#F5F0E8;font-size:22px;margin:0 0 16px">¡Hola, {$nombre}! 👋</h2>
<p style="color:#8A8278;font-size:15px;line-height:1.7;margin:0 0 24px">
  Gracias por registrarte en <strong style="color:#FF6B2B">TaquerosWeb.com</strong>. 
  Tu cuenta ha sido creada y tu orden está en espera. Para continuar, confirma tu correo electrónico:
</p>
<div style="text-align:center;margin:32px 0">
  <a href="{$url}" style="background:linear-gradient(135deg,#FF6B2B,#E8294C);color:#fff;text-decoration:none;padding:16px 40px;border-radius:12px;font-size:16px;font-weight:700;display:inline-block">
    ✅ Confirmar mi correo
  </a>
</div>
<p style="color:#555;font-size:13px;line-height:1.6">
  O copia y pega este enlace en tu navegador:<br>
  <a href="{$url}" style="color:#FF6B2B;word-break:break-all">{$url}</a>
</p>
<p style="color:#555;font-size:12px;margin-top:24px;border-top:1px solid rgba(255,255,255,0.07);padding-top:16px">
  ⚠️ Este enlace expira en 48 horas. Si no creaste esta cuenta, ignora este mensaje.
</p>
HTML;
        return $this->baseWrap($content);
    }

    private function templateAdminNewOrder(array $c, array $p, array $o): string
    {
        $precio = number_format((float)$p['precio'], 2);
        $content = <<<HTML
<h2 style="color:#F5F0E8;font-size:20px;margin:0 0 8px">🔥 Nueva orden recibida — #{$o['id']}</h2>
<p style="color:#8A8278;font-size:14px;margin:0 0 24px">Revísala en el panel de administración.</p>
<table style="width:100%;border-collapse:collapse">
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px;width:40%">Cliente</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#F5F0E8;font-size:14px">{$c['nombre']} {$c['apellidos']}</td></tr>
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px">Email</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#FF6B2B;font-size:14px">{$c['email']}</td></tr>
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px">Teléfono</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#F5F0E8;font-size:14px">{$c['telefono']}</td></tr>
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px">Paquete</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#F5F0E8;font-size:14px">{$p['emoji']} {$p['nombre']}</td></tr>
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px">Precio</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#FFB800;font-size:16px;font-weight:700">\${$precio} MXN</td></tr>
  <tr><td style="padding:10px;color:#8A8278;font-size:13px;vertical-align:top">Descripción</td><td style="padding:10px;color:#F5F0E8;font-size:13px;line-height:1.6">{$o['descripcion']}</td></tr>
</table>
HTML;
        return $this->baseWrap($content);
    }

    private function templateOrderConfirmed(string $nombre, array $o, array $p): string
    {
        $precio = number_format((float)$p['precio'], 2);
        $dashUrl = APP_URL . '/dashboard';
        $content = <<<HTML
<h2 style="color:#F5F0E8;font-size:22px;margin:0 0 16px">¡Orden confirmada, {$nombre}! ✅</h2>
<p style="color:#8A8278;font-size:15px;line-height:1.7;margin:0 0 24px">
  Tu orden <strong style="color:#FF6B2B">#{$o['id']}</strong> ha sido confirmada. 
  Ya puedes proceder al pago para que nuestro equipo empiece a trabajar en tu sitio web.
</p>
<div style="background:rgba(255,107,43,0.08);border:1px solid rgba(255,107,43,0.2);border-radius:12px;padding:20px;margin-bottom:24px">
  <p style="margin:0;color:#F5F0E8;font-size:14px"><strong>Paquete:</strong> {$p['emoji']} {$p['nombre']}</p>
  <p style="margin:8px 0 0;color:#FFB800;font-size:22px;font-weight:700">\${$precio} MXN</p>
</div>
<div style="text-align:center">
  <a href="{$dashUrl}" style="background:linear-gradient(135deg,#FF6B2B,#E8294C);color:#fff;text-decoration:none;padding:16px 40px;border-radius:12px;font-size:16px;font-weight:700;display:inline-block">
    💳 Ir a pagar
  </a>
</div>
HTML;
        return $this->baseWrap($content);
    }

    private function templateAdminSpeiNotification(array $c, array $p, array $o, string $concepto): string
    {
        $precio   = number_format((float)$p['precio'], 2);
        $fecha    = date('d/m/Y H:i');
        $adminUrl = APP_URL . '/dashboard_admin';
        $content = <<<HTML
<h2 style="color:#F5F0E8;font-size:20px;margin:0 0 8px">💳 Pago SPEI por validar — {$concepto}</h2>
<p style="color:#8A8278;font-size:14px;margin:0 0 24px">El cliente notificó que realizó una transferencia SPEI. Verifica el depósito en Mercado Pago W y actualiza el estado de la orden manualmente.</p>
<table style="width:100%;border-collapse:collapse">
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px;width:40%">Concepto de pago</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#FFB800;font-size:15px;font-weight:700">{$concepto}</td></tr>
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px">ID de orden</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#F5F0E8;font-size:14px">#{$o['id']}</td></tr>
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px">Cliente</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#F5F0E8;font-size:14px">{$c['nombre']} {$c['apellidos']}</td></tr>
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px">Email cliente</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#FF6B2B;font-size:14px">{$c['email']}</td></tr>
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px">Teléfono</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#F5F0E8;font-size:14px">{$c['telefono']}</td></tr>
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px">Paquete</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#F5F0E8;font-size:14px">{$p['emoji']} {$p['nombre']}</td></tr>
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px">Monto esperado</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#FFB800;font-size:16px;font-weight:700">\${$precio} MXN</td></tr>
  <tr><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#8A8278;font-size:13px">Método</td><td style="padding:10px;border-bottom:1px solid rgba(255,255,255,0.07);color:#F5F0E8;font-size:14px">Transferencia SPEI</td></tr>
  <tr><td style="padding:10px;color:#8A8278;font-size:13px">Fecha de aviso</td><td style="padding:10px;color:#F5F0E8;font-size:13px">{$fecha}</td></tr>
</table>
<div style="margin-top:28px;text-align:center">
  <a href="{$adminUrl}" style="background:linear-gradient(135deg,#FF6B2B,#E8294C);color:#fff;text-decoration:none;padding:14px 32px;border-radius:12px;font-size:14px;font-weight:700;display:inline-block">
    Ver orden en el panel admin
  </a>
</div>
HTML;
        return $this->baseWrap($content);
    }

    private function templatePaymentApproved(string $nombre, array $o, array $p): string
    {
        $content = <<<HTML
<h2 style="color:#8BDA4F;font-size:22px;margin:0 0 16px">¡Pago recibido, {$nombre}! 🎉</h2>
<p style="color:#8A8278;font-size:15px;line-height:1.7;margin:0 0 24px">
  Recibimos tu pago por la orden <strong style="color:#FF6B2B">#{$o['id']}</strong>. 
  Nuestro equipo ya está trabajando en tu sitio web. Te contactaremos en las próximas horas por WhatsApp para coordinar los detalles.
</p>
<div style="background:rgba(139,218,79,0.08);border:1px solid rgba(139,218,79,0.2);border-radius:12px;padding:20px;margin-bottom:24px;text-align:center">
  <p style="margin:0;color:#8BDA4F;font-size:16px;font-weight:700">Estado: ✅ PAGADO</p>
  <p style="margin:8px 0 0;color:#8A8278;font-size:13px">Paquete: {$p['emoji']} {$p['nombre']}</p>
</div>
<p style="color:#8A8278;font-size:13px;text-align:center">¿Dudas? Escríbenos a <a href="https://wa.me/5215662866353" style="color:#25D366">WhatsApp</a></p>
HTML;
        return $this->baseWrap($content);
    }
}
