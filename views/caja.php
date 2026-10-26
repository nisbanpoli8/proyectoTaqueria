<?php
// views/caja.php

declare(strict_types=1);

$nombreCompleto = $_SESSION['nombre_completo'] ?? 'Cajero';
$rol = $_SESSION['rol'] ?? 'Cajero';
$orden = $orden ?? null;
$detalle = $detalle ?? [];
$subtotal = isset($subtotal) ? (float) $subtotal : 0.0;
$total = isset($total) ? (float) $total : 0.0;
$idMesa = (int) ($id_mesa ?? 0);
$idOrden = (int) ($id_orden ?? 0);
$hayOrdenActiva = $orden !== null && $idOrden > 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Caja | Taquería Tacos Roy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'taco-yellow': '#FBBF24',
                        'taco-yellow-dark': '#F59E0B',
                        'taco-yellow-light': '#FEF3C7',
                        'taco-yellow-warm': '#FFFBEB',
                        'taco-brown-dark': '#3D2314',
                        'taco-brown': '#4A2E1D',
                        'taco-brown-light': '#78350F',
                        'taco-terracotta': '#9A3412',
                    },
                    fontFamily: {
                        'brand': ['"Lilita One"', 'cursive'],
                        'sans': ['"Poppins"', 'sans-serif'],
                    },
                    boxShadow: {
                        'craft': '0 15px 25px -5px rgba(61, 35, 20, 0.25), 0 8px 10px -6px rgba(61, 35, 20, 0.15)',
                    }
                }
            }
        };
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #3D2314;
            background-image: radial-gradient(circle at 50% 50%, rgba(245, 158, 11, 0.12) 0%, rgba(61, 35, 20, 0.9) 100%), radial-gradient(#78350F 1px, transparent 1px);
            background-size: 100% 100%, 24px 24px;
        }
    </style>
