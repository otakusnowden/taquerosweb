<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class OrdenAdjuntoRepository
{
    public function create(array $data): int
    {
        return Database::insert(
            'INSERT INTO orden_adjuntos
                (orden_id, cliente_id, original_name, stored_name, file_path, mime_type, file_size, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW())',
            [
                $data['orden_id'],
                $data['cliente_id'],
                $data['original_name'],
                $data['stored_name'],
                $data['file_path'],
                $data['mime_type'],
                $data['file_size'],
            ]
        );
    }

    public function findByOrdenId(int $ordenId): array
    {
        return Database::query(
            'SELECT id, orden_id, original_name, file_path, mime_type, file_size, created_at
             FROM orden_adjuntos
             WHERE orden_id = ?
             ORDER BY created_at DESC',
            [$ordenId]
        );
    }

    public function findByOrdenIds(array $ordenIds): array
    {
        $ordenIds = array_values(array_filter(array_map('intval', $ordenIds)));
        if (empty($ordenIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ordenIds), '?'));

        return Database::query(
            "SELECT id, orden_id, original_name, file_path, mime_type, file_size, created_at
             FROM orden_adjuntos
             WHERE orden_id IN ($placeholders)
             ORDER BY created_at DESC",
            $ordenIds
        );
    }
}
