<?php
require_once __DIR__ . '/includes/config.php';
$active    = 'reservacion.php';
$pageTitle = 'Reservación · ' . SITE_NAME;
$pageDesc  = 'Reserva tu mesa en Hot Wings. Completa el formulario y confirma tu reservación por WhatsApp.';

include __DIR__ . '/includes/header.php';
$today = date('Y-m-d');
?>

<section class="section section-dark">
    <div class="container" style="max-width:820px;">
        <div style="text-align:center;" class="reveal">
            <span class="eyebrow">Reserva</span>
            <h1 class="section-title">Reserva tu <span class="hl">mesa</span></h1>
            <p class="lead" style="margin:0 auto;">Completa los datos y enviaremos tu solicitud por WhatsApp. El restaurante confirmará tu reservación.</p>
        </div>

        <form class="form-card reveal" id="reservaForm" data-phone="<?= WHATSAPP_PHONE ?>" novalidate>
            <div id="reservaOk" class="alert alert-success" style="display:none;">
                ¡Listo! Abrimos WhatsApp con tu reservación. Envíanos el mensaje para confirmar. 🎉
            </div>

            <div class="form-grid">
                <div class="field full">
                    <label for="nombre">Nombre completo <span class="req">*</span></label>
                    <input type="text" id="nombre" name="nombre" required autocomplete="name" placeholder="Tu nombre">
                    <span class="error-msg">Por favor escribe tu nombre.</span>
                </div>

                <div class="field">
                    <label for="telefono">Teléfono <span class="req">*</span></label>
                    <input type="tel" id="telefono" name="telefono" required autocomplete="tel" placeholder="55 1234 5678">
                    <span class="error-msg">Ingresa un teléfono de contacto.</span>
                </div>

                <div class="field">
                    <label for="personas">Número de personas <span class="req">*</span></label>
                    <input type="number" id="personas" name="personas" required min="1" max="50" value="2">
                    <span class="error-msg">Indica cuántas personas asistirán.</span>
                </div>

                <div class="field">
                    <label for="fecha">Fecha <span class="req">*</span></label>
                    <input type="date" id="fecha" name="fecha" required min="<?= $today ?>" value="<?= $today ?>">
                    <span class="error-msg">Selecciona una fecha.</span>
                </div>

                <div class="field">
                    <label for="hora">Hora <span class="req">*</span></label>
                    <input type="time" id="hora" name="hora" required value="20:00">
                    <span class="error-msg">Selecciona una hora.</span>
                </div>

                <div class="field full">
                    <label for="area">Área <span class="req">*</span></label>
                    <select id="area" name="area" required>
                        <option value="Interior">Interior</option>
                        <option value="Exterior">Exterior</option>
                        <option value="Barra">Barra</option>
                    </select>
                    <span class="error-msg">Selecciona un área.</span>
                </div>

                <div class="field full">
                    <label for="comentarios">Comentarios adicionales</label>
                    <textarea id="comentarios" name="comentarios" placeholder="Silla para bebé, festejo de cumpleaños, etc."></textarea>
                </div>

                <div class="field full form-actions">
                    <button type="submit" class="btn btn-red btn-block">Reservar por WhatsApp</button>
                </div>
            </div>
        </form>
    </div>
</section>
<?= section_divider() ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
