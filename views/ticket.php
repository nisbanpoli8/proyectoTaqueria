<?php
// views/ticket.php
declare(strict_types=1);

// Estos datos deben venir del controlador
$orden = $orden ?? null;
$detalle = $detalle ?? [];
$pago = $pago ?? null;
$total = $total ?? 0.0;
$idMesa = $idMesa ?? 0;

// Si no hay orden, redirigir o mostrar error
if (!$orden) {
    echo "No hay ticket para mostrar.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - Tacos Roy</title>
    <style>
        /* Estilos para simular un ticket térmico */
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .ticket {
            background-color: white;
            width: 300px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        .item { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px; }
        .total { font-weight: bold; font-size: 16px; }
        
        /* Ocultar el botón de imprimir al imprimir la página */
        @media print {
            body { background-color: white; padding: 0; }
            .ticket { box-shadow: none; width: 100%; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="ticket">
        <div class="text-center">
            <h2>TAQUERÍA TACOS ROY</h2>
            <p>Sabor Tradicional</p>
            <p>Fecha: <?php echo date('d/m/Y H:i'); ?></p>
            <p>Mesa: <?php echo htmlspecialchars((string)$orden['id_mesa']); ?></p>
        </div>

        <div class="divider"></div>

        <div>
            <?php foreach ($detalle as $item): ?>
                <div class="item">
                    <span><?php echo $item['cantidad']; ?>x <?php echo htmlspecialchars($item['nombre_producto']); ?></span>
                    <span>$<?php echo number_format((float) $item['subtotal'], 2); ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="divider"></div>

        <div class="item total">
            <span>TOTAL:</span>
            <span>$<?php echo number_format((float) $total, 2); ?></span>
        </div>
        
        <?php if ($pago): ?>
        <div class="item">
            <span>Recibido:</span>
            <span>$<?php echo number_format((float) $pago['monto_recibido'], 2); ?></span>
        </div>
        <div class="item">
            <span>Cambio:</span>
            <span>$<?php echo number_format((float) $pago['cambio'], 2); ?></span>
        </div>
        <?php endif; ?>

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 20px; font-size: 12px;">
            <p>¡Gracias por su compra!</p>
            <p>Vuelva pronto</p>
        </div>

        <!-- Botón de imprimir (no se imprime en el papel) -->
        <div class="text-center no-print" style="margin-top: 30px;">
            <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">
                Imprimir Ticket
            </button>
            <br><br>
            <a href="index.php?action=caja_panel" style="color: #666; font-size: 12px;">Volver al Panel</a>
        </div>
    </div>

</body>
</html>