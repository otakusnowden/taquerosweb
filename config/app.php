<?php
declare(strict_types=1);

// ── Autoloader ────────────────────────────────────────────
require_once __DIR__ . '/../vendor/autoload.php';

// ── Load .env ─────────────────────────────────────────────
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
$dotenv->required([
    'APP_URL','APP_SECRET',
    'DB_HOST','DB_NAME','DB_USER','DB_PASS',
    'MAIL_HOST','MAIL_USER','MAIL_PASS','MAIL_FROM','MAIL_ADMIN',
    'MP_ACCESS_TOKEN',
]);

// ── Error reporting ───────────────────────────────────────
if ($_ENV['APP_ENV'] === 'development' || $_ENV['APP_DEBUG'] === 'true') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// ── Session (secure) ──────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_strict_mode', '1');
    if (isset($_ENV['APP_URL']) && str_starts_with($_ENV['APP_URL'], 'https')) {
        ini_set('session.cookie_secure', '1');
    }
    session_name('TWSESS');
    session_start();
}

// ── Timezone ──────────────────────────────────────────────
date_default_timezone_set('America/Mexico_City');

// ── Constants ─────────────────────────────────────────────
define('APP_ROOT', dirname(__DIR__));
define('APP_URL',  rtrim($_ENV['APP_URL'], '/'));
