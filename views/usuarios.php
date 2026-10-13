<?php

declare(strict_types=1);

$usuario = $_SESSION['nombre_completo'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0">Gestión de Usuarios</h1>
                <a href="index.php?action=logout" class="btn btn-danger btn-sm">Cerrar sesión</a>
            </div>
            <div class="card-body">
                <p class="mb-3">Administrador activo: <strong><?php echo htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8'); ?></strong>.</p>
                <div class="alert alert-primary mb-0">
                    Vista de usuarios disponible solo para el rol Administrador.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
