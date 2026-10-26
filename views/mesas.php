<?php
declare(strict_types=1);
$mesas = $mesas ?? [];
$filtros = $filtros ?? ['todas' => 0, 'libres' => 0, 'ocupadas' => 0, 'cuenta' => 0];
$vista = $vista ?? 'todas';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Mesas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css?v=2">
</head>
<body class="mesas-body">
    <div class="container py-4">
        <div class="mesas-header mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="brand-chip">Taquería Tacos Tacos</span>
                    <h1 class="mesas-title mb-0">Comanda de Mesas</h1>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="user-pill">Mesero: <?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? 'Usuario', ENT_QUOTES, 'UTF-8'); ?></span>
                    <a href="index.php?action=logout" class="btn btn-danger">Cerrar sesión</a>
                </div>
            </div>
        </div>

        <!-- FILTROS CORREGIDOS: Coinciden con los estados de la BD -->
        <div class="filter-bar mb-4">
            <button type="button" class="filter-btn active" data-filter="todas">
                Todas <span class="counter"><?php echo (int) $filtros['todas']; ?></span>
            </button>
            <button type="button" class="filter-btn" data-filter="libre">
                Libres <span class="counter"><?php echo (int) $filtros['libres']; ?></span>
            </button>
            <button type="button" class="filter-btn" data-filter="ocupada">
                Ocupadas <span class="counter"><?php echo (int) $filtros['ocupadas']; ?></span>
            </button>
            <button type="button" class="filter-btn" data-filter="reservada">
                Pendiente Pago <span class="counter"><?php echo (int) $filtros['cuenta']; ?></span>
            </button>
        </div>

        <div class="row g-4">
            <?php foreach ($mesas as $mesa): ?>
                <?php
                $estado = strtoupper((string) ($mesa['estado'] ?? 'LIBRE'));
                $mostrarComensales = (int) ($mesa['comensales'] ?? 0);
                ?>
                <!-- EL DATA-STATUS SE GENERA EN MINÚSCULAS CORRECTAMENTE -->
                <div class="col-12 col-sm-6 col-lg-4 mesa-card-wrapper" data-status="<?php echo strtolower($estado); ?>">
                    <div class="mesa-card status-<?php echo strtolower($estado); ?>">
                        <div class="mesa-numero"> <?php echo htmlspecialchars((string) $mesa['numero_mesa'], ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="mesa-capacidad">
                            <span class="mesa-icon">👥</span>
                            <?php echo (int) $mesa['capacidad']; ?> personas
                        </div>
                        <div class="mesa-estado">
                            <?php echo $estado === 'RESERVADA' ? 'Cuenta' : ($estado === 'OCUPADA' ? 'Ocupada' : 'Libre'); ?>
                        </div>
                        <div class="mesa-resumen">
                            <?php if ($estado === 'LIBRE'): ?>
                                Sin ocupación
                            <?php else: ?>
                                <?php echo $mostrarComensales > 0 ? $mostrarComensales . ' personas' : 'Pedido activo'; ?>
                            <?php endif; ?>
                        </div>

                        <div class="mesa-actions">
                            <?php if ($estado === 'LIBRE'): ?>
                                <button type="button" class="btn btn-primary btn-ocupacion"
                                data-id="<?php echo (int) $mesa['id']; ?>"
                                data-numero="<?php echo htmlspecialchars((string) $mesa['numero_mesa'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-capacidad="<?php echo (int) $mesa['capacidad']; ?>">
                                    Ocupar Mesa
                                </button>
                            <?php elseif ($estado === 'OCUPADA'): ?>
                                <a href="index.php?action=comanda&id=<?php echo (int) $mesa['id']; ?>" class="btn btn-primary w-100">Ver Comanda</a>
                                <a href="index.php?action=mesas_solicitar_cuenta&id=<?php echo (int) $mesa['id']; ?>" class="btn btn-warning mt-2 w-100">Solicitar Cuenta</a>
                            <?php else: ?>
                                <a href="index.php?action=caja&id_mesa=<?php echo (int) $mesa['id']; ?>" class="btn btn-success w-100">
                                <i class="fa-solid fa-cash-register"></i> Cobrar Ticket
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- MODAL CORREGIDO: El footer está vacío, JS lo llenará -->
    <div class="modal fade" id="mesaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mesaModalTitle">Mesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="mesaModalBody"></div>
                <div class="modal-footer" id="modalFooter"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Agregamos ?v=3 para forzar la recarga del script -->
    <script src="assets/js/mesas.js?v=3" defer></script>
</body>
</html>