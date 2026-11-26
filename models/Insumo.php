<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Insumo
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? getDatabaseConnection();
    }

    public function listar(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM insumos ORDER BY id DESC');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id, bool $bloquear = false): ?array
    {
        $sql = 'SELECT * FROM insumos WHERE id = :id LIMIT 1';

        if ($bloquear) {
            $sql .= ' FOR UPDATE';
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $insumo = $stmt->fetch();

        return $insumo !== false ? $insumo : null;
    }

    // Quitamos el $costoUnitario porque no está en la BD
    public function actualizarStock(int $id, float $stockActual): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE insumos
             SET stock_actual = :stock_actual
             WHERE id = :id'
        );

        return $stmt->execute([
            ':stock_actual' => $stockActual,
            ':id' => $id,
        ]);
    }

    public function obtenerAlertas(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM insumos
             WHERE stock_actual <= stock_minimo
             ORDER BY stock_actual ASC, nombre_insumo ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }
}