<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class PaqueteRepository
{
    public function findAll(): array
    {
        return Database::query(
            'SELECT * FROM paquetes WHERE activo = 1 ORDER BY precio ASC'
        );
    }

    public function findById(int $id): ?array
    {
        return Database::queryOne(
            'SELECT * FROM paquetes WHERE id = ? AND activo = 1 LIMIT 1',
            [$id]
        );
    }
}
