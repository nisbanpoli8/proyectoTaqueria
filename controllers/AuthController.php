<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/AccessController.php';

class AuthController
{
    private const SESSION_TIMEOUT = 900;
    private const SESSION_WARNING = 10;

    public static function getSessionTimeout(): int
    {
        return self::SESSION_TIMEOUT;
    }

    public static function getSessionWarning(): int
    {
        return self::SESSION_WARNING;
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToLogin('login');
            return;
        }

        $usuario = trim((string) ($_POST['usuario'] ?? ''));
        $contrasenia = (string) ($_POST['contrasenia'] ?? '');

        if ($usuario === '' || $contrasenia === '') {
            $_SESSION['flash_error'] = 'Usuario o contraseña incorrectos';
            $this->redirectToLogin('login');
            return;
        }

        $usuarioModel = new Usuario();
        $usuarioEncontrado = $usuarioModel->buscarPorUsuario($usuario);

        if (
            $usuarioEncontrado === null
            || (int) $usuarioEncontrado['activo'] !== 1
            || !password_verify($contrasenia, (string) $usuarioEncontrado['contrasenia'])
        ) {
            $_SESSION['flash_error'] = 'Usuario o contraseña incorrectos';
            $this->redirectToLogin('login');
            return;
        }

        $_SESSION['usuario_id'] = (int) $usuarioEncontrado['id'];
        $_SESSION['usuario'] = (string) $usuarioEncontrado['usuario'];
        $_SESSION['nombre_completo'] = trim((string) $usuarioEncontrado['nombre'] . ' ' . (string) $usuarioEncontrado['apellido']);
        $_SESSION['rol'] = (string) $usuarioEncontrado['nombre_rol'];
        $_SESSION['ultimo_acceso'] = time();
        $_SESSION['session_timeout'] = self::SESSION_TIMEOUT;
        $_SESSION['session_warning'] = self::SESSION_WARNING;

        $accessController = new AccessController();
        $accessController->redirectByRole();
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: index.php?action=login');
        exit;
    }

    private function redirectToLogin(string $action): void
    {
        header('Location: index.php?action=' . $action);
        exit;
    }
}
