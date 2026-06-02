<?php
/** Sesión y autenticación del panel administrativo. */
require_once __DIR__ . '/../includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool
{
    return !empty($_SESSION['hw_admin']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

/**
 * Procesa la subida de imágenes a un directorio de uploads.
 *
 * @param string $field   nombre del input file
 * @param string $destDir directorio destino absoluto
 * @param int    $max     número máximo de imágenes permitidas
 * @param bool   $replace si true, vacía el directorio antes de guardar
 * @param string $fixed   si se indica, fuerza el nombre base (para menú)
 * @return array{ok:bool, msg:string}
 */
function handle_upload(string $field, string $destDir, int $max, bool $replace, string $fixed = ''): array
{
    if (empty($_FILES[$field]) || !isset($_FILES[$field]['name'])) {
        return ['ok' => false, 'msg' => 'No se recibió ningún archivo.'];
    }

    // Normaliza a arreglo (input simple o múltiple)
    $names = (array) $_FILES[$field]['name'];
    $tmps  = (array) $_FILES[$field]['tmp_name'];
    $errs  = (array) $_FILES[$field]['error'];
    $sizes = (array) $_FILES[$field]['size'];

    // Filtra slots vacíos
    $items = [];
    foreach ($names as $i => $n) {
        if ($errs[$i] === UPLOAD_ERR_NO_FILE || $n === '') {
            continue;
        }
        $items[] = ['name' => $n, 'tmp' => $tmps[$i], 'err' => $errs[$i], 'size' => $sizes[$i]];
    }

    if (!$items) {
        return ['ok' => false, 'msg' => 'Selecciona al menos una imagen.'];
    }
    if (count($items) > $max) {
        return ['ok' => false, 'msg' => "Máximo $max imagen(es) para esta sección."];
    }

    // Validación previa
    $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : null;
    $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];
    $clean = [];
    $error = '';
    foreach ($items as $it) {
        if ($it['err'] !== UPLOAD_ERR_OK) {
            $error = 'Error al subir uno de los archivos.';
            break;
        }
        if ($it['size'] > MAX_UPLOAD) {
            $error = 'Una imagen supera el tamaño máximo permitido (6 MB).';
            break;
        }
        $ext = strtolower(pathinfo($it['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ALLOWED_EXT, true)) {
            $error = 'Solo se permiten imágenes JPG, PNG o WEBP.';
            break;
        }
        if ($finfo) {
            $mime = finfo_file($finfo, $it['tmp']);
            if (!in_array($mime, $allowedMime, true)) {
                $error = 'El archivo no es una imagen válida.';
                break;
            }
        }
        $clean[] = ['tmp' => $it['tmp'], 'ext' => $ext === 'jpeg' ? 'jpg' : $ext];
    }
    // Siempre liberar el recurso finfo antes de retornar (evita fugas / cierres sucios).
    if ($finfo) {
        finfo_close($finfo);
    }
    if ($error !== '') {
        return ['ok' => false, 'msg' => $error];
    }

    if (!is_dir($destDir) && !mkdir($destDir, 0775, true) && !is_dir($destDir)) {
        return ['ok' => false, 'msg' => 'No se pudo acceder al directorio de destino.'];
    }

    // Reemplazo: limpia las imágenes existentes
    if ($replace) {
        foreach (glob($destDir . '/*') ?: [] as $old) {
            if (is_file($old)) {
                @unlink($old);
            }
        }
    }

    // Guarda los archivos
    $saved = 0;
    foreach ($clean as $idx => $c) {
        if ($fixed !== '') {
            $target = $destDir . '/' . $fixed . '.' . $c['ext'];
        } else {
            $target = $destDir . '/' . sprintf('%02d_carrusel.%s', $idx + 1, $c['ext']);
        }
        if (move_uploaded_file($c['tmp'], $target)) {
            $saved++;
        }
    }

    if ($saved === 0) {
        return ['ok' => false, 'msg' => 'No se pudo guardar ninguna imagen.'];
    }
    return ['ok' => true, 'msg' => "Se guardó correctamente ($saved imagen" . ($saved > 1 ? 'es' : '') . ').'];
}
