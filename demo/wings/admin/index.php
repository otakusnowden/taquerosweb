<?php
require_once __DIR__ . '/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['user'] ?? '';
    $p = $_POST['pass'] ?? '';
    if (hash_equals(ADMIN_USER, $u) && hash_equals(ADMIN_PASS, $p)) {
        session_regenerate_id(true);
        $_SESSION['hw_admin'] = true;
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Usuario o contraseña incorrectos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Acceso · Panel Hot Wings</title>
    <link rel="icon" type="image/png" href="../assets/img/wings_logo.png">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-login">
    <div class="login-card">
        <img src="../assets/img/wings_logo.png" alt="Hot Wings" class="login-logo">
        <h1>Panel administrativo</h1>
        <p class="login-sub">Ingresa tus credenciales para continuar.</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="off">
            <div class="field">
                <label for="user">Usuario</label>
                <input type="text" id="user" name="user" required autofocus>
            </div>
            <div class="field">
                <label for="pass">Contraseña</label>
                <input type="password" id="pass" name="pass" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>
        <a href="../index.php" class="back-link">← Volver al sitio</a>
    </div>
</body>
</html>
