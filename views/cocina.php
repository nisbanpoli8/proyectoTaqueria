<?php

declare(strict_types=1);

$ordenes = $ordenes ?? [];
$mesas = $mesas ?? [];
$success = $success ?? '';
$error = $error ?? '';
$nombreCompleto = (string) ($_SESSION['nombre_completo'] ?? 'Encargado de Cocina');

function escaparCocina(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cocina - Comandas Pendientes | Tacos Roy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #3D2314;
            background-image: radial-gradient(#78350F 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .font-brand { font-family: 'Lilita One', cursive; }
    </style>
</head>
<body class="min-h-screen text-[#3D2314]">
    <header class="sticky top-0 z-10 border-b-4 border-[#4A2E1D] bg-[#FBBF24] shadow-lg">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4">
            <div class="flex items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#4A2E1D] bg-[#FFFBEB] text-2xl text-[#9A3412]">
                    <i class="fa-solid fa-fire" aria-hidden="true"></i>
                </span>
                <div>
                    <div class="font-brand text-2xl uppercase leading-none">TACOS ROY</div>
                    <h1 class="mt-1 text-sm font-semibold sm:text-base">Cocina - Comandas Pendientes</h1>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <span class="rounded-full border-2 border-[#4A2E1D] bg-[#FFFBEB] px-4 py-2 text-sm font-bold">
                    <i class="fa-solid fa-receipt mr-1" aria-hidden="true"></i>
                    <span><?php echo count($ordenes); ?></span> órdenes pendientes
                </span>
                <span class="hidden text-xs font-semibold sm:inline" id="last-updated" aria-live="polite"></span>
                <button type="button" id="refresh-now" class="rounded-xl border-2 border-[#4A2E1D] bg-[#FFFBEB] px-4 py-2 font-bold transition hover:bg-[#FEF3C7]">
                    <i class="fa-solid fa-rotate mr-1" aria-hidden="true"></i>Refrescar ahora
                </button>
                <a href="index.php?action=cocina_listos" class="rounded-xl border-2 border-[#4A2E1D] bg-[#4A2E1D] px-4 py-2 font-bold text-[#FBBF24] transition hover:bg-[#3D2314]">
                    <i class="fa-solid fa-bell-concierge mr-1" aria-hidden="true"></i>Órdenes listas
                </a>
                <a href="index.php?action=logout" class="rounded-xl border-2 border-[#B91C1C] bg-[#B91C1C] px-4 py-2 font-bold text-white transition hover:bg-red-800">
                    <i class="fa-solid fa-right-from-bracket mr-1" aria-hidden="true"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl space-y-8 px-4 py-8">
        <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border-2 border-[#FBBF24] bg-[#FFFBEB] p-4 shadow-lg">
            <p class="font-semibold">Bienvenido, <?php echo escaparCocina($nombreCompleto); ?>.</p>
            <p class="text-sm font-medium text-[#78350F]">El tablero se actualiza automáticamente cada 15 segundos.</p>
        </div>

        <?php if ($success !== ''): ?>
            <div id="flash-success" role="status" class="rounded-xl border-2 border-green-700 bg-[#DCFCE7] px-4 py-3 font-semibold text-green-900">
                <i class="fa-solid fa-circle-check mr-2" aria-hidden="true"></i><?php echo escaparCocina($success); ?>
            </div>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
            <div role="alert" class="rounded-xl border-2 border-[#B91C1C] bg-[#FEE2E2] px-4 py-3 font-semibold text-[#B91C1C]">
                <i class="fa-solid fa-circle-exclamation mr-2" aria-hidden="true"></i><?php echo escaparCocina($error); ?>
            </div>
        <?php endif; ?>

        <section aria-labelledby="salon-title" class="rounded-3xl border-4 border-[#4A2E1D] bg-[#FFFBEB] p-5 shadow-2xl sm:p-7">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <h2 id="salon-title" class="font-brand text-3xl text-[#4A2E1D]">
                    <i class="fa-solid fa-map-location-dot mr-2 text-[#9A3412]" aria-hidden="true"></i>Estado del salón
                </h2>
                <span class="rounded-full bg-[#FEF3C7] px-3 py-1 text-sm font-bold"><?php echo count($mesas); ?> mesas</span>
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <?php foreach ($mesas as $mesa): ?>
                    <?php
                    $disponibilidad = strtolower((string) ($mesa['disponibilidad'] ?? 'libre'));
                    $estadoMesa = [
                        'libre' => ['Libre', 'bg-[#DCFCE7] border-green-700 text-green-900', 'fa-circle-check'],
                        'ocupada' => ['Ocupada', 'bg-[#FEE2E2] border-[#B91C1C] text-[#B91C1C]', 'fa-utensils'],
                        'reservada' => ['Reservada', 'bg-[#FEF3C7] border-[#F59E0B] text-[#78350F]', 'fa-clock'],
                    ][$disponibilidad] ?? ['Desconocida', 'bg-gray-100 border-gray-400 text-gray-700', 'fa-circle-question'];
                    ?>
                    <div class="flex items-center justify-between gap-3 rounded-xl border-2 px-4 py-3 <?php echo $estadoMesa[1]; ?>">
                        <div>
                            <div class="font-brand text-xl">MESA <?php echo escaparCocina((string) $mesa['numero_mesa']); ?></div>
                            <div class="text-xs font-semibold"><?php echo $estadoMesa[0]; ?> · <?php echo (int) $mesa['capacidad']; ?> personas</div>
                        </div>
                        <i class="fa-solid <?php echo $estadoMesa[2]; ?> text-xl" aria-hidden="true"></i>
                    </div>
                <?php endforeach; ?>
                <?php if ($mesas === []): ?>
                    <p class="text-sm text-[#78350F]">No hay mesas registradas.</p>
                <?php endif; ?>
            </div>
        </section>

        <section aria-labelledby="orders-title" class="rounded-3xl border-4 border-[#4A2E1D] bg-[#FFFBEB] p-5 shadow-2xl sm:p-7">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <h2 id="orders-title" class="font-brand text-3xl text-[#4A2E1D]">
                    <i class="fa-solid fa-fire-burner mr-2 text-[#9A3412]" aria-hidden="true"></i>Comandas activas
                </h2>
                <span class="text-sm font-semibold text-[#78350F]">Mesero: <?php echo escaparCocina($nombreCompleto); ?></span>
            </div>

            <?php if ($ordenes === []): ?>
                <div class="rounded-2xl border-2 border-dashed border-[#F59E0B] bg-[#FEF3C7] px-6 py-12 text-center">
                    <i class="fa-solid fa-mug-hot mb-4 text-5xl text-[#9A3412]" aria-hidden="true"></i>
                    <h3 class="font-brand text-3xl text-[#4A2E1D]">¡Todo al día!</h3>
                    <p class="mt-2 text-[#78350F]">No hay comandas pendientes por preparar.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                    <?php foreach ($ordenes as $orden): ?>
                        <?php
                        $preparando = $orden['estado'] === 'en_preparacion';
                        $cantidadProductos = array_sum(array_map(
                            static fn (array $producto): int => (int) $producto['cantidad'],
                            $orden['detalle']
                        ));
                        $creadoEn = strtotime((string) $orden['creado_en']);
                        ?>
                        <article class="flex flex-col rounded-2xl border-2 border-[#4A2E1D] bg-white shadow-lg">
                            <div class="flex items-start justify-between gap-3 rounded-t-xl border-b-2 border-[#4A2E1D] bg-[#FBBF24] p-4">
                                <div>
                                    <h3 class="font-brand text-3xl">MESA <?php echo escaparCocina((string) $orden['numero_mesa']); ?></h3>
                                    <p class="mt-1 text-sm font-semibold text-[#4A2E1D]">
                                        <i class="fa-solid fa-user mr-1" aria-hidden="true"></i>
                                        <?php echo escaparCocina(trim((string) $orden['mesero_nombre'] . ' ' . (string) $orden['mesero_apellido'])); ?>
                                    </p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo $preparando ? 'bg-[#FEF3C7] text-[#78350F]' : 'bg-[#FEE2E2] text-[#B91C1C]'; ?>">
                                    <?php echo $preparando ? 'En preparación' : 'Pendiente'; ?>
                                </span>
                            </div>

                            <div class="flex-1 p-4">
                                <div class="mb-3 flex items-center justify-between border-b border-amber-200 pb-2">
                                    <span class="font-bold text-[#4A2E1D]">Productos</span>
                                    <span class="rounded-full bg-[#FEF3C7] px-3 py-1 text-sm font-bold"><?php echo $cantidadProductos; ?> productos</span>
                                </div>
                                <ul class="space-y-3">
                                    <?php foreach ($orden['detalle'] as $producto): ?>
                                        <li class="border-b border-dashed border-amber-200 pb-2 last:border-0">
                                            <div class="flex items-start gap-3">
                                                <span class="min-w-9 rounded-lg bg-[#4A2E1D] px-2 py-1 text-center font-bold text-[#FBBF24]"><?php echo (int) $producto['cantidad']; ?>x</span>
                                                <div class="flex-1">
                                                    <span class="font-semibold"><?php echo escaparCocina((string) $producto['nombre_producto']); ?></span>
                                                    <?php if (trim((string) ($producto['notas'] ?? '')) !== ''): ?>
                                                        <p class="mt-1 text-sm italic text-[#B91C1C]">
                                                            <i class="fa-solid fa-note-sticky mr-1" aria-hidden="true"></i><?php echo escaparCocina((string) $producto['notas']); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-amber-200 px-4 py-3 text-sm text-[#78350F]">
                                <span><i class="fa-regular fa-clock mr-1" aria-hidden="true"></i><?php echo $creadoEn === false ? 'Hora no disponible' : date('H:i', $creadoEn); ?></span>
                                <span>Orden #<?php echo (int) $orden['id']; ?></span>
                            </div>
                            <form method="POST" action="index.php?action=cocina_actualizar_estado" class="p-4 pt-0">
                                <input type="hidden" name="id_orden" value="<?php echo (int) $orden['id']; ?>">
                                <input type="hidden" name="estado" value="<?php echo $preparando ? 'listo' : 'en_preparacion'; ?>">
                                <button type="submit" class="w-full rounded-xl border-2 border-[#4A2E1D] px-4 py-3 font-bold text-[#3D2314] transition <?php echo $preparando ? 'bg-[#FBBF24] hover:bg-[#F59E0B]' : 'bg-[#DCFCE7] hover:bg-green-200'; ?>">
                                    <i class="fa-solid <?php echo $preparando ? 'fa-check' : 'fa-fire'; ?> mr-2" aria-hidden="true"></i>
                                    <?php echo $preparando ? 'Marcar como listo' : 'Iniciar preparación'; ?>
                                </button>
                            </form>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <script src="assets/js/cocina.js" defer></script>
</body>
</html>
