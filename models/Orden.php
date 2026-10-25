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

    public function listarPendientes(): array
    {
        $sql = "SELECT o.id, o.id_mesa, o.id_usuario, o.tipo_pedido, o.estado, o.total, o.creado_en,
                       m.numero_mesa, m.capacidad, u.nombre AS mesero_nombre, u.apellido AS mesero_apellido
                FROM ordenes o
                INNER JOIN mesas m ON m.id = o.id_mesa
                INNER JOIN usuarios u ON u.id = o.id_usuario
                WHERE o.estado IN ('pendiente', 'en_preparacion')
                ORDER BY o.creado_en ASC";

        return $this->pdo->query($sql)->fetchAll();
    }

    public function actualizarEstado(int $idOrden, string $estado): bool
    {
        $estadoNormalizado = strtolower(trim($estado));
        $estadosPermitidos = ['pendiente', 'en_preparacion', 'listo', 'servido', 'pagado', 'cancelado'];

        if (!in_array($estadoNormalizado, $estadosPermitidos, true)) {
            return false;
        }

        $stmt = $this->pdo->prepare(
            'UPDATE ordenes
             SET estado = :estado
             WHERE id = :id'
        );

        return $stmt->execute([
            ':estado' => $estadoNormalizado,
            ':id' => $idOrden,
        ]);
    }

    public function obtenerActivaPorMesa(int $idMesa): ?array
    {
        $sql = 'SELECT o.*
                FROM ordenes o
                WHERE o.id_mesa = :id_mesa
                  AND o.estado IN (\'pendiente\', \'en_preparacion\', \'listo\', \'servido\')
                ORDER BY o.creado_en DESC
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_mesa' => $idMesa]);

        $orden = $stmt->fetch();

        return $orden !== false ? $orden : null;
    }

    public function obtenerDetalle(int $idOrden): array
    {
        $sql = 'SELECT d.cantidad, d.precio_unitario, d.subtotal, d.notas, p.nombre_producto
                FROM detalle_ordenes d
                INNER JOIN productos p ON p.id = d.id_producto
                WHERE d.id_orden = :id_orden
                ORDER BY d.id ASC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_orden' => $idOrden]);

        return $stmt->fetchAll();
    }

    public function listarListos(): array
    {
        $sql = "SELECT o.id, o.id_mesa, o.id_usuario, o.tipo_pedido, o.estado, o.total, o.creado_en,
                       m.numero_mesa, m.capacidad, u.nombre AS mesero_nombre, u.apellido AS mesero_apellido
                FROM ordenes o
                INNER JOIN mesas m ON m.id = o.id_mesa
                INNER JOIN usuarios u ON u.id = o.id_usuario
                WHERE o.estado = 'listo'
                ORDER BY o.creado_en ASC";

        return $this->pdo->query($sql)->fetchAll();
    }
}
