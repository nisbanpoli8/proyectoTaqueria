<?php
$error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Insumo | Tacos Roy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen bg-gray-100">
    <?php include __DIR__ . '/../sidebar.php'; ?>
    
    <main class="flex-1 p-6 md:p-10">
        <div class="mx-auto max-w-2xl rounded-xl bg-white p-6 shadow">
            <h1 class="mb-6 text-2xl font-bold text-gray-900">Registrar Nuevo Insumo</h1>

            <?php if ($error !== null): ?>
                <p class="mb-4 rounded-lg border border-red-300 bg-red-50 p-3 text-red-800">
                    <?php echo htmlspecialchars((string)$error); ?>
                </p>
            <?php endif; ?>

            <form action="index.php?action=inventario_guardar" method="POST" class="space-y-5">
                
                <div>
                    <label for="nombre" class="mb-1 block font-medium text-gray-800">Nombre del Insumo *</label>
                    <input id="nombre" name="nombre" type="text" required placeholder="Ej. Cebolla morada" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-amber-500 focus:outline-none">
                </div>

                <div>
                    <label for="unidad_medida" class="mb-1 block font-medium text-gray-800">Unidad de Medida *</label>
                    <select id="unidad_medida" name="unidad_medida" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
                        <option value="">Selecciona una unidad</option>
                        <option value="Kg">Kilogramos (Kg)</option>
                        <option value="L">Litros (L)</option>
                        <option value="Pza">Piezas (Pza)</option>
                        <option value="Paquete">Paquete</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="stock_inicial" class="mb-1 block font-medium text-gray-800">Stock Inicial *</label>
                        <input id="stock_inicial" name="stock_inicial" type="number" step="0.01" min="0" required value="0" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>
                    <div>
                        <label for="stock_minimo" class="mb-1 block font-medium text-gray-800">Stock Mínimo *</label>
                        <input id="stock_minimo" name="stock_minimo" type="number" step="0.01" min="0" required value="0" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="rounded-lg bg-amber-500 px-5 py-2 font-bold text-gray-950 hover:bg-amber-600">
                        Guardar Insumo
                    </button>
                    <a href="index.php?action=inventario" class="rounded-lg border border-gray-300 px-5 py-2 font-semibold text-gray-700">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>