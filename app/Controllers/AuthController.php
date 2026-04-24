<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Response;
use App\DTOs\RegisterDTO;
use App\Services\AuthService;

final class AuthController
{
    public function __construct(
        private readonly AuthService $service = new AuthService()
    ) {}

    public function register(): never
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::error('Method not allowed.', 405);
        }

        $body = $this->parseBody();

        try {
            $dto    = new RegisterDTO($body);
            $result = $this->service->register($dto);
            Response::success(
                ['orden_id' => $result['orden']['id']],
                '¡Registro exitoso! Revisa tu correo para confirmar tu cuenta.'
            );
        } catch (\InvalidArgumentException $e) {
            $errors = json_decode($e->getMessage(), true) ?? [];
            Response::error('Datos inválidos.', 422, $errors);
        } catch (\RuntimeException $e) {
            Response::error($e->getMessage(), 409);
        }
    }

    public function login(): never
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::error('Method not allowed.', 405);
        }

        $body     = $this->parseBody();
        $email    = trim($body['email'] ?? '');
        $password = $body['password'] ?? '';

        if (!$email || !$password) {
            Response::error('Correo y contraseña son requeridos.', 422);
        }

        try {
            $cliente = $this->service->login($email, $password);
            Auth::login($cliente);
            Response::success(
                ['redirect' => APP_URL . '/dashboard'],
                'Bienvenido de vuelta, ' . $cliente['nombre'] . '!'
            );
        } catch (\RuntimeException $e) {
            Response::error($e->getMessage(), 401);
        }
    }

    public function verifyEmail(): never
    {
        $token = trim($_GET['token'] ?? '');
        if (!$token) {
            Response::error('Token inválido.', 400);
        }

        try {
            $this->service->verifyEmail($token);
            // Redirect to login with success flag
            Response::redirect(APP_URL . '/login?verified=1');
        } catch (\RuntimeException $e) {
            Response::redirect(APP_URL . '/login?error=' . urlencode($e->getMessage()));
        }
    }

    public function logout(): never
    {
        Auth::logout();
        Response::redirect(APP_URL . '/login?logout=1');
    }

    private function parseBody(): array
    {
        $ct = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($ct, 'application/json')) {
            $raw  = file_get_contents('php://input');
            $data = json_decode($raw, true) ?? [];
        } else {
            $data = $_POST;
        }
        return $data;
    }
}
