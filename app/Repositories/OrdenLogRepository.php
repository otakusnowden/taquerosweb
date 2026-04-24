<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class OrdenLogRepository
{
    public function log(int $ordenId, string $accion, ?string $detalle = null, ?int $clienteId = null): void
    {
        Database::execute(
            'INSERT INTO orden_logs (orden_id, cliente_id, accion, detalle, ip, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())',
            [
                $ordenId,
                $clienteId,
                $accion,
                $detalle,
                $_SERVER['REMOTE_ADDR'] ?? null,
            ]
        );
    }

    public function findByOrdenId(int $ordenId): array
    {
        return Database::query(
            'SELECT * FROM orden_logs WHERE orden_id = ? ORDER BY created_at ASC',
            [$ordenId]
        );
    }
}
