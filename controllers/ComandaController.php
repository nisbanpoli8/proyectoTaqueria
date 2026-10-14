<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Orden.php';

class ComandaController
{
    public function index(): void
    {
        $idMesa = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($idMesa <= 0) {
            header('Location: index.php?action=mesas');
            exit;
        }

        $productos = (new Producto())->listar();
        $comanda = $_SESSION['comanda'][$idMesa] ?? ['items' => [], 'notas' => ''];
        $items = [];
        $total = 0.0;

        foreach ($comanda['items'] as $idProducto => $item) {
            $producto = null;

            foreach ($productos as $productoActual) {
                if ((int) $productoActual['id'] === (int) $idProducto) {
                    $producto = $productoActual;
                    break;
                }
            }

            if ($producto === null) {
                continue;
            }

            $cantidad = max(1, (int) ($item['cantidad'] ?? 1));
            $precio = (float) ($producto['precio'] ?? 0);
            $subtotal = $cantidad * $precio;
            $total += $subtotal;

            $items[] = [
                'id' => (int) $idProducto,
                'nombre' => (string) $producto['nombre_producto'],
                'cantidad' => $cantidad,
                'precio' => $precio,
                'subtotal' => $subtotal,
            ];
        }

        require_once __DIR__ . '/../views/comanda.php';
    }

    public function agregarProducto(): void
    {
        $idMesa = isset($_POST['id_mesa']) ? (int) $_POST['id_mesa'] : 0;
        $idProducto = isset($_POST['id_producto']) ? (int) $_POST['id_producto'] : 0;
        $cantidad = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : 1;

        if ($idMesa <= 0 || $idProducto <= 0 || $cantidad <= 0) {
            $this->redirigirComanda($idMesa);
        }

        $producto = (new Producto())->listar();
        $productoEncontrado = null;

        foreach ($producto as $item) {
            if ((int) $item['id'] === $idProducto) {
                $productoEncontrado = $item;
                break;
            }
        }

        if ($productoEncontrado === null) {
            $this->redirigirComanda($idMesa);
        }

        if (!isset($_SESSION['comanda'])) {
            $_SESSION['comanda'] = [];
        }

        if (!isset($_SESSION['comanda'][$idMesa])) {
            $_SESSION['comanda'][$idMesa] = ['items' => [], 'notas' => ''];
        }

        $key = (string) $idProducto;
        $actual = $_SESSION['comanda'][$idMesa]['items'][$key] ?? ['cantidad' => 0, 'precio' => (float) $productoEncontrado['precio']];
        $actual['cantidad'] = (int) $actual['cantidad'] + $cantidad;
        $actual['precio'] = (float) $productoEncontrado['precio'];
        $_SESSION['comanda'][$idMesa]['items'][$key] = $actual;

        if (isset($_POST['notas'])) {
            $_SESSION['comanda'][$idMesa]['notas'] = trim((string) $_POST['notas']);
        }

        $this->redirigirComanda($idMesa);
    }

    public function eliminarProducto(): void
    {
        $idMesa = isset($_POST['id_mesa']) ? (int) $_POST['id_mesa'] : 0;
        $idProducto = isset($_POST['id_producto']) ? (int) $_POST['id_producto'] : 0;
        $cantidad = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : 1;

        if ($idMesa <= 0 || $idProducto <= 0) {
            $this->redirigirComanda($idMesa);
        }

        if (!isset($_SESSION['comanda'][$idMesa]['items'])) {
            $this->redirigirComanda($idMesa);
        }

        $key = (string) $idProducto;
        $itemActual = $_SESSION['comanda'][$idMesa]['items'][$key] ?? null;

        if ($itemActual === null) {
            $this->redirigirComanda($idMesa);
        }

        $nuevaCantidad = max(0, (int) $itemActual['cantidad'] - ($cantidad > 0 ? $cantidad : 1));

        if ($nuevaCantidad <= 0) {
            unset($_SESSION['comanda'][$idMesa]['items'][$key]);
        } else {
            $_SESSION['comanda'][$idMesa]['items'][$key]['cantidad'] = $nuevaCantidad;
        }

        $this->redirigirComanda($idMesa);
    }

    public function enviarACocina(): void
    {
        $idMesa = isset($_POST['id_mesa']) ? (int) $_POST['id_mesa'] : 0;

        if ($idMesa <= 0) {
            header('Location: index.php?action=mesas');
            exit;
        }

        if (!isset($_SESSION['comanda'][$idMesa]['items']) || count($_SESSION['comanda'][$idMesa]['items']) === 0) {
            $_SESSION['flash_error'] = 'La comanda está vacía.';
            header('Location: index.php?action=comanda&id=' . $idMesa);
            exit;
        }

        $productos = (new Producto())->listar();
        $orden = new Orden();
        $idOrden = $orden->crear($idMesa, (int) ($_SESSION['usuario_id'] ?? 0), 'COMEDOR', 'PENDIENTE');
        $observaciones = trim((string) ($_POST['notas'] ?? $_SESSION['comanda'][$idMesa]['notas'] ?? ''));

        foreach ($_SESSION['comanda'][$idMesa]['items'] as $idProducto => $item) {
            $precio = 0.0;

            foreach ($productos as $producto) {
                if ((int) $producto['id'] === (int) $idProducto) {
                    $precio = (float) $producto['precio'];
                    break;
                }
            }

            if ($precio <= 0 || (int) $item['cantidad'] <= 0) {
                continue;
            }

            $orden->agregarDetalle(
                $idOrden,
                (int) $idProducto,
                (int) $item['cantidad'],
                $precio,
                $observaciones
            );
        }

        $orden->calcularTotal($idOrden);
        unset($_SESSION['comanda'][$idMesa]);
        $_SESSION['mesas_estado'][$idMesa] = 'OCUPADA';
        $_SESSION['flash_success'] = 'Comanda enviada a la cocina.';

        header('Location: index.php?action=mesas');
        exit;
    }

    private function redirigirComanda(int $idMesa): void
    {
        if ($idMesa > 0) {
            header('Location: index.php?action=comanda&id=' . $idMesa);
        } else {
            header('Location: index.php?action=mesas');
        }

        exit;
    }
}
