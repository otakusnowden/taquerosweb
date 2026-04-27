<?php
declare(strict_types=1);

namespace App\Services;

use App\DTOs\OrderDTO;
use App\Repositories\OrdenRepository;
use App\Repositories\OrdenAdjuntoRepository;
use App\Repositories\PaqueteRepository;
use App\Repositories\ClienteRepository;
use App\Repositories\OrdenLogRepository;

final class OrderService
{
    private const MAX_ATTACHMENT_SIZE = 10485760;
    private const MAX_ATTACHMENTS_PER_REQUEST = 8;
    private const ALLOWED_EXTENSIONS = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'txt', 'doc', 'docx',
        'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar',
    ];

    public function __construct(
        private readonly OrdenRepository    $ordenes  = new OrdenRepository(),
        private readonly OrdenAdjuntoRepository $adjuntos = new OrdenAdjuntoRepository(),
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

    public function updateOrderDetails(int $ordenId, int $clienteId, string $descripcion, array $files = []): array
    {
        $orden = $this->ordenes->findById($ordenId);

        if (!$orden || (int)$orden['cliente_id'] !== $clienteId) {
            throw new \RuntimeException('Orden no encontrada.');
        }

        if ($orden['estado'] === 'cancelado') {
            throw new \RuntimeException('No se puede editar una orden cancelada.');
        }

        $descripcion = trim($descripcion);
        if ($descripcion === '') {
            throw new \RuntimeException('Describe tu proyecto.');
        }

        $safeDescription = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');
        $this->ordenes->updateDescripcion($ordenId, $safeDescription);

        $uploadedCount = $this->storeAttachments($ordenId, $clienteId, $files);
        $logDetail = $uploadedCount > 0
            ? "Cliente actualizó la descripción y agregó {$uploadedCount} adjunto(s)."
            : 'Cliente actualizó la descripción.';
        $this->logs->log($ordenId, 'detalles_actualizados', $logDetail, $clienteId);

        $orden = $this->ordenes->findById($ordenId);
        $orden['adjuntos'] = $this->adjuntos->findByOrdenId($ordenId);

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
        $orders = $this->ordenes->findByClienteId($clienteId);
        $attachments = $this->adjuntos->findByOrdenIds(array_column($orders, 'id'));
        $byOrder = [];

        foreach ($attachments as $attachment) {
            $byOrder[(int)$attachment['orden_id']][] = $attachment;
        }

        foreach ($orders as &$order) {
            $order['adjuntos'] = $byOrder[(int)$order['id']] ?? [];
        }

        return $orders;
    }

    public function getOrder(int $ordenId, int $clienteId): array
    {
        $orden = $this->ordenes->findById($ordenId);
        if (!$orden || (int)$orden['cliente_id'] !== $clienteId) {
            throw new \RuntimeException('Orden no encontrada.');
        }
        $orden['adjuntos'] = $this->adjuntos->findByOrdenId($ordenId);
        return $orden;
    }

    public function storeAttachments(int $ordenId, int $clienteId, array $files = []): int
    {
        $normalized = $this->normalizeFiles($files['adjuntos'] ?? $files['attachments'] ?? null);
        if (empty($normalized)) {
            return 0;
        }

        if (count($normalized) > self::MAX_ATTACHMENTS_PER_REQUEST) {
            throw new \RuntimeException('Solo puedes subir hasta 8 archivos por solicitud.');
        }

        $uploadDir = APP_ROOT . '/uploads/order-attachments/' . $ordenId;
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
            throw new \RuntimeException('No se pudo preparar la carpeta de adjuntos.');
        }

        $publicBase = '/uploads/order-attachments/' . $ordenId;
        $stored = 0;

        foreach ($normalized as $file) {
            if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
                throw new \RuntimeException('No se pudo subir uno de los archivos.');
            }
            if ((int)$file['size'] > self::MAX_ATTACHMENT_SIZE) {
                throw new \RuntimeException('Cada adjunto debe pesar 10 MB o menos.');
            }

            $originalName = basename((string)$file['name']);
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
                throw new \RuntimeException("Tipo de archivo no permitido: {$extension}.");
            }

            $storedName = bin2hex(random_bytes(16)) . '.' . $extension;
            $target = $uploadDir . '/' . $storedName;

            if (!move_uploaded_file((string)$file['tmp_name'], $target)) {
                throw new \RuntimeException('No se pudo guardar uno de los adjuntos.');
            }

            $mimeType = $this->detectMimeType($target);
            $this->adjuntos->create([
                'orden_id' => $ordenId,
                'cliente_id' => $clienteId,
                'original_name' => $originalName,
                'stored_name' => $storedName,
                'file_path' => $publicBase . '/' . $storedName,
                'mime_type' => $mimeType,
                'file_size' => (int)$file['size'],
            ]);
            $stored++;
        }

        return $stored;
    }

    private function normalizeFiles(?array $files): array
    {
        if ($files === null || empty($files['name'])) {
            return [];
        }

        if (!is_array($files['name'])) {
            return [$files];
        }

        $normalized = [];
        foreach ($files['name'] as $index => $name) {
            $normalized[] = [
                'name' => $name,
                'type' => $files['type'][$index] ?? '',
                'tmp_name' => $files['tmp_name'][$index] ?? '',
                'error' => $files['error'][$index] ?? UPLOAD_ERR_NO_FILE,
                'size' => $files['size'][$index] ?? 0,
            ];
        }

        return $normalized;
    }

    private function detectMimeType(string $path): string
    {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $mime = finfo_file($finfo, $path);
                finfo_close($finfo);
                if (is_string($mime) && $mime !== '') {
                    return $mime;
                }
            }
        }

        return 'application/octet-stream';
    }
}
