<?php

declare(strict_types=1);

$error = $error ?? '';
$success = $success ?? '';
$usuarioActual = $usuarioActual ?? [
    'id' => '',
    'nombre' => '',
    'apellido' => '',
    'usuario' => '',
    'id_rol' => '',
    'activo' => 1,
];
$modo = (isset($usuarioActual['id']) && (int) $usuarioActual['id'] > 0) ? 'editar' : 'crear';
$roles = [
    ['id' => 1, 'nombre_rol' => 'Administrador'],
    ['id' => 2, 'nombre_rol' => 'Cajero'],
    ['id' => 3, 'nombre_rol' => 'Mesero'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $modo === 'editar' ? 'Editar Usuario' : 'Nuevo Usuario'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm border-0 mx-auto" style="max-width: 720px;">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0"><?php echo $modo === 'editar' ? 'Editar Usuario' : 'Crear Usuario'; ?></h1>
                <a href="index.php?action=usuarios" class="btn btn-light btn-sm">Volver</a>
            </div>
            <div class="card-body">
                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <?php if ($success !== ''): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?action=usuarios_guardar">
                    <?php if ($modo === 'editar'): ?>
                        <input type="hidden" name="id" value="<?php echo (int) $usuarioActual['id']; ?>">
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars((string) $usuarioActual['nombre'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" id="apellido" name="apellido" class="form-control" value="<?php echo htmlspecialchars((string) $usuarioActual['apellido'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" id="usuario" name="usuario" class="form-control" value="<?php echo htmlspecialchars((string) $usuarioActual['usuario'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="id_rol" class="form-label">Rol</label>
                            <select id="id_rol" name="id_rol" class="form-select" required>
                                <option value="">Seleccione un rol</option>
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?php echo (int) $rol['id']; ?>" <?php echo ((string) $usuarioActual['id_rol'] === (string) $rol['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars((string) $rol['nombre_rol'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="contrasenia" class="form-label">Contraseña</label>
                            <input type="password" id="contrasenia" name="contrasenia" class="form-control" minlength="8" <?php echo $modo === 'crear' ? 'required' : ''; ?>>
                            <?php if ($modo === 'editar'): ?>
                                <small class="text-muted">Dejar vacío para conservar la contraseña actual.</small>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label for="repetir_contrasenia" class="form-label">Confirmar contraseña</label>
                            <input type="password" id="repetir_contrasenia" name="repetir_contrasenia" class="form-control" minlength="8">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="index.php?action=usuarios" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary"><?php echo $modo === 'editar' ? 'Guardar cambios' : 'Crear usuario'; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
