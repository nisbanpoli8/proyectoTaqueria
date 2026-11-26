<?php
// views/inventario/lista.php
declare(strict_types=1);
$insumos = $insumos ?? [];
$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario | Admin Panel</title>
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
        
        <!-- Header Verde -->
        <header class="bg-[#8CE65C] py-4 px-8 flex justify-between items-center shadow-sm">
            <div class="flex-1 flex justify-center items-center gap-3">
                <i class="fa-solid fa-boxes-stacked text-4xl text-black"></i>
                <h1 class="text-4xl font-bold text-black font-brand uppercase">INVENTARIO</h1>
            </div>
            <div class="bg-white border-2 border-black px-6 py-1 rounded-full font-bold text-black shadow-sm">
                Admin
            </div>
        </header>

        <?php if ($flashSuccess !== null || $flashError !== null): ?>
            <p class="m-4 rounded-lg border p-3 <?php echo $flashError !== null ? 'border-red-300 bg-red-50 text-red-800' : 'border-green-300 bg-green-50 text-green-800'; ?>">
                <?php echo htmlspecialchars((string) ($flashError ?? $flashSuccess), ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <!-- Barra de Herramientas -->
        <div class="flex w-full border-b-2 border-gray-200 bg-white">
            <div class="w-1/2 p-3 flex items-center gap-3 border-r-2 border-gray-200">
                <i class="fa-solid fa-magnifying-glass text-2xl text-gray-400"></i>
                <input type="text" id="buscador" placeholder="Buscar insumo" class="bg-transparent text-lg text-gray-700 placeholder-gray-400 outline-none w-full">
            </div>
            <div class="w-1/2 p-3 flex items-center justify-center">
                <a href="index.php?action=insumos_nuevo" class="flex items-center gap-2 text-xl font-bold text-black hover:text-green-600 transition-colors">
                    <i class="fa-solid fa-plus"></i> Nuevo insumo
                </a>
            </div>
        </div>

        <!-- Lista de Insumos -->
        <div class="flex-1 overflow-y-auto">
            <table class="w-full text-left border-collapse" id="tablaInsumos">
                <tbody>
                    <?php if (empty($insumos)): ?>
                        <tr><td colspan="5" class="text-center p-10 text-gray-500 text-lg">No hay insumos registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($insumos as $insumo): ?>
                            <?php
                            // Lógica del semáforo
                            $stock = (float)$insumo['stock_actual'];
                            $minimo = (float)$insumo['stock_minimo'];
                            
                            if ($stock <= 0) {
                                $color = 'bg-red-500';
                                $texto = 'No Disponible';
                            } elseif ($stock <= $minimo) {
                                $color = 'bg-yellow-400';
                                $texto = 'Bajo Stock';
                                // Si quieres usar un icono de advertencia en lugar de círculo, cambia el HTML de abajo
                            } else {
                                $color = 'bg-green-500';
                                $texto = 'Disponible';
                            }
                            ?>
                            <tr class="border-b-2 border-gray-300 hover:bg-gray-50 transition-colors fila-insumo">
                                <td class="p-4 w-1/4">
                                    <h3 class="text-2xl font-bold text-black nombre-insumo"><?php echo htmlspecialchars($insumo['nombre_insumo']); ?></h3>
                                </td>
                                <td class="p-4 text-lg text-black">
                                    <p>Stock Actual: <strong><?php echo $stock; ?> <?php echo htmlspecialchars((string) $insumo['unidad_medida'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
                                    <p>Stock Mínimo: <?php echo $minimo; ?> <?php echo htmlspecialchars((string) $insumo['unidad_medida'], ENT_QUOTES, 'UTF-8'); ?></p>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <?php if ($color === 'bg-yellow-400'): ?>
                                            <i class="fa-solid fa-triangle-exclamation text-4xl text-yellow-500 mb-1"></i>
                                        <?php else: ?>
                                            <span class="w-6 h-6 <?php echo $color; ?> rounded-full mb-1"></span>
                                        <?php endif; ?>
                                        <span class="text-sm font-semibold"><?php echo $texto; ?></span>
                                    </div>
                                </td>
                                <td class="p-4 text-center w-24">
                                    <a href="index.php?action=inventario_editar&id=<?php echo (int) $insumo['id']; ?>" class="text-3xl text-black hover:text-blue-600 transition-colors">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    
    <script src="assets/js/inventario.js" defer></script>
</body>
</html>