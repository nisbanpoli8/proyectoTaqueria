<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Mesa.php';
require_once __DIR__ . '/../models/Orden.php';


class CocinaController
{
    public function index(): void
    {
        $ordenModel = new Orden();
        $ordenes = $this->agregarDetalles($ordenModel->listarPendientes(), $ordenModel);
        $mesas = (new Mesa())->obtenerTodas();
        $success = (string) ($_SESSION['flash_success'] ?? '');
        $error = (string) ($_SESSION['flash_error'] ?? '');
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require __DIR__ . '/../views/cocina.php';
    }

    public function actualizarEstado(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=cocina');
            exit;
        }

        $idOrden = (int) ($_POST['id_orden'] ?? 0);
        $estado = strtolower(trim((string) ($_POST['estado'] ?? '')));
        $estadosPermitidos = ['en_preparacion', 'listo', 'servido'];

        if ($idOrden <= 0 || !in_array($estado, $estadosPermitidos, true)) {
            $_SESSION['flash_error'] = 'No fue posible actualizar el estado de la orden.';
            header('Location: index.php?action=cocina');
            exit;
        }

        if (!(new Orden())->actualizarEstado($idOrden, $estado)) {
            $_SESSION['flash_error'] = 'No fue posible actualizar el estado de la orden.';
            header('Location: index.php?action=cocina');
            exit;
        }

        $_SESSION['flash_success'] = 'El estado de la orden se actualizó correctamente.';
        header('Location: index.php?action=cocina');
        exit;
    }

    public function listos(): void
    {
        $ordenModel = new Orden();
        $ordenes = $this->agregarDetalles($ordenModel->listarListos(), $ordenModel);
        $success = (string) ($_SESSION['flash_success'] ?? '');
        $error = (string) ($_SESSION['flash_error'] ?? '');
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require __DIR__ . '/../views/cocina_listos.php';
    }

    private function agregarDetalles(array $ordenes, Orden $ordenModel): array
    {
        foreach ($ordenes as &$orden) {
            $orden['detalle'] = $ordenModel->obtenerDetalle((int) $orden['id']);
        }
        unset($orden);

        return $ordenes;
    }
}
