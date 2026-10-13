<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Mesa
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? getDatabaseConnection();
    }

    public function obtenerTodas(): array
    {
        $sql = 'SELECT *
                FROM mesas
                ORDER BY CAST(numero_mesa AS UNSIGNED), numero_mesa';

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $idMesa): ?array
    {
        $sql = 'SELECT *
                FROM mesas
                WHERE id = :id
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idMesa]);

        $mesa = $stmt->fetch();

        return $mesa !== false ? $mesa : null;
    }

    public function actualizarEstado(int $idMesa, string $estado): bool
    {
        $estadoNormalizado = strtolower(trim($estado));
        $estadosPermitidos = ['libre', 'ocupada', 'reservada'];

        if (!in_array($estadoNormalizado, $estadosPermitidos, true)) {
            return false;
        }

        $sql = 'UPDATE mesas
                SET disponibilidad = :estado
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':estado' => $estadoNormalizado,
            ':id' => $idMesa,
        ]);
    }

    public function actualizarCapacidad(int $idMesa, int $capacidad): bool
    {
        $sql = 'UPDATE mesas
                SET capacidad = :capacidad
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':capacidad' => $capacidad,
            ':id' => $idMesa,
        ]);
    }
}
