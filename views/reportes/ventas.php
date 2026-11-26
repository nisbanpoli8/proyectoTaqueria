<?php
declare(strict_types=1);

$ventas = $ventas ?? [];
$totalGeneral = $totalGeneral ?? 0.0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de ventas | Tacos Roy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen bg-gray-50">
    <?php include __DIR__ . '/../sidebar.php'; ?>
    <main class="flex-1 p-6 md:p-10">
        <div class="mx-auto max-w-5xl">
            <header class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Reporte de ventas</h1>
                    <p class="mt-1 text-gray-600">Ingresos agrupados por método de pago.</p>
                </div>
                <a href="index.php?action=reportes_pdf" target="_blank" rel="noopener" class="rounded-lg bg-red-600 px-5 py-2 font-bold text-white hover:bg-red-700">
                    Exportar PDF
                </a>
            </header>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <table class="w-full text-left">
                    <thead class="bg-amber-100 text-gray-900">
                        <tr>
                            <th class="p-4">Método de pago</th>
                            <th class="p-4 text-right">Número de ventas</th>
                            <th class="p-4 text-right">Ingresos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventas as $venta): ?>
                            <tr class="border-t border-gray-200">
                                <td class="p-4 capitalize"><?php echo htmlspecialchars((string) $venta['metodo_pago'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="p-4 text-right"><?php echo (int) $venta['cantidad_ventas']; ?></td>
                                <td class="p-4 text-right font-semibold">$<?php echo number_format((float) $venta['total'], 2, '.', ','); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="border-t-2 border-gray-300 bg-gray-50">
                        <tr>
                            <th colspan="2" class="p-4 text-right">Total general</th>
                            <th class="p-4 text-right">$<?php echo number_format((float) $totalGeneral, 2, '.', ','); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
