<?php
// views/cocina.php

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
    <link rel="icon" type="image/png" href="assets/images/logoTacosRoyTab.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cocina - Comandas | Tacos Roy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Llamamos a nuestros estilos -->
    <link rel="stylesheet" href="assets/css/styles.css?v=7">
</head>
<body class="cocina-body">
    <div id="aviso-actualizacion" class="hidden fixed bottom-5 right-5 bg-[#4A2E1D] text-[#FBBF24] px-6 py-3 rounded-xl shadow-2xl z-50 border-2 border-[#FBBF24]">
        Actualizando...
    </div>
    <!-- Header -->
    <header class="cocina-header">
        <div class="cocina-header-content">
            <div class="cocina-logo">
                    <img src="assets/images/logoTacosRoyH.png" alt="Logo Tacos Roy" class="cocina-logo-img">
                <div>
                    <div class="cocina-logo-text">TACOS ROY</div>
                    <div class="cocina-logo-sub">Cocina - Comandas Pendientes</div>
                </div>
            </div>
             <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <!-- 1. Contador de órdenes -->
                <span class="rounded-full border-2 border-[#4A2E1D] bg-[#FFFBEB] px-4 py-2 text-sm">
                    <i class="fa-solid fa-receipt mr-1" aria-hidden="true"></i>
                    <span><?php echo count($ordenes); ?></span> órdenes
                </span>
                
                <!-- 2. Hora de actualización (El JS la llena) -->
                <span class="hidden text-xs sm:inline" id="last-updated" aria-live="polite"></span>
                
                <!-- 3. Enlace a Órdenes Listas -->
                <a href="index.php?action=cocina_listos" class="cocina-btn cocina-btn-dark">
                    <i class="fa-solid fa-bell-concierge mr-1" aria-hidden="true"></i>Órdenes listas
                </a>
                
                <!-- 5. Botón Cerrar Sesión -->
                <a href="index.php?action=logout" class="cocina-btn" style="background-color: #B91C1C; color: white; border-color: #B91C1C;">
                    <i class="fa-solid fa-right-from-bracket mr-1" aria-hidden="true"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </header>

    <main class="cocina-main">
        <!-- Avisos -->
        <?php if ($success !== ''): ?>
            <div class="p-4 mb-4 rounded-lg bg-green-100 text-green-900 border-2 border-green-700">
                <i class="fa-solid fa-circle-check mr-2"></i><?php echo escaparCocina($success); ?>
            </div>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
            <div class="p-4 mb-4 rounded-lg bg-red-100 text-red-900 border-2 border-red-700">
                <i class="fa-solid fa-circle-exclamation mr-2"></i><?php echo escaparCocina($error); ?>
            </div>
        <?php endif; ?>

        <!-- Sección: Mesas del Salón -->
        <section class="cocina-section">
            <h2 class="cocina-section-title">
                <i class="fa-solid fa-map-location-dot text-[#9A3412]"></i> Estado del Salón
            </h2>
            <div class="cocina-mesas-grid">
                <?php foreach ($mesas as $mesa): ?>
                    <?php
                    $disponibilidad = strtolower((string) ($mesa['disponibilidad'] ?? 'libre'));
                    $textos = ['libre' => 'Libre', 'ocupada' => 'Ocupada', 'reservada' => 'Reservada'];
                    $iconos = ['libre' => 'fa-circle-check', 'ocupada' => 'fa-utensils', 'reservada' => 'fa-clock'];
                    $textoMesa = $textos[$disponibilidad] ?? 'Desconocida';
                    $iconoMesa = $iconos[$disponibilidad] ?? 'fa-circle-question';
                    ?>
                    <div class="cocina-mesa-card cocina-mesa-<?php echo $disponibilidad; ?>">
                        <div>
                            <div class="text-lg"><?php echo escaparCocina((string) $mesa['numero_mesa']); ?></div>
                            <div class="text-xs"><?php echo $textoMesa; ?> · <?php echo (int) $mesa['capacidad']; ?> pax</div>
                        </div>
                        <i class="fa-solid <?php echo $iconoMesa; ?> text-lg"></i>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Sección: Comandas Activas -->
        <section class="cocina-section">
            <h2 class="cocina-section-title">
                <i class="fa-solid fa-fire-burner text-[#9A3412]"></i> Comandas Activas
                <span class="text-sm text-[#78350F] ml-auto">Mesero: <?php echo escaparCocina($nombreCompleto); ?></span>
            </h2>

            <?php if ($ordenes === []): ?>
                <div class="text-center p-10 bg-yellow-50 border-2 border-dashed border-yellow-400 rounded-lg">
                    <i class="fa-solid fa-mug-hot text-5xl text-[#9A3412] mb-3"></i>
                    <h3 class="font-brand text-2xl">¡Todo al día!</h3>
                    <p class="text-sm text-gray-600">No hay comandas pendientes.</p>
                </div>
            <?php else: ?>
                <div class="cocina-orders-grid">
                    <?php foreach ($ordenes as $orden): ?>
                        <?php
                        $preparando = $orden['estado'] === 'en_preparacion';
                        $cantidadProductos = array_sum(array_map(fn($p) => (int) $p['cantidad'], $orden['detalle']));
                        $creadoEn = strtotime((string) $orden['creado_en']);
                        $notasUnicas = []; 
                        ?>
                        <article class="cocina-order-card">
                            <div class="cocina-order-header flex justify-between items-start">
                                <div>
                                    <h3 class="font-brand text-2xl"><?php echo escaparCocina((string) $orden['numero_mesa']); ?></h3>
                                    <p class="text-sm"><i class="fa-solid fa-user mr-1"></i> <?php echo escaparCocina(trim($orden['mesero_nombre'] . ' ' . $orden['mesero_apellido'])); ?></p>
                                </div>
                                <span class="px-2 py-1 rounded text-xs<?php echo $preparando ? 'bg-yellow-200 text-yellow-900' : 'bg-red-200 text-red-900'; ?>">
                                    <?php echo $preparando ? 'En preparación' : 'Pendiente'; ?>
                                </span>
                            </div>

                            <div class="cocina-order-body">
                                <div class="flex justify-between border-b pb-2 mb-2 text-sm">
                                    <span>Productos</span>
                                    <span><?php echo $cantidadProductos; ?> items</span>
                                </div>
                                <ul class="space-y-2">
                                    <?php foreach ($orden['detalle'] as $producto): ?>
                                        <?php 
                                        $nota = trim((string) ($producto['notas'] ?? ''));
                                        if ($nota !== '') {
                                            $notasUnicas[] = $nota;
                                        }
                                        ?>
                                        <li class="flex gap-2 text-sm border-b border-dashed pb-1 last:border-0">
                                            <span class="bg-[#4A2E1D] text-[#FBBF24] px-2 rounded"><?php echo (int) $producto['cantidad']; ?>x</span>
                                            <div>
                                                <span><?php echo escaparCocina((string) $producto['nombre_producto']); ?></span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if (!empty($notasUnicas)): ?>
                                    <div class="mt-3 p-2 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-xs font-bold text-red-800 uppercase mb-1">
                                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> Notas de la comanda: 
                                        </p>
                                        <?php foreach (array_unique($notasUnicas) as $nota): ?>
                                            <p class="text-sm italic text-red-700">• <?php echo escaparCocina($nota); ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="cocina-order-footer">
                                <span><i class="fa-regular fa-clock"></i> <?php echo $creadoEn ? date('H:i', $creadoEn) : '--:--'; ?></span>
                                <span>Orden #<?php echo (int) $orden['id']; ?></span>
                            </div>

                            <form method="POST" action="index.php?action=cocina_actualizar_estado" class="p-3 pt-0">
                                <input type="hidden" name="id_orden" value="<?php echo (int) $orden['id']; ?>">
                                <input type="hidden" name="estado" value="<?php echo $preparando ? 'listo' : 'en_preparacion'; ?>">
                                <button type="submit" class="cocina-btn w-full <?php echo $preparando ? 'cocina-btn-dark' : ''; ?>" style="<?php echo $preparando ? '' : 'background-color: #DCFCE7;'; ?>">
                                    <i class="fa-solid <?php echo $preparando ? 'fa-check' : 'fa-fire'; ?> mr-2"></i>
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