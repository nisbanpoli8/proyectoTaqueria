document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[action="index.php?action=caja_procesar_pago"]');

    if (!form) {
        return;
    }

    const totalInput = form.querySelector('input[name="total"]');
    const montoInput = form.querySelector('input[name="monto_recibido"]');
    const cambioValor = document.getElementById('cambio-valor');
    const metodoPagos = form.querySelectorAll('input[name="metodo_pago"]');

    if (!totalInput || !montoInput || !cambioValor) {
        return;
    }

    const formatearMoneda = (valor) => `$${Number(valor).toLocaleString('es-MX', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })}`;

    const calcularCambio = () => {
        if (!totalInput || !montoInput || !cambioValor) {
            return;
        }

        const total = Number.parseFloat(totalInput.value || '0');
        const metodoSeleccionado = form.querySelector('input[name="metodo_pago"]:checked')?.value ?? 'efectivo';

        if (metodoSeleccionado === 'efectivo') {
            montoInput.disabled = false;
            const montoRecibido = Number.parseFloat(montoInput.value || '0');
            const cambio = Math.max(montoRecibido - total, 0);
            cambioValor.textContent = formatearMoneda(cambio);
            return;
        }

        montoInput.disabled = true;
        montoInput.value = total.toFixed(2);
        cambioValor.textContent = formatearMoneda(0);
    };

    metodoPagos.forEach((radio) => {
        radio.addEventListener('change', calcularCambio);
    });

    montoInput.addEventListener('input', calcularCambio);
    calcularCambio();
});
