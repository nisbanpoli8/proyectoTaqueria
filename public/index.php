<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/AccessController.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';

const SESSION_TIMEOUT_SECONDS = 600;
const SESSION_WARNING_SECONDS = 10;

$action = $_GET['action'] ?? 'login';
$accessController = new AccessController();

if (isset($_SESSION['usuario_id'])) {
    $now = time();
    $lastActivity = (int) ($_SESSION['ultimo_acceso'] ?? $now);

    if (($now - $lastActivity) >= SESSION_TIMEOUT_SECONDS) {
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
        header('Location: index.php?action=login&expired=1');
        exit;
    }

    $_SESSION['ultimo_acceso'] = $now;
}

if ($action === 'logout') {
    (new AuthController())->logout();
}

if ($action === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        (new AuthController())->login();
    }

    $error = $_SESSION['flash_error'] ?? '';
    unset($_SESSION['flash_error']);
    $expired = isset($_GET['expired']) ? 'La sesión cerró por inactividad.' : '';

    require_once __DIR__ . '/../views/login.php';
    return;
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?action=login');
    exit;
}

if ($action === 'home') {
    $accessController->redirectByRole();
}

if ($action === 'admin') {
    $accessController->requireRole(['Administrador']);
    require_once __DIR__ . '/../views/admin_panel.php';
    return;
}

if ($action === 'caja') {
    $accessController->requireRole(['Cajero']);
    require_once __DIR__ . '/../views/caja.php';
    return;
}

if ($action === 'mesas') {
    $accessController->requireRole(['Mesero']);
    require_once __DIR__ . '/../views/mapa_mesas.php';
    return;
}

if ($action === 'usuarios') {
    $accessController->requireRole(['Administrador']);
    (new UsuarioController())->index();
    return;
}

if ($action === 'usuarios_nuevo') {
    $accessController->requireRole(['Administrador']);
    (new UsuarioController())->nuevo();
    return;
}

if ($action === 'usuarios_guardar') {
    $accessController->requireRole(['Administrador']);
    (new UsuarioController())->guardar();
    return;
}

if ($action === 'usuarios_editar') {
    $accessController->requireRole(['Administrador']);
    (new UsuarioController())->editar();
    return;
}

if ($action === 'usuarios_desactivar') {
    $accessController->requireRole(['Administrador']);
    (new UsuarioController())->desactivar();
    return;
}

$accessController->redirectByRole();
