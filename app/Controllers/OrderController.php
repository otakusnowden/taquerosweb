<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Response;
use App\DTOs\OrderDTO;
use App\Services\OrderService;
use App\Services\MercadoPagoService;
use App\Repositories\PaqueteRepository;

final class OrderController
{
    public function __construct(
        private readonly OrderService       $service  = new OrderService(),
        private readonly MercadoPagoService $mp       = new MercadoPagoService(),
        private readonly PaqueteRepository  $paquetes = new PaqueteRepository(),
    ) {}

    /** GET /api/orders — list current user's orders */
    public function index(): never
    {
        $user = Auth::user() ?? Response::error('No autenticado.', 401);
        $orders = $this->service->getClientOrders((int)$user['id']);
        Response::success($orders);
    }

    /** POST /api/orders — create new draft order */
    public function store(): never
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') Response::error('Method not allowed.', 405);
        $user = Auth::user() ?? Response::error('No autenticado.', 401);

        $body = $this->parseBody();
        $body['cliente_id'] = $user['id'];

        try {
            $dto   = new OrderDTO($body);
            $orden = $this->service->createOrder($dto);
            if (!empty($_FILES)) {
                $this->service->storeAttachments((int)$orden['id'], (int)$user['id'], $_FILES);
                $orden = $this->service->getOrder((int)$orden['id'], (int)$user['id']);
            }
            Response::success($orden, 'Orden creada correctamente.', 201);
        } catch (\InvalidArgumentException $e) {
            Response::error('Datos inválidos.', 422, json_decode($e->getMessage(), true) ?? []);
        } catch (\RuntimeException $e) {
            Response::error($e->getMessage(), 400);
        }
    }

    /** POST /api/orders/{id}/details */
    public function updateDetails(int $id): never
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') Response::error('Method not allowed.', 405);
        $user = Auth::user() ?? Response::error('No autenticado.', 401);

        $body = $this->parseBody();

        try {
            $orden = $this->service->updateOrderDetails(
                $id,
                (int)$user['id'],
                (string)($body['descripcion'] ?? ''),
                $_FILES
            );
            Response::success($orden, 'Detalles de la orden actualizados.');
        } catch (\RuntimeException $e) {
            Response::error($e->getMessage(), 400);
        }
    }

    /** POST /api/orders/{id}/confirm */
    public function confirm(int $id): never
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') Response::error('Method not allowed.', 405);
        $user = Auth::user() ?? Response::error('No autenticado.', 401);

        try {
            $orden = $this->service->confirmOrder($id, (int)$user['id']);
            Response::success($orden, 'Orden confirmada. Procede al pago.');
        } catch (\RuntimeException $e) {
            Response::error($e->getMessage(), 400);
        }
    }

    /** POST /api/orders/{id}/pay — create MP preference */
    public function pay(int $id): never
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') Response::error('Method not allowed.', 405);
        $user = Auth::user() ?? Response::error('No autenticado.', 401);

        try {
            $result = $this->mp->createPreference($id, (int)$user['id']);
            Response::success($result, 'Preferencia de pago creada.');
        } catch (\RuntimeException $e) {
            Response::error($e->getMessage(), 400);
        }
    }

    /**
     * POST /api/orders/{id}/spei-notify
     * Cliente notifica que ya realizó la transferencia SPEI.
     * Cambia estado a 'revision', registra el pago como pending y notifica al admin.
     */
    public function notifySpei(int $id): never
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') Response::error('Method not allowed.', 405);
        $user = Auth::user() ?? Response::error('No autenticado.', 401);

        try {
            $orden = $this->service->notifySpeiTransfer($id, (int)$user['id']);
            Response::success([
                'orden'    => $orden,
                'concepto' => $this->service->formatSpeiConcepto($id),
            ], 'Notificación de transferencia recibida.');
        } catch (\RuntimeException $e) {
            Response::error($e->getMessage(), 400);
        }
    }

    /** GET /api/packages — list available packages */
    public function packages(): never
    {
        $pkgs = $this->paquetes->findAll();
        Response::success($pkgs);
    }

    private function parseBody(): array
    {
        $ct = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($ct, 'application/json')) {
            return json_decode(file_get_contents('php://input'), true) ?? [];
        }
        return $_POST;
    }
}
