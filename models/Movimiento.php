<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Movimiento
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? getDatabaseConnection();
    }

    public function registrarMovimiento(
        int $idInsumo,
        string $tipoMovimiento,
        float $cantidad,
        float $stockResultante, // Nuevo parámetro
        string $motivo,
        int $idUsuario
    ): bool {
        // Añadimos stock_resultante y fecha
        $stmt = $this->pdo->prepare(
            'INSERT INTO movimientos (id_insumo, tipo_movimiento, cantidad, stock_resultante, motivo, id_usuario, fecha)
             VALUES (:id_insumo, :tipo_movimiento, :cantidad, :stock_resultante, :motivo, :id_usuario, NOW())'
        );

        return $stmt->execute([
            ':id_insumo' => $idInsumo,
            ':tipo_movimiento' => $tipoMovimiento,
            ':cantidad' => $cantidad,
            ':stock_resultante' => $stockResultante,
            ':motivo' => $motivo,
            ':id_usuario' => $idUsuario,
        ]);
    }
}