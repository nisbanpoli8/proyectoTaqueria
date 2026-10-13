<?php
// config/init_admin.php

declare(strict_types=1);

require_once __DIR__ . '/database.php';

function ensureInitialAdmin(): void
{
    $pdo = getDatabaseConnection();

    // Datos del admin inicial (desde .env con valores por defecto)
    $username = getEnvValue('APP_ADMIN_USERNAME', 'admin_roy');
    $plainPassword = getEnvValue('APP_ADMIN_PASSWORD', 'admin123');
    $nombre = getEnvValue('APP_ADMIN_NOMBRE', 'Roy');
    $apellido = getEnvValue('APP_ADMIN_APELLIDO', 'Admin');

    // Verificar si el admin ya existe
    $existingUser = $pdo->prepare('SELECT id FROM usuarios WHERE usuario = :usuario LIMIT 1');
    $existingUser->execute([':usuario' => $username]);

    if ($existingUser->fetch() !== false) {
        // El admin ya existe, no hacer nada
        return;
    }

    // Obtener el ID del rol "Administrador"
    //CORREGIDO: la columna se llama 'nombre_rol', no 'nombre'
    $roleQuery = $pdo->prepare('SELECT id FROM roles WHERE nombre_rol = :nombre LIMIT 1');
    $roleQuery->execute([':nombre' => 'Administrador']);
    $roleId = $roleQuery->fetchColumn();

    if ($roleId === false || $roleId === null) {
        $roleId = 1; // Fallback al ID 1
    }

    // Encriptar la contraseña
    $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

    // Insertar el admin
    //CORREGIDO: 'contrasenia' en lugar de 'password', y se agregaron 'nombre' y 'apellido'
    $insertUser = $pdo->prepare(
        'INSERT INTO usuarios (usuario, contrasenia, nombre, apellido, id_rol, activo) 
         VALUES (:usuario, :contrasenia, :nombre, :apellido, :id_rol, 1)'
    );

    $insertUser->execute([
        ':usuario' => $username,
        ':contrasenia' => $hash,
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':id_rol' => (int) $roleId,
    ]);
}

// Ejecutar la función
ensureInitialAdmin();