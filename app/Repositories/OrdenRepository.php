<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class OrdenRepository
{
    public function findById(int $id): ?array
    {
        return Database::queryOne(
            'SELECT o.*, p.nombre AS paquete_nombre, p.precio AS paquete_precio, p.emoji,
                    c.nombre AS cliente_nombre, c.email AS cliente_email
             FROM ordenes o
             JOIN paquetes p ON p.id = o.paquete_id
             JOIN clientes c ON c.id = o.cliente_id
             WHERE o.id = ? LIMIT 1',
            [$id]
        );
    }

    /** All orders for a client, newest first */
    public function findByClienteId(int $clienteId): array
    {
        return Database::query(
            'SELECT o.*, p.nombre AS paquete_nombre, p.precio AS paquete_precio, p.emoji
             FROM ordenes o
             JOIN paquetes p ON p.id = o.paquete_id
             WHERE o.cliente_id = ?
             ORDER BY o.created_at DESC',
            [$clienteId]
        );
    }

    public function create(array $data): int
    {
        return Database::insert(
            'INSERT INTO ordenes (cliente_id, paquete_id, descripcion, estado, created_at)
             VALUES (?, ?, ?, "borrador", NOW())',
            [$data['cliente_id'], $data['paquete_id'], $data['descripcion']]
        );
    }

    public function updateEstado(int $id, string $estado): void
    {
        Database::execute(
            'UPDATE ordenes SET estado = ?, updated_at = NOW() WHERE id = ?',
            [$estado, $id]
        );
    }

    public function attachMpPreference(int $id, string $preferenceId): void
    {
        Database::execute(
            'UPDATE ordenes SET mp_preference_id = ?, updated_at = NOW() WHERE id = ?',
            [$preferenceId, $id]
        );
    }
}
