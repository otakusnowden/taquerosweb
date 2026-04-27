<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

final class ClienteRepository
{
    public function findAll(): array
    {
        return Database::query(
            'SELECT id, nombre, apellidos, telefono, email, created_at
             FROM clientes
             ORDER BY created_at DESC'
        );
    }

    public function findByEmail(string $email): ?array
    {
        return Database::queryOne(
            'SELECT * FROM clientes WHERE email = ? LIMIT 1',
            [$email]
        );
    }

    public function findById(int $id): ?array
    {
        return Database::queryOne(
            'SELECT * FROM clientes WHERE id = ? LIMIT 1',
            [$id]
        );
    }

    public function findByVerificationToken(string $token): ?array
    {
        return Database::queryOne(
            'SELECT * FROM clientes WHERE verification_token = ? AND email_verified_at IS NULL LIMIT 1',
            [$token]
        );
    }

    public function create(array $data): int
    {
        return Database::insert(
            'INSERT INTO clientes
                (nombre, apellidos, telefono, email, password_hash, verification_token, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())',
            [
                $data['nombre'],
                $data['apellidos'],
                $data['telefono'],
                $data['email'],
                $data['password_hash'],
                $data['verification_token'],
            ]
        );
    }

    public function markEmailVerified(int $id): void
    {
        Database::execute(
            'UPDATE clientes SET email_verified_at = NOW(), verification_token = NULL WHERE id = ?',
            [$id]
        );
    }

    public function updateLastLogin(int $id): void
    {
        Database::execute(
            'UPDATE clientes SET last_login_at = NOW() WHERE id = ?',
            [$id]
        );
    }
}
