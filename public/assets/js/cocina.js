document.addEventListener('DOMContentLoaded', () => {
    const lastUpdated = document.getElementById('last-updated');
    const successNotice = document.getElementById('flash-success');
    const avisoActualizacion = document.getElementById('aviso-actualizacion');

    // ⏱️ CONFIGURACIÓN DEL TIEMPO (en milisegundos)
    // 20000 = 20 segundos.
    const TIEMPO_ACTUALIZACION_MS = 20000;
    const SEGUNDOS = TIEMPO_ACTUALIZACION_MS / 1000;

    const showLastUpdated = () => {
        if (lastUpdated) {
            lastUpdated.textContent = 'Actualizado: ' + new Date().toLocaleTimeString('es-MX', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            });
        }
    };

    // Función para mostrar el aviso flotante
    const mostrarAviso = (mensaje) => {
        if (!avisoActualizacion) return;
        avisoActualizacion.textContent = mensaje;
        avisoActualizacion.classList.remove('hidden');

        // Ocultar el aviso después de 3 segundos
        setTimeout(() => {
            avisoActualizacion.classList.add('hidden');
        }, 3000);
    };

    showLastUpdated();
    mostrarAviso(`El tablero se actualiza cada ${SEGUNDOS} segundos.`);

    if (successNotice) {
        window.setTimeout(() => {
            successNotice.remove();
        }, 5000);
    }

    // Bucle de actualización automática
    window.setInterval(() => {
        mostrarAviso('🔄 Actualizando tablero...');
        setTimeout(() => {
            window.location.reload();
        }, 500); // Espera medio segundo antes de recargar
    }, TIEMPO_ACTUALIZACION_MS);
});