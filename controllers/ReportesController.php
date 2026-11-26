<?php
// controllers/ReportesController.php

declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';

class ReportesController {
    public function index(): void {
        $this->ventas();
    }

    public function ventas(): void {
        $pdo = getDatabaseConnection();
        $ventas = $this->obtenerResumenVentas($pdo);
        $totalGeneral = array_sum(array_column($ventas, 'total'));

        require __DIR__ . '/../views/reportes/ventas.php';
    }

    public function exportarPdf(): void
    {
        $autoload = __DIR__ . '/../vendor/autoload.php';

        if (!is_file($autoload)) {
            http_response_code(503);
            echo 'La exportación PDF requiere Dompdf disponible en vendor/autoload.php.';
            return;
        }

        require_once $autoload;

        if (!class_exists(\Dompdf\Dompdf::class)) {
            http_response_code(503);
            echo 'No se encontró la clase Dompdf en el autoloader configurado.';
            return;
        }

        $ventas = $this->obtenerResumenVentas(getDatabaseConnection());
        $totalGeneral = array_sum(array_column($ventas, 'total'));
        $filas = '';

        foreach ($ventas as $venta) {
            $metodo = htmlspecialchars((string) $venta['metodo_pago'], ENT_QUOTES, 'UTF-8');
            $cantidad = (int) $venta['cantidad_ventas'];
            $total = number_format((float) $venta['total'], 2, '.', ',');
            $filas .= '<tr><td>' . $metodo . '</td><td>' . $cantidad . '</td><td>$' . $total . '</td></tr>';
        }

        $html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><style>'
            . 'body{font-family:DejaVu Sans,sans-serif;color:#222}table{width:100%;border-collapse:collapse}'
            . 'th,td{padding:10px;border:1px solid #ccc;text-align:left}th{background:#fbbf24}'
            . '</style></head><body><h1>Reporte de ventas</h1><table><thead><tr>'
            . '<th>Método de pago</th><th>Número de ventas</th><th>Ingresos</th>'
            . '</tr></thead><tbody>' . $filas . '</tbody><tfoot><tr><th colspan="2">Total general</th><th>$'
            . number_format($totalGeneral, 2, '.', ',')
            . '</th></tr></tfoot></table></body></html>';

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('reporte-ventas.pdf', ['Attachment' => false]);
    }

    private function obtenerResumenVentas(PDO $pdo): array
    {
        $stmt = $pdo->prepare(
            'SELECT p.metodo_pago, COUNT(*) AS cantidad_ventas,
                    COALESCE(SUM(o.total), 0) AS total
             FROM pagos p
             INNER JOIN ordenes o ON o.id = p.id_orden
             GROUP BY p.metodo_pago
             ORDER BY p.metodo_pago'
        );
        $stmt->execute();

        $resumen = [
            'efectivo' => ['metodo_pago' => 'efectivo', 'cantidad_ventas' => 0, 'total' => 0.0],
            'tarjeta' => ['metodo_pago' => 'tarjeta', 'cantidad_ventas' => 0, 'total' => 0.0],
            'transferencia' => ['metodo_pago' => 'transferencia', 'cantidad_ventas' => 0, 'total' => 0.0],
        ];

        foreach ($stmt->fetchAll() as $fila) {
            $metodo = (string) $fila['metodo_pago'];

            if (!isset($resumen[$metodo])) {
                $resumen[$metodo] = [
                    'metodo_pago' => $metodo,
                    'cantidad_ventas' => 0,
                    'total' => 0.0,
                ];
            }

            $resumen[$metodo]['cantidad_ventas'] = (int) $fila['cantidad_ventas'];
            $resumen[$metodo]['total'] = (float) $fila['total'];
        }

        return array_values($resumen);
    }
}