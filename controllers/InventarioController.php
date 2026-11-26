<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Insumo.php';
require_once __DIR__ . '/../models/Movimiento.php';

class InventarioController
{
    private PDO $pdo;
    private Insumo $insumoModel;
    private Movimiento $movimientoModel;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? getDatabaseConnection();
        $this->insumoModel = new Insumo($this->pdo);
        $this->movimientoModel = new Movimiento($this->pdo);
    }

    public function index(): void
    {
        $insumos = $this->insumoModel->listar();
        require __DIR__ . '/../views/inventario/lista.php';
    }

        // 1. Mostrar el formulario de nuevo insumo
    public function nuevo(): void
    {
        require __DIR__ . '/../views/inventario/nuevo.php';
    }

    // 2. Guardar el insumo en la BD
    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $unidad = trim($_POST['unidad_medida'] ?? '');
            $stockInicial = (float)($_POST['stock_inicial'] ?? 0);
            $stockMinimo = (float)($_POST['stock_minimo'] ?? 0);

            // Validación (Criterio de aceptación HU-06)
            if (empty($nombre) || empty($unidad) || $stockInicial < 0 || $stockMinimo < 0) {
                $_SESSION['flash_error'] = 'Todos los campos obligatorios deben estar llenos.';
                header('Location: index.php?action=inventario_nuevo');
                exit;
            }

            try {
                $this->pdo->beginTransaction();

                // Insertamos el insumo
                $sql = "INSERT INTO insumos (nombre_insumo, unidad_medida, stock_actual, stock_minimo) 
                        VALUES (:nombre, :unidad, :stock, :minimo)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':unidad' => $unidad,
                    ':stock' => $stockInicial,
                    ':minimo' => $stockMinimo
                ]);

                $idInsumo = (int)$this->pdo->lastInsertId();
                $idUsuario = (int)($_SESSION['usuario_id'] ?? 1); // Fallback al admin

                // REGLA DE NEGOCIO: Si el stock inicial es mayor a 0, registramos el movimiento de entrada inicial
                if ($stockInicial > 0) {
                    $sqlMov = "INSERT INTO movimientos (id_usuario, id_insumo, tipo_movimiento, cantidad, stock_resultante, motivo, fecha) 
                               VALUES (:id_usuario, :id_insumo, 'entrada', :cantidad, :stock_resultante, 'Ajuste', NOW())";
                    $stmtMov = $this->pdo->prepare($sqlMov);
                    $stmtMov->execute([
                        ':id_usuario' => $idUsuario,
                        ':id_insumo' => $idInsumo,
                        ':cantidad' => $stockInicial,
                        ':stock_resultante' => $stockInicial
                    ]);
                }

                $this->pdo->commit();
                $_SESSION['flash_success'] = 'Insumo registrado correctamente.';
                header('Location: index.php?action=inventario');
                exit;

            } catch (Throwable $e) {
                $this->pdo->rollBack();
                error_log($e->__toString());
                $_SESSION['flash_error'] = 'Error al registrar el insumo. Inténtalo de nuevo.';
                header('Location: index.php?action=inventario_nuevo');
                exit;
            }
        }
    }


    public function editar(int $id): void
    {
        $insumo = $id > 0 ? $this->insumoModel->obtenerPorId($id) : null;
        $error = null;

        if ($insumo === null) {
            http_response_code($id > 0 ? 404 : 400);
            $error = 'No se encontró el insumo solicitado.';
        }

        require __DIR__ . '/../views/inventario/editar.php';
    }

    public function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            return;
        }

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $stockRaw = $_POST['stock_actual'] ?? null;
        $motivo = $_POST['motivo'] ?? null;
        $idUsuario = (int) ($_SESSION['usuario_id'] ?? $_SESSION['idUsuario'] ?? 1); // 1 por defecto si falla la sesión
        
        $motivos = [
            'Reabastecimiento' => 'entrada',
            'Merma' => 'salida',
            'Ajuste' => 'ajuste',
        ];

        // Validaciones básicas (sin costo unitario)
        if (
            $id === false
            || $id <= 0
            || !is_scalar($stockRaw)
            || !is_numeric((string) $stockRaw)
            || !is_string($motivo)
            || !isset($motivos[$motivo])
        ) {
            $_SESSION['flash_error'] = 'Los datos enviados no son válidos.';
            header('Location: index.php?action=inventario');
            exit;
        }

        $stockActual = (float) $stockRaw;

        if (!is_finite($stockActual) || $stockActual < 0) {
            $_SESSION['flash_error'] = 'El stock debe ser un valor válido y no negativo.';
            header('Location: index.php?action=inventario_editar&id=' . $id);
            exit;
        }

        try {
            $this->pdo->beginTransaction();
            $insumo = $this->insumoModel->obtenerPorId((int) $id, true);

            if ($insumo === null) {
                throw new RuntimeException('El insumo ya no existe.');
            }

            $stockAnterior = (float) $insumo['stock_actual'];

            if (
                ($motivo === 'Reabastecimiento' && $stockActual < $stockAnterior)
                || ($motivo === 'Merma' && $stockActual > $stockAnterior)
            ) {
                $this->pdo->rollBack();
                $_SESSION['flash_error'] = 'El motivo seleccionado no corresponde al cambio de stock.';
                header('Location: index.php?action=inventario_editar&id=' . $id);
                exit;
            }

            $tipoMovimiento = $motivos[$motivo];
            $cantidad = abs($stockActual - $stockAnterior);

            // Actualizar solo el stock (ya que no hay costo_unitario)
            if (!$this->insumoModel->actualizarStock((int) $id, $stockActual)) {
                throw new RuntimeException('No fue posible actualizar el insumo.');
            }

            // Registrar movimiento (asegúrate de que tu modelo Movimiento acepte el stockActual)
            if (!$this->movimientoModel->registrarMovimiento(
                (int) $id,
                $tipoMovimiento,
                $cantidad,
                $stockActual,
                $motivo,
                $idUsuario
            )) {
                throw new RuntimeException('No fue posible registrar el movimiento.');
            }

            $this->pdo->commit();
            $_SESSION['flash_success'] = 'El inventario se actualizó correctamente.';
            header('Location: index.php?action=inventario');
            exit;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            error_log($exception->__toString());
            $_SESSION['flash_error'] = 'No se pudo actualizar el inventario. Inténtalo nuevamente.';
            header('Location: index.php?action=inventario_editar&id=' . $id);
            exit;
        }
    }

    public function obtenerAlertas(): array
    {
        return $this->insumoModel->obtenerAlertas();
    }
}