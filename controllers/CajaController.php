<?php
// controllers/CajaController.php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Orden.php';
require_once __DIR__ . '/../models/Mesa.php';

class CajaController
{

    public function lista(): void
    {
        $pdo = getDatabaseConnection();
        // Buscamos las mesas que están reservadas (pidieron cuenta)
        $stmt = $pdo->query("SELECT id, numero_mesa, capacidad FROM mesas WHERE disponibilidad = 'reservada'");
        $mesasPendientes = $stmt->fetchAll();

        require __DIR__ . '/../views/caja_lista.php';
    }

    public function index(): void
    {
        $idMesa = (int) ($_GET['id_mesa'] ?? 0);

        if ($idMesa <= 0) {
            header('Location: index.php?action=mesas');
            exit;
        }

        $ordenModel = new Orden();
        $orden = $ordenModel->obtenerActivaPorMesa($idMesa);

        if ($orden === null) {
            $detalle = [];
            $subtotal = 0.0;
            $total = 0.0;
            $idOrden = 0;
        } else {
            $idOrden = (int) $orden['id'];
            $detalle = $ordenModel->obtenerDetalle($idOrden);
            $subtotal = 0.0;

            foreach ($detalle as $producto) {
                $subtotal += (float) ($producto['subtotal'] ?? 0.0);
            }

            $total = max($subtotal, (float) ($orden['total'] ?? 0.0));
        }

        $id_mesa = $idMesa;
        $id_orden = $idOrden;

        require __DIR__ . '/../views/caja.php';
    }

    public function procesarPago(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=mesas');
            exit;
        }

        $idOrden = (int) ($_POST['id_orden'] ?? 0);
        $idMesa = (int) ($_POST['id_mesa'] ?? 0);
        $metodoPagoTexto = strtolower(trim((string) ($_POST['metodo_pago'] ?? '')));
        $montoRecibido = (float) ($_POST['monto_recibido'] ?? 0);
        $total = (float) ($_POST['total'] ?? 0);
        $metodosPermitidos = ['efectivo', 'tarjeta', 'transferencia'];

        // ✅ CORREGIDO: Ahora valida $metodoPagoTexto
        if ($idOrden <= 0 || $idMesa <= 0 || !in_array($metodoPagoTexto, $metodosPermitidos, true) || $total < 0) {
            $_SESSION['flash_error'] = 'No fue posible procesar el pago.';
            header('Location: index.php?action=caja&id_mesa=' . $idMesa);
            exit;
        }

        // ✅ CORREGIDO: Ahora usa $metodoPagoTexto
        if ($metodoPagoTexto === 'efectivo' && $montoRecibido < $total) {
            $_SESSION['flash_error'] = 'El monto recibido es menor al total.';
            header('Location: index.php?action=caja&id_mesa=' . $idMesa);
            exit;
        }

        // Calcular cambio
        $cambio = round(max($montoRecibido - $total, 0.0), 2);
        
        $pdo = getDatabaseConnection();

        $sqlPago = 'INSERT INTO pagos (id_orden, metodo_pago, monto_recibido, cambio)
                    VALUES (:id_orden, :metodo_pago, :monto_recibido, :cambio)';
        
        $stmtPago = $pdo->prepare($sqlPago);
        $stmtPago->execute([
            ':id_orden' => $idOrden,
            ':metodo_pago' => 'efectivo',
            ':monto_recibido' => number_format($montoRecibido, 2, '.', ''),
            ':cambio' => number_format($cambio, 2, '.', ''),
        ]);

         // Actualizar estado de la orden a 'pagado'
        $ordenModel = new Orden();
        if (!$ordenModel->actualizarEstado($idOrden, 'pagado')) {
            $_SESSION['flash_error'] = 'No fue posible cerrar la orden.';
            header('Location: index.php?action=caja&id_mesa=' . $idMesa);
            exit;
        }

        // Liberar la mesa
        $mesaModel = new Mesa();
        if (!$mesaModel->actualizarEstado($idMesa, 'libre')) {
            $_SESSION['flash_error'] = 'No fue posible liberar la mesa.';
            header('Location: index.php?action=caja&id_mesa=' . $idMesa);
            exit;
        }

        $_SESSION['flash_success'] = 'Pago procesado y mesa liberada';
        header('Location: index.php?action=caja_panel');
        exit;
    }
}
?>