</head>
<body class="min-h-screen py-8 text-taco-brown">
    <div class="mx-auto max-w-7xl px-4">
        <header class="mb-8 rounded-3xl border-4 border-taco-brown bg-taco-yellow-warm p-4 shadow-craft">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border-2 border-taco-brown bg-taco-yellow text-2xl shadow-md">🌮</div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-taco-brown-light">Taquería Tacos Roy</p>
                        <h1 class="font-brand text-3xl text-taco-brown">Panel de Caja</h1>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <div class="rounded-full border-2 border-taco-brown bg-white px-3 py-2 text-sm font-semibold text-taco-brown">
                        <?php echo htmlspecialchars($rol, ENT_QUOTES, 'UTF-8'); ?>: <?php echo htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <a href="index.php?action=logout" class="inline-flex items-center justify-center rounded-xl border-2 border-taco-brown bg-red-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-600">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i>
                        Cerrar Sesión
                    </a>
                </div>
            </div>
        </header>

        <div class="mb-6 flex items-center justify-between gap-3">
            <div class="rounded-full border-2 border-taco-yellow bg-taco-yellow-light px-4 py-2 text-sm font-medium text-taco-brown">
                Cuenta en revisión
            </div>
            <a href="index.php?action=mesas" class="inline-flex items-center justify-center rounded-xl border-2 border-taco-brown bg-white px-4 py-2 text-sm font-semibold text-taco-brown transition hover:bg-taco-yellow-light">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Volver a Mesas
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.4fr_0.9fr]">
            <section class="rounded-3xl border-4 border-taco-brown bg-taco-yellow-warm p-5 shadow-craft">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="font-brand text-2xl text-taco-brown">Desglose de la comanda</h2>
                    <span class="rounded-full bg-taco-brown px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-white">
                        Mesa <?php echo htmlspecialchars((string) $idMesa, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <?php if (!$hayOrdenActiva): ?>
                    <div class="rounded-2xl border-2 border-dashed border-taco-brown/40 bg-white p-6 text-center">
                        <p class="font-brand text-2xl text-taco-brown">No hay una orden activa</p>
                        <p class="mt-2 text-sm text-taco-brown-light">Esta mesa no tiene una comanda pendiente de pago.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($detalle as $producto): ?>
                            <div class="flex items-center justify-between rounded-2xl border border-taco-brown/20 bg-white p-3">
                                <div>
                                    <p class="font-semibold text-taco-brown"><?php echo htmlspecialchars((string) ($producto['nombre_producto'] ?? 'Producto'), ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p class="text-sm text-taco-brown-light">
                                        <?php echo (int) ($producto['cantidad'] ?? 0); ?> · $<?php echo number_format((float) ($producto['precio_unitario'] ?? 0.0), 2, '.', ','); ?>
                                    </p>
                                </div>
                                <p class="font-bold text-taco-brown">$<?php echo number_format((float) ($producto['subtotal'] ?? 0.0), 2, '.', ','); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-6 space-y-3 border-t border-taco-brown/20 pt-5">
                        <div class="flex items-center justify-between text-taco-brown-light">
                            <span>Subtotal</span>
                            <span>$<?php echo number_format($subtotal, 2, '.', ','); ?></span>
                        </div>
                        <div class="flex items-center justify-between border-t border-taco-brown/20 pt-3 text-lg font-bold text-taco-brown">
                            <span>Total</span>
                            <span>$<?php echo number_format($total, 2, '.', ','); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <aside class="rounded-3xl border-4 border-taco-brown bg-white p-5 shadow-craft">
                <div class="mb-5">
                    <h2 class="font-brand text-2xl text-taco-brown">Pago</h2>
                    <p class="text-sm text-taco-brown-light">Resumen del cobro y método de pago.</p>
                </div>

                <?php if ($hayOrdenActiva): ?>
                    <form action="index.php?action=caja_procesar_pago" method="POST" class="space-y-5" data-total="<?php echo number_format($total, 2, '.', ''); ?>">
                        <input type="hidden" name="id_orden" value="<?php echo $idOrden; ?>">
                        <input type="hidden" name="id_mesa" value="<?php echo $idMesa; ?>">
                        <input type="hidden" name="total" value="<?php echo number_format($total, 2, '.', ''); ?>">

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-taco-brown">Método de pago</label>
                            <div class="space-y-3">
                                <label class="flex cursor-pointer items-center justify-between rounded-2xl border border-taco-brown/20 bg-taco-yellow-warm p-3">
                                    <span class="flex items-center gap-3">
                                        <input type="radio" name="metodo_pago" value="efectivo" class="h-4 w-4 text-taco-brown" checked>
                                        <span class="font-medium text-taco-brown">Efectivo</span>
                                    </span>
                                    <i class="fa-solid fa-money-bill-wave text-green-600"></i>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="monto_recibido" class="mb-2 block text-sm font-semibold text-taco-brown">Monto recibido</label>
                            <input id="monto_recibido" name="monto_recibido" type="number" min="0" step="0.01" value="<?php echo number_format($total, 2, '.', ''); ?>" class="w-full rounded-2xl border-2 border-taco-brown/20 bg-taco-yellow-light px-4 py-3 text-base text-taco-brown outline-none transition focus:border-taco-brown" placeholder="0.00">
                        </div>

                        <div class="rounded-2xl border-2 border-taco-brown bg-taco-yellow-light p-4">
                            <p class="text-sm uppercase tracking-[0.2em] text-taco-brown-light">Cambio</p>
                            <p id="cambio-valor" class="mt-2 text-3xl font-brand text-taco-brown">$<?php echo number_format(0, 2, '.', ','); ?></p>
                        </div>

                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl border-2 border-taco-brown bg-taco-yellow px-4 py-3 text-base font-bold uppercase tracking-wide text-taco-brown transition hover:bg-taco-yellow-dark">
                            <i class="fa-solid fa-cash-register mr-2"></i>
                            Procesar Pago
                        </button>
                    </form>
                <?php else: ?>
                    <div class="rounded-2xl border-2 border-dashed border-taco-brown/40 bg-taco-yellow-light p-6 text-center">
                        <p class="font-semibold text-taco-brown">No se puede cobrar hasta que exista una orden activa.</p>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>

    <script src="assets/js/caja.js" defer></script>
</body>
</html>