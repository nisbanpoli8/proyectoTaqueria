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
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="mesas-body">
    <div class="container py-4">
        <div class="mesas-header mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="brand-chip">Tacos Roy</span>
                    <h1 class="mesas-title mb-0">Comanda de Mesas</h1>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="user-pill">Mesero: <?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? 'Usuario', ENT_QUOTES, 'UTF-8'); ?></span>
                    <a href="index.php?action=logout" class="btn btn-danger">Cerrar sesión</a>
                </div>
            </div>
        </div>

        <div class="filter-bar mb-4">
            <button type="button" class="filter-btn active" data-filter="todas">
                Todas <span class="counter"><?php echo (int) $filtros['todas']; ?></span>
            </button>
            <button type="button" class="filter-btn" data-filter="libres">
                Libres <span class="counter"><?php echo (int) $filtros['libres']; ?></span>
            </button>
            <button type="button" class="filter-btn" data-filter="ocupadas">
                Ocupadas <span class="counter"><?php echo (int) $filtros['ocupadas']; ?></span>
            </button>
            <button type="button" class="filter-btn" data-filter="cuenta">
                Pendiente Pago <span class="counter"><?php echo (int) $filtros['cuenta']; ?></span>
            </button>
        </div>

        <div class="row g-4">
            <?php foreach ($mesas as $mesa): ?>
                <?php
                $estado = strtoupper((string) ($mesa['estado'] ?? 'LIBRE'));
                $estadoClase = strtolower($estado);
                $mostrarComensales = (int) ($mesa['comensales'] ?? 0);
                $resumen = $estado === 'LIBRE' ? 'Mesa disponible' : ($estado === 'CUENTA' ? 'Cuenta pendiente' : 'Mesa ocupada');
                ?>
                <div class="col-sm-6 col-md-4 col-lg-3 mesa-card-wrapper" data-status="<?php echo strtolower($estado); ?>">
                    <div class="mesa-card status-<?php echo strtolower($estado); ?>">
                        <div class="mesa-numero">MESA <?php echo htmlspecialchars((string) $mesa['numero_mesa'], ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="mesa-capacidad">
                            <span class="mesa-icon">👥</span>
                            <?php echo (int) $mesa['capacidad']; ?> personas
                        </div>
                        <div class="mesa-estado">
                            <?php echo $estado === 'CUENTA' ? 'Cuenta' : ($estado === 'OCUPADA' ? 'Ocupada' : 'Libre'); ?>
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
                                <button type="button" class="btn btn-primary btn-ocupacion" data-id="<?php echo (int) $mesa['id']; ?>" data-numero="<?php echo htmlspecialchars((string) $mesa['numero_mesa'], ENT_QUOTES, 'UTF-8'); ?>">
                                    Ocupar Mesa
                                </button>
                            <?php elseif ($estado === 'OCUPADA'): ?>
                                <button type="button" class="btn btn-primary btn-ver-comanda" data-id="<?php echo (int) $mesa['id']; ?>" data-numero="<?php echo htmlspecialchars((string) $mesa['numero_mesa'], ENT_QUOTES, 'UTF-8'); ?>">
                                    Ver Comanda
                                </button>
                                <a href="index.php?action=mesas_solicitar_cuenta&id=<?php echo (int) $mesa['id']; ?>" class="btn btn-warning mt-2 w-100">Solicitar Cuenta</a>
                            <?php else: ?>
                                <button type="button" class="btn btn-success btn-cobrar" data-id="<?php echo (int) $mesa['id']; ?>" data-numero="<?php echo htmlspecialchars((string) $mesa['numero_mesa'], ENT_QUOTES, 'UTF-8'); ?>">
                                    Cobrar Ticket
                                </button>
                                <a href="index.php?action=mesas_liberar&id=<?php echo (int) $mesa['id']; ?>" class="btn btn-outline-dark mt-2 w-100">Liberar Mesa</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="modal fade" id="mesaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mesaModalTitle">Mesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="mesaModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/mesas.js" defer></script>
</body>
</html>
