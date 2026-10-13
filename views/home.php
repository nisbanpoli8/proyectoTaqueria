<?php
// views/home.php

$usuario = $_SESSION['usuario'] ?? 'Usuario';
$nombreCompleto = $_SESSION['nombre_completo'] ?? $usuario;
$rol = $_SESSION['rol'] ?? 'Usuario';
$tiempoRestanteMs = SESSION_TIMEOUT_SECONDS * 1000;
$warningTimeMs = SESSION_WARNING_SECONDS * 1000;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Comandas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="dashboard-body">
    <div class="container py-5">
        <div class="dashboard-card mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="dashboard-label mb-1">Usuario activo</p>
                    <h1 class="dashboard-title mb-0">¡Bienvenido!</h1>
                </div>
                <a href="index.php?action=logout" class="btn btn-danger btn-lg">Cerrar sesión</a>
            </div>

            <div class="alert alert-warning mb-4">
                <strong><?php echo htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8'); ?></strong>
                <span class="ms-2"><?php echo htmlspecialchars($rol, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>

            <div class="session-banner">
                <span class="me-2">⏱️</span>
                <span>La sesión se cerrará por inactividad en <strong id="countdown-text">10 segundos</strong>.</span>
            </div>
        </div>
    </div>

    <script>
        const SESSION_TIMEOUT_MS = <?php echo (int) $tiempoRestanteMs; ?>;
        const SESSION_WARNING_MS = <?php echo (int) $warningTimeMs; ?>;
        const countdownText = document.getElementById('countdown-text');

        let remainingMs = SESSION_TIMEOUT_MS;

        const updateCountdown = () => {
            const secondsLeft = Math.max(0, Math.ceil(remainingMs / 1000));
            countdownText.textContent = secondsLeft + ' segundo' + (secondsLeft === 1 ? '' : 's');

            if (remainingMs <= SESSION_WARNING_MS) {
                countdownText.closest('.session-banner').classList.add('warning');
            }
        };

        updateCountdown();

        const timer = setInterval(() => {
            remainingMs -= 1000;
            updateCountdown();

            if (remainingMs <= 0) {
                clearInterval(timer);
                window.location.href = 'index.php?action=logout';
            }
        }, 1000);
    </script>
</body>
</html>