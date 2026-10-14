document.addEventListener('DOMContentLoaded', () => {
    const lastUpdated = document.getElementById('last-updated');
    const refreshNow = document.getElementById('refresh-now');
    const successNotice = document.getElementById('flash-success');

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

    showLastUpdated();

    if (refreshNow) {
        refreshNow.addEventListener('click', () => window.location.reload());
    }

    if (successNotice) {
        window.setTimeout(() => {
            successNotice.remove();
        }, 5000);
    }

    window.setInterval(() => {
        window.location.reload();
    }, 15000);
});
