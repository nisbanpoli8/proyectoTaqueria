document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[action="index.php?action=caja_procesar_pago"]');
    if (!form) return;

    const totalInput = form.querySelector('input[name="total"]');
    const montoInput = document.getElementById('monto_recibido');
    const cambioValor = document.getElementById('cambio-valor');
    const btnSubmit = form.querySelector('button[type="submit"]');

    const formatearMoneda = (valor) => `$${Number(valor).toLocaleString('es-MX', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })}`;

    const calcularCambio = () => {
        const total = Number.parseFloat(totalInput.value || '0');
        const montoRecibido = Number.parseFloat(montoInput.value || '0');

        // Calcular cambio (nunca menor a 0)
        const cambio = Math.max(montoRecibido - total, 0);
        cambioValor.textContent = formatearMoneda(cambio);

        // ✅ VALIDACIÓN: Si el monto es menor al total o supera los 1000
        if (montoRecibido < total) {
            btnSubmit.disabled = true;
            btnSubmit.style.opacity = '0.5';
            btnSubmit.style.cursor = 'not-allowed';
            montoInput.classList.add('border-red-500'); // Pone el borde rojo
        } else if (montoRecibido > 1000) {
            btnSubmit.disabled = true;
            btnSubmit.style.opacity = '0.5';
            btnSubmit.style.cursor = 'not-allowed';
            montoInput.classList.add('border-red-500');
        } else {
            btnSubmit.disabled = false;
            btnSubmit.style.opacity = '1';
            btnSubmit.style.cursor = 'pointer';
            montoInput.classList.remove('border-red-500');
        }
    };

    // Escuchar cambios en el input
    montoInput.addEventListener('input', calcularCambio);

    // Calcular al cargar la página
    calcularCambio();
});