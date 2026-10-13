<?php
// models/Orden.php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Orden
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? getDatabaseConnection();
    }

    public function crear(
        int $idMesa,
        int $idUsuario,
        string $tipoPedido = 'comedor',
        string $estado = 'pendiente'
    ): int {
        $tipoPedidoNormalizado = strtolower(trim($tipoPedido));
        $estadoNormalizado = strtolower(trim($estado));
        
        $tiposPermitidos = ['comedor', 'para_llevar', 'domicilio'];
        $estadosPermitidos = ['pendiente', 'en_preparacion', 'listo', 'servido', 'pagado', 'cancelado'];

        if (!in_array($tipoPedidoNormalizado, $tiposPermitidos, true)) {
            throw new InvalidArgumentException('El tipo de pedido no es válido.');
        }

        if (!in_array($estadoNormalizado, $estadosPermitidos, true)) {
            throw new InvalidArgumentException('El estado de la orden no es válido.');
        }

        $sql = 'INSERT INTO ordenes (id_mesa, id_usuario, tipo_pedido, estado, total)
                VALUES (:id_mesa, :id_usuario, :tipo_pedido, :estado, 0.00)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_mesa' => $idMesa,
            ':id_usuario' => $idUsuario,
            ':tipo_pedido' => $tipoPedidoNormalizado,
            ':estado' => $estadoNormalizado,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function agregarDetalle(
        int $idOrden,
        int $idProducto,
        int $cantidad,
        float $precioUnitario,
        string $notas = ''
    ): int {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException('La cantidad debe ser mayor a cero.');
        }

        if ($precioUnitario < 0) {
            throw new InvalidArgumentException('El precio unitario no puede ser negativo.');
        }

        $subtotal = $cantidad * $precioUnitario;

        $sql = 'INSERT INTO detalle_ordenes (id_orden, id_producto, cantidad, precio_unitario, subtotal, notas)
                VALUES (:id_orden, :id_producto, :cantidad, :precio_unitario, :subtotal, :notas)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_orden' => $idOrden,
            ':id_producto' => $idProducto,
            ':cantidad' => $cantidad,
            ':precio_unitario' => $precioUnitario,
            ':subtotal' => $subtotal,
            ':notas' => $notas,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function calcularTotal(int $idOrden): float
    {
        $sql = 'SELECT COALESCE(SUM(subtotal), 0) AS total
                FROM detalle_ordenes
                WHERE id_orden = :id_orden';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_orden' => $idOrden]);

        $total = (float) $stmt->fetchColumn();

        $updateSql = 'UPDATE ordenes SET total = :total WHERE id = :id';
        $updateStmt = $this->pdo->prepare($updateSql);
        $updateStmt->execute([
            ':total' => $total,
            ':id' => $idOrden,
        ]);

        return $total;
    }

    public function obtenerPorMesa(int $idMesa): array
    {
        $sql = 'SELECT *
                FROM ordenes
                WHERE id_mesa = :id_mesa
                ORDER BY creado_en DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_mesa' => $idMesa]);

        return $stmt->fetchAll();
    }
}
