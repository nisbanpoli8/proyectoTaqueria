<?php
// views/auditoria/lista.php
declare(strict_types=1);
$movimientos = $movimientos ?? [];
$desde = $desde ?? (is_string($_GET['desde'] ?? null) ? $_GET['desde'] : '');
$hasta = $hasta ?? (is_string($_GET['hasta'] ?? null) ? $_GET['hasta'] : '');
$errorFiltro = $errorFiltro ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-brand { font-family: 'Lilita One', cursive; }
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-white flex min-h-screen">

    <!-- SIDEBAR -->
    <?php include __DIR__ . '/../sidebar.php'; ?> 
 

    <!-- CONTENIDO PRINCIPAL -->
    <main class="flex-1 flex flex-col">
        
        <!-- Header Naranja -->
        <header class="bg-[#FF9B50] py-4 px-8 flex justify-between items-center shadow-sm">
            <div class="flex-1 flex justify-center items-center gap-3">
                <i class="fa-solid fa-file-invoice text-4xl text-black"></i>
                <h1 class="text-4xl font-bold text-black font-brand uppercase">HISTORIAL DE MOVIMIENTOS</h1>
            </div>
            <div class="bg-white border-2 border-black px-6 py-1 rounded-full font-bold text-black shadow-sm">
                Admin
            </div>
        </header>

        <!-- Filtros -->
        <form action="index.php" method="GET" class="p-6 bg-white border-b-2 border-gray-200 flex flex-wrap gap-4 items-end">
            <input type="hidden" name="action" value="auditoria">
            <label class="flex flex-col gap-1 font-semibold text-black">
                Desde
                <input type="date" name="desde" value="<?php echo htmlspecialchars((string) $desde, ENT_QUOTES, 'UTF-8'); ?>" class="border-2 border-gray-300 rounded p-2 text-sm">
            </label>
            <label class="flex flex-col gap-1 font-semibold text-black">
                Hasta
                <input type="date" name="hasta" value="<?php echo htmlspecialchars((string) $hasta, ENT_QUOTES, 'UTF-8'); ?>" class="border-2 border-gray-300 rounded p-2 text-sm">
            </label>
            <button type="submit" class="rounded bg-amber-500 px-5 py-2 font-bold text-black hover:bg-amber-600">Filtrar</button>
            <a href="index.php?action=auditoria" class="rounded border border-gray-300 px-5 py-2 font-semibold text-gray-700">Limpiar</a>
        </form>

        <?php if ($errorFiltro !== null): ?>
            <p class="mx-6 mt-4 rounded-lg border border-red-300 bg-red-50 p-3 text-red-800">
                <?php echo htmlspecialchars((string) $errorFiltro, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <!-- Tabla de Movimientos -->
        <div class="flex-1 overflow-y-auto p-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-black">
                        <th class="p-3 text-lg font-bold text-black uppercase">Fecha/Hora</th>
                        <th class="p-3 text-lg font-bold text-black uppercase">Insumo</th>
                        <th class="p-3 text-lg font-bold text-black uppercase">Tipo de movimiento</th>
                        <th class="p-3 text-lg font-bold text-black uppercase">Usuario</th>
                        <th class="p-3 text-lg font-bold text-black uppercase">Motivo</th>
                        <th class="p-3 text-lg font-bold text-black uppercase">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($movimientos)): ?>
                        <tr><td colspan="6" class="text-center p-10 text-gray-500 text-lg">No hay movimientos registrados para el periodo seleccionado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($movimientos as $mov): ?>
                            <tr class="border-b-2 border-gray-200 hover:bg-gray-50 fila-movimiento">
                                <td class="p-3 text-black"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime((string) $mov['fecha'])), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="p-3 text-lg text-black nombre-insumo"><?php echo htmlspecialchars((string) ($mov['nombre_insumo'] ?? 'Insumo eliminado'), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="p-3 text-lg text-black capitalize"><?php echo htmlspecialchars((string) $mov['tipo_movimiento'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="p-3 text-lg text-black"><?php echo htmlspecialchars(trim((string) ($mov['nombre_usuario'] ?? '')) ?: 'Usuario no disponible', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="p-3 text-lg text-gray-600"><?php echo htmlspecialchars((string) ($mov['motivo'] ?? 'N/A'), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="p-3 text-lg font-bold text-black"><?php echo (float)$mov['cantidad']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>