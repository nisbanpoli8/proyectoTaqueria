<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Usuario
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? getDatabaseConnection();
    }

    public function buscarPorUsuario(string $usuario): ?array
    {
        $sql = 'SELECT u.*, r.nombre_rol
                FROM usuarios u
                INNER JOIN roles r ON r.id = u.id_rol
                WHERE u.usuario = :usuario
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':usuario' => $usuario]);

        $usuarioEncontrado = $stmt->fetch();

        return $usuarioEncontrado !== false ? $usuarioEncontrado : null;
    }

    public function crear(
        int $idRol,
        string $nombre,
        string $apellido,
        string $usuario,
        string $contrasenia,
        bool $activo = true
    ): int {
        $hash = password_hash($contrasenia, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO usuarios (id_rol, nombre, apellido, usuario, contrasenia, activo)
                VALUES (:id_rol, :nombre, :apellido, :usuario, :contrasenia, :activo)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_rol' => $idRol,
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':usuario' => $usuario,
            ':contrasenia' => $hash,
            ':activo' => $activo ? 1 : 0,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function editar(int $idUsuario, array $datos): bool
    {
        $campos = [];
        $parametros = [':id' => $idUsuario];

        if (isset($datos['id_rol'])) {
            $campos[] = 'id_rol = :id_rol';
            $parametros[':id_rol'] = (int) $datos['id_rol'];
        }

        if (isset($datos['nombre'])) {
            $campos[] = 'nombre = :nombre';
            $parametros[':nombre'] = trim((string) $datos['nombre']);
        }

        if (isset($datos['apellido'])) {
            $campos[] = 'apellido = :apellido';
            $parametros[':apellido'] = trim((string) $datos['apellido']);
        }

        if (isset($datos['usuario'])) {
            $campos[] = 'usuario = :usuario';
            $parametros[':usuario'] = trim((string) $datos['usuario']);
        }

        if (isset($datos['contrasenia'])) {
            $campos[] = 'contrasenia = :contrasenia';
            $parametros[':contrasenia'] = password_hash((string) $datos['contrasenia'], PASSWORD_DEFAULT);
        }

        if (isset($datos['activo'])) {
            $campos[] = 'activo = :activo';
            $parametros[':activo'] = (bool) $datos['activo'] ? 1 : 0;
        }

        if ($campos === []) {
            return false;
        }

        $sql = 'UPDATE usuarios SET ' . implode(', ', $campos) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($parametros);
    }

    public function desactivar(int $idUsuario): bool
    {
        $sql = 'UPDATE usuarios SET activo = 0 WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([':id' => $idUsuario]);
    }

    public function listar(): array
    {
        $sql = 'SELECT u.*, r.nombre_rol
                FROM usuarios u
                LEFT JOIN roles r ON r.id = u.id_rol
                ORDER BY u.id DESC';

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }
}
