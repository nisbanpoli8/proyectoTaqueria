<?php

declare(strict_types=1);

$ordenes = $ordenes ?? [];
$success = $success ?? '';
$error = $error ?? '';

function escaparCocinaListos(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes Listas para Servir | Tacos Roy</title>
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
    <header class="border-b-4 border-[#4A2E1D] bg-[#FBBF24] shadow-lg">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4">
            <div>
                <span class="font-brand text-2xl">TACOS ROY</span>
                <h1 class="text-lg font-bold">Órdenes Listas para Servir</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                <span id="last-updated" class="hidden items-center text-xs font-semibold sm:flex" aria-live="polite"></span>
                <button type="button" id="refresh-now" class="rounded-xl border-2 border-[#4A2E1D] bg-[#FFFBEB] px-4 py-2 font-bold">
                    <i class="fa-solid fa-rotate mr-1" aria-hidden="true"></i>Refrescar ahora
                </button>
                <a href="index.php?action=cocina" class="rounded-xl border-2 border-[#4A2E1D] bg-[#4A2E1D] px-4 py-2 font-bold text-[#FBBF24]">Comandas pendientes</a>
                <a href="index.php?action=logout" class="rounded-xl border-2 border-[#B91C1C] bg-[#B91C1C] px-4 py-2 font-bold text-white">Cerrar Sesión</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        <?php if ($success !== ''): ?>
            <div id="flash-success" role="status" class="mb-5 rounded-xl border-2 border-green-700 bg-[#DCFCE7] px-4 py-3 font-semibold text-green-900">
                <i class="fa-solid fa-circle-check mr-2" aria-hidden="true"></i><?php echo escaparCocinaListos($success); ?>
            </div>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
            <div role="alert" class="mb-5 rounded-xl border-2 border-[#B91C1C] bg-[#FEE2E2] px-4 py-3 font-semibold text-[#B91C1C]">
                <i class="fa-solid fa-circle-exclamation mr-2" aria-hidden="true"></i><?php echo escaparCocinaListos($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($ordenes === []): ?>
            <div class="rounded-3xl border-4 border-[#4A2E1D] bg-[#FFFBEB] px-6 py-14 text-center shadow-2xl">
                <i class="fa-solid fa-bell-concierge mb-4 text-5xl text-[#15803D]" aria-hidden="true"></i>
                <h2 class="font-brand text-3xl">No hay órdenes listas</h2>
                <p class="mt-2 text-[#78350F]">Las comandas marcadas como listas aparecerán aquí.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                <?php foreach ($ordenes as $orden): ?>
                    <article class="flex flex-col rounded-2xl border-4 border-green-700 bg-[#FFFBEB] shadow-xl">
                        <div class="flex items-start justify-between gap-3 rounded-t-xl bg-[#DCFCE7] p-4">
                            <div>
                                <h2 class="font-brand text-3xl">MESA <?php echo escaparCocinaListos((string) $orden['numero_mesa']); ?></h2>
                                <p class="text-sm font-semibold">
                                    <?php echo escaparCocinaListos(trim((string) $orden['mesero_nombre'] . ' ' . (string) $orden['mesero_apellido'])); ?>
                                </p>
                            </div>
                            <span class="rounded-full bg-green-800 px-3 py-1 text-xs font-bold text-white">Lista</span>
                        </div>
                        <ul class="flex-1 space-y-3 p-4">
                            <?php foreach ($orden['detalle'] as $producto): ?>
                                <li class="flex gap-3 border-b border-dashed border-green-200 pb-2 last:border-0">
                                    <span class="min-w-9 rounded-lg bg-[#4A2E1D] px-2 py-1 text-center font-bold text-[#FBBF24]"><?php echo (int) $producto['cantidad']; ?>x</span>
                                    <span class="font-semibold">
                                        <?php echo escaparCocinaListos((string) $producto['nombre_producto']); ?>
                                        <?php if (trim((string) ($producto['notas'] ?? '')) !== ''): ?>
                                            <span class="block text-sm italic text-[#B91C1C]"><?php echo escaparCocinaListos((string) $producto['notas']); ?></span>
                                        <?php endif; ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="flex items-center justify-between px-4 pb-3 text-sm text-[#78350F]">
                            <span><i class="fa-regular fa-clock mr-1" aria-hidden="true"></i><?php echo date('H:i', strtotime((string) $orden['creado_en'])); ?></span>
                            <span>Orden #<?php echo (int) $orden['id']; ?></span>
                        </div>
                        <form method="POST" action="index.php?action=cocina_actualizar_estado" class="p-4 pt-0">
                            <input type="hidden" name="id_orden" value="<?php echo (int) $orden['id']; ?>">
                            <input type="hidden" name="estado" value="servido">
                            <button type="submit" class="w-full rounded-xl border-2 border-[#4A2E1D] bg-[#15803D] px-4 py-3 font-bold text-white transition hover:bg-green-800">
                                <i class="fa-solid fa-check-double mr-2" aria-hidden="true"></i>Marcar como servido
                            </button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script src="assets/js/cocina.js" defer></script>
</body>
</html>
