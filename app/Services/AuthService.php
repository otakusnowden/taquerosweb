<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\DTOs\RegisterDTO;
use App\Repositories\ClienteRepository;
use App\Repositories\OrdenRepository;
use App\Repositories\PaqueteRepository;
use App\Repositories\OrdenLogRepository;

final class AuthService
{
    public function __construct(
        private readonly ClienteRepository $clientes = new ClienteRepository(),
        private readonly OrdenRepository   $ordenes  = new OrdenRepository(),
        private readonly PaqueteRepository $paquetes = new PaqueteRepository(),
        private readonly OrdenLogRepository $logs    = new OrdenLogRepository(),
        private readonly EmailService      $email    = new EmailService(),
    ) {}

    /**
     * Register a new client and create an initial draft order.
     * Returns ['cliente' => [...], 'orden' => [...]]
     * @throws \RuntimeException on duplicate email or invalid package
     */
    public function register(RegisterDTO $dto): array
    {
        // 1. Check duplicate email
        if ($this->clientes->findByEmail($dto->email)) {
            throw new \RuntimeException('Este correo ya tiene una cuenta registrada.');
        }

        // 2. Validate package exists
        $paquete = $this->paquetes->findById($dto->paqueteId);
        if (!$paquete) {
            throw new \RuntimeException('El paquete seleccionado no existe.');
        }

        // 3. Create client + order in a transaction
        Database::beginTransaction();
        try {
            $token      = bin2hex(random_bytes(32));
            $clienteId  = $this->clientes->create([
                'nombre'             => $dto->nombre,
                'apellidos'          => $dto->apellidos,
                'telefono'           => $dto->telefono,
                'email'              => $dto->email,
                'password_hash'      => password_hash($dto->password, PASSWORD_BCRYPT, ['cost' => 12]),
                'verification_token' => $token,
            ]);

            $ordenId = $this->ordenes->create([
                'cliente_id'  => $clienteId,
                'paquete_id'  => $dto->paqueteId,
                'descripcion' => $dto->descripcion,
            ]);

            $this->logs->log($ordenId, 'creada', 'Orden creada junto con registro de cliente.', $clienteId);

            Database::commit();
        } catch (\Throwable $e) {
            Database::rollback();
            throw new \RuntimeException('Error al crear la cuenta: ' . $e->getMessage());
        }

        $cliente = $this->clientes->findById($clienteId);
        $orden   = $this->ordenes->findById($ordenId);

        // 4. Send emails (non-blocking — failures logged, not thrown)
        $this->email->sendVerificationEmail($dto->email, $dto->nombre, $token);
        $this->email->sendAdminNewOrder($cliente, $paquete, $orden);

        return compact('cliente', 'orden');
    }

    /**
     * Verify email token.
     * @throws \RuntimeException on invalid/expired token
     */
    public function verifyEmail(string $token): array
    {
        $cliente = $this->clientes->findByVerificationToken($token);
        if (!$cliente) {
            throw new \RuntimeException('El enlace de verificación es inválido o ya fue usado.');
        }
        $this->clientes->markEmailVerified($cliente['id']);
        return $cliente;
    }

    /**
     * Authenticate and return client array.
     * @throws \RuntimeException on invalid credentials or unverified email
     */
    public function login(string $email, string $password): array
    {
        $cliente = $this->clientes->findByEmail(strtolower(trim($email)));

        if (!$cliente || !password_verify($password, $cliente['password_hash'])) {
            throw new \RuntimeException('Correo o contraseña incorrectos.');
        }

        if (!$cliente['email_verified_at']) {
            throw new \RuntimeException(
                'Debes confirmar tu correo electrónico antes de iniciar sesión. Revisa tu bandeja de entrada.'
            );
        }

        $this->clientes->updateLastLogin($cliente['id']);
        return $cliente;
    }
}
