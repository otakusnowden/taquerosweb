<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class PagoRepository
{
    public function findByMpPaymentId(string $mpPaymentId): ?array
    {
        return Database::queryOne(
            'SELECT * FROM pagos WHERE mp_payment_id = ? LIMIT 1',
            [$mpPaymentId]
        );
    }

    public function findByOrdenId(int $ordenId): array
    {
        return Database::query(
            'SELECT * FROM pagos WHERE orden_id = ? ORDER BY created_at DESC',
            [$ordenId]
        );
    }

    public function create(array $data): int
    {
        return Database::insert(
            'INSERT INTO pagos
                (orden_id, mp_payment_id, mp_status, monto, metodo_pago, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())',
            [
                $data['orden_id'],
                $data['mp_payment_id'],
                $data['mp_status'],
                $data['monto'],
                $data['metodo_pago'] ?? 'mercadopago',
            ]
        );
    }

    public function updateStatus(int $id, string $status): void
    {
        Database::execute(
            'UPDATE pagos SET mp_status = ?, updated_at = NOW() WHERE id = ?',
            [$status, $id]
        );
    }
}
