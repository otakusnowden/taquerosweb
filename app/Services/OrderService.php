<?php
declare(strict_types=1);

namespace App\Services;

use App\DTOs\OrderDTO;
use App\Repositories\OrdenRepository;
use App\Repositories\PaqueteRepository;
use App\Repositories\ClienteRepository;
use App\Repositories\OrdenLogRepository;

final class OrderService
{
    public function __construct(
        private readonly OrdenRepository    $ordenes  = new OrdenRepository(),
        private readonly PaqueteRepository  $paquetes = new PaqueteRepository(),
        private readonly ClienteRepository  $clientes = new ClienteRepository(),
        private readonly OrdenLogRepository $logs     = new OrdenLogRepository(),
        private readonly EmailService       $email    = new EmailService(),
    ) {}

    /** Create a new draft order for an existing client */
    public function createOrder(OrderDTO $dto): array
    {
        $paquete = $this->paquetes->findById($dto->paqueteId);
        if (!$paquete) {
            throw new \RuntimeException('Paquete no válido.');
        }

        $cliente = $this->clientes->findById($dto->clienteId);
        if (!$cliente) {
            throw new \RuntimeException('Cliente no encontrado.');
        }

        $ordenId = $this->ordenes->create([
            'cliente_id'  => $dto->clienteId,
            'paquete_id'  => $dto->paqueteId,
            'descripcion' => $dto->descripcion,
        ]);

        $orden = $this->ordenes->findById($ordenId);
        $this->logs->log($ordenId, 'creada', "Nueva orden creada por cliente.", $dto->clienteId);

        // Notify admin
        $this->email->sendAdminNewOrder($cliente, $paquete, $orden);

        return $orden;
    }

    /**
     * Client confirms the order (borrador → pendiente_pago).
     * Validates ownership before transition.
     */
    public function confirmOrder(int $ordenId, int $clienteId): array
    {
        $orden = $this->ordenes->findById($ordenId);

        if (!$orden) {
            throw new \RuntimeException('Orden no encontrada.');
        }
        if ((int)$orden['cliente_id'] !== $clienteId) {
            throw new \RuntimeException('No tienes permiso sobre esta orden.');
        }
        if ($orden['estado'] !== 'borrador') {
            throw new \RuntimeException("Esta orden ya está en estado '{$orden['estado']}' y no puede confirmarse.");
        }

        $this->ordenes->updateEstado($ordenId, 'pendiente_pago');
        $this->logs->log($ordenId, 'confirmada', 'Cliente confirmó la orden.', $clienteId);

        $orden   = $this->ordenes->findById($ordenId);
        $cliente = $this->clientes->findById($clienteId);
        $paquete = $this->paquetes->findById((int)$orden['paquete_id']);

        $this->email->sendOrderConfirmed($cliente['email'], $cliente['nombre'], $orden, $paquete);

        return $orden;
    }

    public function getClientOrders(int $clienteId): array
    {
        return $this->ordenes->findByClienteId($clienteId);
    }

    public function getOrder(int $ordenId, int $clienteId): array
    {
        $orden = $this->ordenes->findById($ordenId);
        if (!$orden || (int)$orden['cliente_id'] !== $clienteId) {
            throw new \RuntimeException('Orden no encontrada.');
        }
        return $orden;
    }
}
