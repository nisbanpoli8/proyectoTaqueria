<?php

declare(strict_types=1);

$idMesa = isset($idMesa) ? (int) $idMesa : 0;
$productos = $productos ?? [];
$items = $items ?? [];
$total = $total ?? 0.0;
$notas = $notas ?? '';
$usuario = $_SESSION['nombre_completo'] ?? 'Mesero';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comanda de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="comanda-body">
    <div class="container py-4">
        <div class="comanda-shell">
            <div class="comanda-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div>
                    <span class="comanda-badge">Comanda de Pedidos</span>
                    <h1 class="mb-0 comanda-title">MESA <?php echo htmlspecialchars((string) $idMesa, ENT_QUOTES, 'UTF-8'); ?></h1>
                </div>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="comanda-user">Mesero: <?php echo htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8'); ?></span>
                    <a href="index.php?action=mesas" class="btn btn-dark">Volver a Mesas</a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="menu-panel">
                        <div class="panel-title">
                            <span>🍽️</span> MENÚ
                        </div>
                        <div class="menu-list">
                            <?php foreach ($productos as $producto): ?>
                                <div class="product-card">
                                    <div>
                                        <h3><?php echo htmlspecialchars((string) $producto['nombre_producto'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <div class="price-tag">$<?php echo number_format((float) $producto['precio'], 2); ?></div>
                                    </div>
                                    <form method="POST" action="index.php?action=comanda_agregar" class="product-form">
                                        <input type="hidden" name="id_mesa" value="<?php echo (int) $idMesa; ?>">
                                        <input type="hidden" name="id_producto" value="<?php echo (int) $producto['id']; ?>">
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="number" name="cantidad" class="form-control qty-selector" min="1" max="20" value="1">
                                            <button type="submit" class="btn btn-primary">Seleccionar</button>
                                        </div>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="menu-footer">Presiona “Seleccionar” para agregar items a la comanda.</div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="summary-panel">
                        <div class="panel-title">
                            <span>🧾</span> Resumen de Comanda
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-3" id="comandaTableBody">
                                <thead>
                                    <tr>
                                        <th>CANTIDAD</th>
                                        <th>PRODUCTO</th>
                                        <th>PRECIO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($items === []): ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">La comanda está vacía.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr data-price="<?php echo number_format((float) $item['precio'], 2, '.', ''); ?>">
                                                <td>
                                                    <div class="qty-controls">
                                                        <form method="POST" action="index.php?action=comanda_eliminar" class="d-inline">
                                                            <input type="hidden" name="id_mesa" value="<?php echo (int) $idMesa; ?>">
                                                            <input type="hidden" name="id_producto" value="<?php echo (int) $item['id']; ?>">
                                                            <input type="hidden" name="cantidad" value="1">
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary qty-btn" aria-label="Restar cantidad">-</button>
                                                        </form>
                                                        <input type="number" class="cantidad-input" value="<?php echo (int) $item['cantidad']; ?>" min="1" readonly>
                                                        <form method="POST" action="index.php?action=comanda_agregar" class="d-inline">
                                                            <input type="hidden" name="id_mesa" value="<?php echo (int) $idMesa; ?>">
                                                            <input type="hidden" name="id_producto" value="<?php echo (int) $item['id']; ?>">
                                                            <input type="hidden" name="cantidad" value="1">
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary qty-btn" aria-label="Sumar cantidad">+</button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="product-name"><?php echo htmlspecialchars((string) $item['nombre'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                    <form method="POST" action="index.php?action=comanda_eliminar" class="mt-2">
                                                        <input type="hidden" name="id_mesa" value="<?php echo (int) $idMesa; ?>">
                                                        <input type="hidden" name="id_producto" value="<?php echo (int) $item['id']; ?>">
                                                        <input type="hidden" name="cantidad" value="999">
                                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0">Eliminar</button>
                                                    </form>
                                                </td>
                                                <td class="subtotal-item">$<?php echo number_format((float) $item['subtotal'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <form method="POST" action="index.php?action=comanda_enviar" class="mt-3">
                            <input type="hidden" name="id_mesa" value="<?php echo (int) $idMesa; ?>">
                            <div class="subtotal-box">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Subtotal</span>
                                    <strong id="subtotal-valor">$<?php echo number_format((float) $total, 2); ?></strong>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="notas" class="form-label">Modificaciones / notas</label>
                                <textarea id="notas" name="notas" class="form-control" rows="3" placeholder="Ej. sin cebolla, poca salsa"><?php echo htmlspecialchars((string) $notas, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <div class="action-row mt-4">
                                <a href="index.php?action=mesas" class="btn btn-danger">Cancelar</a>
                                <button type="submit" class="btn btn-warning">Enviar a Cocina</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/comanda.js" defer></script>
</body>
</html>
