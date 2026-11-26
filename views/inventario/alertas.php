<?php
declare(strict_types=1);

$alertasInsumos = $alertasInsumos ?? [];
?>
<div class="rounded-xl border-2 <?php echo $alertasInsumos === [] ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50'; ?> p-5">
    <?php if ($alertasInsumos === []): ?>
        <p class="font-semibold text-green-900">
            <i class="fa-solid fa-circle-check mr-2" aria-hidden="true"></i>
            No hay insumos con bajo stock.
        </p>
    <?php else: ?>
        <h2 class="mb-3 text-lg font-bold text-red-900">
            <i class="fa-solid fa-bell mr-2" aria-hidden="true"></i>
            Atención: <?php echo count($alertasInsumos); ?> insumo(s) con stock bajo
        </h2>
        <ul class="space-y-2">
            <?php foreach ($alertasInsumos as $insumo): ?>
                <li class="flex flex-wrap items-center justify-between gap-2 text-red-900">
                    <span>
                        <strong><?php echo htmlspecialchars((string) $insumo['nombre_insumo'], ENT_QUOTES, 'UTF-8'); ?></strong>
                        — Stock: <?php echo htmlspecialchars((string) $insumo['stock_actual'], ENT_QUOTES, 'UTF-8'); ?>
                        <?php echo htmlspecialchars((string) $insumo['unidad_medida'], ENT_QUOTES, 'UTF-8'); ?>
                        (mínimo: <?php echo htmlspecialchars((string) $insumo['stock_minimo'], ENT_QUOTES, 'UTF-8'); ?>)
                    </span>
                    <a
                        href="index.php?action=inventario_editar&id=<?php echo (int) $insumo['id']; ?>"
                        class="font-semibold underline"
                    >Editar stock</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
