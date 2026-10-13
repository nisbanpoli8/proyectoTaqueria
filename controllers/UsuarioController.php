<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    public function index(): void
    {
        $modelo = new Usuario();
        $usuarios = $modelo->listar();
        $error = $_SESSION['flash_error'] ?? '';
        $success = $_SESSION['flash_success'] ?? '';

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);

        require_once __DIR__ . '/../views/usuarios/lista.php';
    }

    public function nuevo(): void
    {
        $usuario = [
            'id' => '',
            'nombre' => '',
            'apellido' => '',
            'usuario' => '',
            'id_rol' => '',
            'activo' => 1,
        ];

        $error = $_SESSION['flash_error'] ?? '';
        $success = $_SESSION['flash_success'] ?? '';
        unset($_SESSION['flash_error'], $_SESSION['flash_success']);

        require_once __DIR__ . '/../views/usuarios/formulario.php';
    }

    public function guardar(): void
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $apellido = trim((string) ($_POST['apellido'] ?? ''));
        $usuarioLogin = trim((string) ($_POST['usuario'] ?? ''));
        $rol = isset($_POST['id_rol']) ? (int) $_POST['id_rol'] : 0;
        $contrasenia = (string) ($_POST['contrasenia'] ?? '');
        $repetirContrasenia = (string) ($_POST['repetir_contrasenia'] ?? '');

        $errores = $this->validarDatos($nombre, $apellido, $usuarioLogin, $rol, $contrasenia, $repetirContrasenia, $id);

        if ($errores !== []) {
            $_SESSION['flash_error'] = implode(' ', $errores);
            $_SESSION['usuario_form'] = [
                'id' => $id,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'usuario' => $usuarioLogin,
                'id_rol' => $rol,
            ];

            $this->redirectToForm($id);
        }

        $modelo = new Usuario();

        if ($id > 0) {
            $datos = [
                'id_rol' => $rol,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'usuario' => $usuarioLogin,
            ];

            if ($contrasenia !== '') {
                $datos['contrasenia'] = $contrasenia;
            }

            $modelo->editar($id, $datos);
            $_SESSION['flash_success'] = 'Usuario actualizado correctamente.';
        } else {
            $modelo->crear($rol, $nombre, $apellido, $usuarioLogin, $contrasenia, true);
            $_SESSION['flash_success'] = 'Usuario creado correctamente.';
        }

        header('Location: index.php?action=usuarios');
        exit;
    }

    public function editar(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            header('Location: index.php?action=usuarios');
            exit;
        }

        $modelo = new Usuario();
        $usuario = $modelo->listar();
        $usuarioActual = null;

        foreach ($usuario as $item) {
            if ((int) $item['id'] === $id) {
                $usuarioActual = $item;
                break;
            }
        }

        if ($usuarioActual === null) {
            header('Location: index.php?action=usuarios');
            exit;
        }

        $error = $_SESSION['flash_error'] ?? '';
        $success = $_SESSION['flash_success'] ?? '';
        unset($_SESSION['flash_error'], $_SESSION['flash_success']);

        require_once __DIR__ . '/../views/usuarios/formulario.php';
    }

    public function desactivar(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id > 0) {
            $modelo = new Usuario();
            $modelo->desactivar($id);
            $_SESSION['flash_success'] = 'Usuario desactivado correctamente.';
        }

        header('Location: index.php?action=usuarios');
        exit;
    }

    private function validarDatos(
        string $nombre,
        string $apellido,
        string $usuarioLogin,
        int $rol,
        string $contrasenia,
        string $repetirContrasenia,
        int $idActual
    ): array {
        $errores = [];

        if ($nombre === '') {
            $errores[] = 'El nombre es obligatorio.';
        }

        if ($apellido === '') {
            $errores[] = 'Los apellidos son obligatorios.';
        }

        if ($usuarioLogin === '') {
            $errores[] = 'El usuario es obligatorio.';
        }

        if ($rol <= 0) {
            $errores[] = 'Debe seleccionar un rol.';
        }

        $modelo = new Usuario();
        $usuarioExistente = $modelo->buscarPorUsuario($usuarioLogin);

        if ($usuarioExistente !== null && (int) $usuarioExistente['id'] !== $idActual) {
            $errores[] = 'El nombre de usuario ya está registrado.';
        }

        if ($idActual <= 0 && $contrasenia === '') {
            $errores[] = 'La contraseña es obligatoria.';
        }

        if ($contrasenia !== '' && strlen($contrasenia) < 8) {
            $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
        }

        if ($contrasenia !== '' && $contrasenia !== $repetirContrasenia) {
            $errores[] = 'Las contraseñas no coinciden.';
        }

        return $errores;
    }

    private function redirectToForm(int $id): void
    {
        if ($id > 0) {
            header('Location: index.php?action=usuarios_editar&id=' . $id);
        } else {
            header('Location: index.php?action=usuarios_nuevo');
        }
        exit;
    }
}
