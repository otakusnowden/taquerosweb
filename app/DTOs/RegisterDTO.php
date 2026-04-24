<?php
declare(strict_types=1);

namespace App\DTOs;

final class RegisterDTO
{
    public readonly string $nombre;
    public readonly string $apellidos;
    public readonly string $telefono;
    public readonly string $email;
    public readonly string $password;
    public readonly int    $paqueteId;
    public readonly string $descripcion;

    /** @throws \InvalidArgumentException */
    public function __construct(array $data)
    {
        $errors = [];

        $nombre      = trim($data['nombre'] ?? '');
        $apellidos   = trim($data['apellidos'] ?? '');
        $telefono    = trim($data['telefono'] ?? '');
        $email       = strtolower(trim($data['email'] ?? ''));
        $password    = $data['password'] ?? '';
        $passwordRep = $data['password_confirmation'] ?? '';
        $paqueteId   = (int)($data['paquete_id'] ?? 0);
        $descripcion = trim($data['descripcion'] ?? '');

        if (empty($nombre) || strlen($nombre) < 2)         $errors['nombre']    = 'Nombre inválido (mín. 2 caracteres).';
        if (empty($apellidos) || strlen($apellidos) < 2)   $errors['apellidos'] = 'Apellidos inválidos.';
        if (!preg_match('/^\+?[0-9\s\-]{7,15}$/', $telefono)) $errors['telefono'] = 'Teléfono inválido.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))    $errors['email']     = 'Correo inválido.';
        if (strlen($password) < 8)                         $errors['password']  = 'La contraseña debe tener al menos 8 caracteres.';
        if ($password !== $passwordRep)                    $errors['password_confirmation'] = 'Las contraseñas no coinciden.';
        if ($paqueteId <= 0)                               $errors['paquete_id'] = 'Debes seleccionar un paquete.';
        if (empty($descripcion))                           $errors['descripcion'] = 'Describe tu proyecto.';

        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode($errors));
        }

        $this->nombre      = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        $this->apellidos   = htmlspecialchars($apellidos, ENT_QUOTES, 'UTF-8');
        $this->telefono    = $telefono;
        $this->email       = $email;
        $this->password    = $password;         // raw — service will hash
        $this->paqueteId   = $paqueteId;
        $this->descripcion = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');
    }
}
