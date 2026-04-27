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

    /** All orders with client and package details for admin views */
    public function findAllForAdmin(): array
    {
        return Database::query(
            'SELECT o.*,
                    p.nombre AS paquete_nombre, p.precio AS paquete_precio, p.emoji,
                    c.nombre AS cliente_nombre, c.apellidos AS cliente_apellidos,
                    c.email AS cliente_email, c.telefono AS cliente_telefono,
                    c.created_at AS cliente_created_at
             FROM ordenes o
             JOIN paquetes p ON p.id = o.paquete_id
             JOIN clientes c ON c.id = o.cliente_id
             ORDER BY o.created_at DESC'
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

    public function updateDescripcion(int $id, string $descripcion): void
    {
        Database::execute(
            'UPDATE ordenes SET descripcion = ?, updated_at = NOW() WHERE id = ?',
            [$descripcion, $id]
        );
    }

    public function updateAdminFields(int $id, array $data): void
    {
        Database::execute(
            'UPDATE ordenes
             SET paquete_id = ?,
                 cliente_id = ?,
                 descripcion = ?,
                 estado = ?,
                 mp_preference_id = ?,
                 created_at = ?,
                 updated_at = NOW()
             WHERE id = ?',
            [
                $data['paquete_id'],
                $data['cliente_id'],
                $data['descripcion'],
                $data['estado'],
                $data['mp_preference_id'] ?: null,
                $data['created_at'],
                $id,
            ]
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
