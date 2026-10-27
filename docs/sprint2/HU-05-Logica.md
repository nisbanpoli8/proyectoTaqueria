# Instrucciones para el Agente: HU-05 (Lógica de Cobro)

## Contexto
Ya tenemos la estructura (vista y controlador base) de la HU-05. Ahora debes implementar la lógica real para consultar la comanda, calcular totales, procesar el pago y liberar la mesa.

Lee `AGENTS.md` para respetar las reglas del proyecto.

## Tareas a implementar

### 1. Actualizar `controllers/CajaController.php`
- **`index()`**: 
  - Debe recibir un `id_mesa` por GET (ej. `index.php?action=caja&id_mesa=1`).
  - Si no hay `id_mesa`, redirigir a `index.php?action=mesas`.
  - Usar el modelo `Orden` para buscar la orden activa de esa mesa (estado 'pendiente', 'en_preparacion', 'listo' o 'servido').
  - Obtener el detalle de los productos de esa orden.
  - Calcular el subtotal y el total.
  - Pasar estas variables a la vista `views/caja.php`.
- **`procesarPago()`**:
  - Recibir por POST: `id_orden`, `id_mesa`, `metodo_pago`, `monto_recibido`, `total`.
  - Validar que el `monto_recibido` sea >= al `total` (si es efectivo).
  - Calcular el cambio.
  - Insertar el registro en la tabla `pagos` (usando PDO).
  - Actualizar el estado de la orden a 'pagado' (usando el modelo `Orden`).
  - Liberar la mesa (cambiar `disponibilidad` a 'libre' usando el modelo `Mesa`).
  - Guardar un `flash_success` con el mensaje "Pago procesado y mesa liberada".
  - Redirigir a `index.php?action=mesas`.

### 2. Actualizar `views/caja.php` (Hacerla dinámica)
- Reemplazar los datos hardcodeados por las variables PHP que envía el controlador (`$orden`, `$detalle`, `$subtotal`, `$total`, `$id_mesa`, `$id_orden`).
- Mostrar el número de mesa dinámicamente.
- Iterar sobre `$detalle` para mostrar los productos reales.
- Mostrar el total real.
- En el formulario, agregar inputs ocultos (`hidden`) para `id_orden`, `id_mesa` y `total`.
- Mostrar un aviso si no hay una orden activa para esa mesa.

### 3. Actualizar `public/assets/js/caja.js`
- Implementar la lógica para calcular el cambio en tiempo real.
- Escuchar el evento `input` del campo `monto_recibido` y el cambio en los radio buttons de `metodo_pago`.
- Si el método es "efectivo", calcular el cambio (`monto_recibido - total`) y mostrarlo en el recuadro de cambio.
- Si el método es "tarjeta" o "transferencia", el cambio debe ser $0.00 y el campo de monto se puede deshabilitar.
- El monto total debe venir desde PHP (puedes usar un atributo `data-total` en el formulario o un input hidden).

### 4. Actualizar `models/Orden.php` y `models/Mesa.php` (si es necesario)
- Verificar que existan los métodos para:
  - Obtener la orden activa por mesa.
  - Obtener el detalle de una orden.
  - Actualizar el estado de una orden (a 'pagado').
  - Actualizar la disponibilidad de una mesa (a 'libre').
- Si faltan, créalos usando PDO con sentencias preparadas.

## Restricciones
- NO uses Composer.
- Usa SOLO PDO con sentencias preparadas.
- Comentarios en español.
- Sigue la nomenclatura de AGENTS.md.
- Verifica la sintaxis PHP al final.

## Salida esperada
1. Resumen de archivos modificados.
2. Confirmación de sintaxis válida.
3. Dime "Lógica de la HU-05 implementada. Listo para probar".