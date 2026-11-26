<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';

class AuditoriaController {
    public function index(): void {
        $pdo = getDatabaseConnection();
        $desdeRaw = $_GET['desde'] ?? '';
        $hastaRaw = $_GET['hasta'] ?? '';
        $desde = is_string($desdeRaw) ? trim($desdeRaw) : '';
        $hasta = is_string($hastaRaw) ? trim($hastaRaw) : '';
        $errorFiltro = null;
        $movimientos = [];

        if (($desde !== '' && !$this->esFechaValida($desde)) || ($hasta !== '' && !$this->esFechaValida($hasta))) {
            $errorFiltro = 'Ingresa fechas válidas para aplicar el filtro.';
        } elseif ($desde !== '' && $hasta !== '' && $desde > $hasta) {
            $errorFiltro = 'La fecha inicial no puede ser posterior a la fecha final.';
        } else {
            $condiciones = [];
            $parametros = [];

            if ($desde !== '') {
                $condiciones[] = 'm.fecha >= :desde';
                $parametros[':desde'] = $desde . ' 00:00:00';
            }

            if ($hasta !== '') {
                $condiciones[] = 'm.fecha < :hasta';
                $parametros[':hasta'] = (new DateTimeImmutable($hasta))->modify('+1 day')->format('Y-m-d 00:00:00');
            }

            $sql = 'SELECT m.fecha, m.tipo_movimiento, m.cantidad, m.motivo,
                           i.nombre_insumo,
                           CONCAT_WS(\' \', u.nombre, u.apellido) AS nombre_usuario
                    FROM movimientos m
                    LEFT JOIN insumos i ON m.id_insumo = i.id
                    LEFT JOIN usuarios u ON m.id_usuario = u.id';

            if ($condiciones !== []) {
                $sql .= ' WHERE ' . implode(' AND ', $condiciones);
            }

            $sql .= ' ORDER BY m.fecha DESC';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($parametros);
            $movimientos = $stmt->fetchAll();
        }

        require __DIR__ . '/../views/auditoria/lista.php';
    }

    private function esFechaValida(string $fecha): bool
    {
        $fechaAnalizada = DateTimeImmutable::createFromFormat('!Y-m-d', $fecha);

        return $fechaAnalizada !== false && $fechaAnalizada->format('Y-m-d') === $fecha;
    }
}