<?php
declare(strict_types=1);

namespace App\DTOs;

final class OrderDTO
{
    public readonly int    $clienteId;
    public readonly int    $paqueteId;
    public readonly string $descripcion;

    public function __construct(array $data)
    {
        $errors = [];

        $clienteId   = (int)($data['cliente_id'] ?? 0);
        $paqueteId   = (int)($data['paquete_id'] ?? 0);
        $descripcion = trim($data['descripcion'] ?? '');

        if ($clienteId <= 0)    $errors['cliente_id']  = 'Cliente inválido.';
        if ($paqueteId <= 0)    $errors['paquete_id']  = 'Selecciona un paquete.';
        if (empty($descripcion)) $errors['descripcion'] = 'Describe tu proyecto.';

        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode($errors));
        }

        $this->clienteId   = $clienteId;
        $this->paqueteId   = $paqueteId;
        $this->descripcion = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');
    }
}
