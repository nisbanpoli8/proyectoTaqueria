<?php

declare(strict_types=1);

$usuarios = $usuarios ?? [];
$error = $error ?? '';
$success = $success ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0">Usuarios</h1>
                <div>
                    <a href="index.php?action=usuarios_nuevo" class="btn btn-success btn-sm me-2">+ Nuevo usuario</a>
                    <a href="index.php?action=logout" class="btn btn-danger btn-sm">Cerrar sesión</a>
                </div>
            </div>
            <div class="card-body">
                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <?php if ($success !== ''): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($usuarios === []): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No hay usuarios registrados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?php echo (int) $usuario['id']; ?></td>
                                        <td><?php echo htmlspecialchars((string) $usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars((string) $usuario['apellido'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars((string) $usuario['usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars((string) ($usuario['nombre_rol'] ?? 'Sin rol'), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <?php if ((int) ($usuario['activo'] ?? 0) === 1): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="index.php?action=usuarios_editar&id=<?php echo (int) $usuario['id']; ?>" class="btn btn-sm btn-primary me-2">Editar</a>
                                            <a href="index.php?action=usuarios_desactivar&id=<?php echo (int) $usuario['id']; ?>" class="btn btn-sm btn-outline-danger">Desactivar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
