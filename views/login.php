<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Taquería Tacos Tacos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" sizes="64x64" href="public/assets/images/logoprueba.png">
</head>
<body class="login-body">
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="login-card">
            <div class="login-header text-center">
                <div class="brand-badge mx-auto mb-3"><img src="public/assets/images/logoprueba.png" alt="Logo Taqueria Tacos Tacos"></div>
                <h1 class="brand-title">Taquería Tacos Tacos</h1>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger rounded-pill text-center mb-4" role="alert">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($expired)): ?>
                <div class="alert alert-warning rounded-pill text-center mb-4" role="alert">
                    <?php echo htmlspecialchars($expired, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
            
            <br><br>
            <form id="login-form" method="POST" action="index.php?action=login" novalidate>
                <div class="mb-4">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input
                        type="text"
                        id="usuario"
                        name="usuario"
                        class="form-control <?php echo !empty($error) ? 'is-invalid' : ''; ?>"
                        placeholder="Ingrese usuario"
                        value="<?php echo htmlspecialchars($_POST['usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label for="contrasenia" class="form-label">Contraseña</label>
                    <input
                        type="password"
                        id="contrasenia"
                        name="contrasenia"
                        class="form-control <?php echo !empty($error) ? 'is-invalid' : ''; ?>"
                        placeholder="Ingrese contraseña"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                    ENTRAR AL SISTEMA
                </button>
            </form>

            <p class="footer-note text-center mt-4 mb-0">
                ¿Problemas para conectarse? Contáctese con el <strong>administrador</strong>
            </p>
        </div>
    </div>

    <script src="assets/js/login.js" defer></script>
</body>
</html>
