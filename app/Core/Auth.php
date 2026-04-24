<?php
declare(strict_types=1);

namespace App\Core;

final class Auth
{
    private const SESSION_KEY = 'tw_cliente';

    /** Store authenticated client in session */
    public static function login(array $cliente): void
    {
        session_regenerate_id(true);
        $_SESSION[self::SESSION_KEY] = [
            'id'       => $cliente['id'],
            'nombre'   => $cliente['nombre'],
            'apellidos'=> $cliente['apellidos'],
            'email'    => $cliente['email'],
            'loginAt'  => time(),
        ];
    }

    /** Destroy session */
    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
    }

    /** Check if client is logged in and session not expired */
    public static function check(): bool
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            return false;
        }
        $lifetime = (int)($_ENV['SESSION_LIFETIME'] ?? 7200);
        if (time() - $_SESSION[self::SESSION_KEY]['loginAt'] > $lifetime) {
            self::logout();
            return false;
        }
        // Refresh timestamp
        $_SESSION[self::SESSION_KEY]['loginAt'] = time();
        return true;
    }

    /** Get current logged-in client data or null */
    public static function user(): ?array
    {
        return self::check() ? $_SESSION[self::SESSION_KEY] : null;
    }

    /** Redirect to login if not authenticated */
    public static function requireAuth(string $redirectTo = '/login'): void
    {
        if (!self::check()) {
            header('Location: ' . APP_URL . $redirectTo);
            exit;
        }
    }

    /** Redirect to dashboard if already authenticated */
    public static function requireGuest(string $redirectTo = '/dashboard'): void
    {
        if (self::check()) {
            header('Location: ' . APP_URL . $redirectTo);
            exit;
        }
    }
}
