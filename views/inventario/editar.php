<?php
declare(strict_types=1);

$insumo = $insumo ?? null;
$error = $error ?? null;
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar inventario | Tacos Roy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen bg-gray-100">
    <?php include __DIR__ . '/../sidebar.php'; ?>
    <main class="flex-1 p-6 md:p-10">
        <div class="mx-auto max-w-2xl rounded-xl bg-white p-6 shadow">
            <h1 class="mb-6 text-2xl font-bold text-gray-900">Editar stock</h1>

            <?php if ($error !== null || $flashError !== null): ?>
                <p class="mb-4 rounded-lg border border-red-300 bg-red-50 p-3 text-red-800">
                    <?php echo htmlspecialchars((string) ($error ?? $flashError), ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>

            <?php if ($insumo === null): ?>
                <a href="index.php?action=inventario" class="font-semibold text-blue-700 underline">Volver al inventario</a>
            <?php else: ?>
                <p class="mb-6 text-gray-700">
                    Insumo: <strong><?php echo htmlspecialchars((string) $insumo['nombre_insumo'], ENT_QUOTES, 'UTF-8'); ?></strong>
                </p>

                <form action="index.php?action=inventario_actualizar" method="POST" class="space-y-5">
                    <input type="hidden" name="id" value="<?php echo (int) $insumo['id']; ?>">

                    <div>
                        <label for="stock_actual" class="mb-1 block font-medium text-gray-800">Stock actual (<?php echo htmlspecialchars((string) $insumo['unidad_medida']); ?>)</label>
                        <input
                            id="stock_actual"
                            name="stock_actual"
                            type="number"
                            min="0"
                            step="0.01"
                            required
                            value="<?php echo htmlspecialchars((string) $insumo['stock_actual'], ENT_QUOTES, 'UTF-8'); ?>"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-amber-500 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="motivo" class="mb-1 block font-medium text-gray-800">Motivo del movimiento</label>
                        <select id="motivo" name="motivo" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
                            <option value="">Selecciona un motivo</option>
                            <option value="Reabastecimiento">Reabastecimiento</option>
                            <option value="Merma">Merma</option>
                            <option value="Ajuste">Ajuste</option>
                        </select>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="submit" class="rounded-lg bg-amber-500 px-5 py-2 font-bold text-gray-950 hover:bg-amber-600">
                            Guardar cambios
                        </button>
                        <a href="index.php?action=inventario" class="rounded-lg border border-gray-300 px-5 py-2 font-semibold text-gray-700">
                            Cancelar
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>