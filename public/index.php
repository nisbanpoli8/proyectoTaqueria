<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/AccessController.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/MesasController.php';
require_once __DIR__ . '/../controllers/ComandaController.php';
require_once __DIR__ . '/../controllers/CajaController.php';

const SESSION_TIMEOUT_SECONDS = 900;
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

if ($action === 'mesas') {
    $accessController->requireRole(['Mesero','Cajero']);
    (new MesasController())->index();
    return;
}

if ($action === 'mesas_ocupar') {
    $accessController->requireRole(['Mesero']);
    (new MesasController())->ocupar();
    return;
}

if ($action === 'mesas_liberar') {
    $accessController->requireRole(['Mesero']);
    (new MesasController())->liberar();
    return;
}

if ($action === 'mesas_solicitar_cuenta') {
    $accessController->requireRole(['Mesero']);
    (new MesasController())->solicitarCuenta();
    return;
}

if ($action === 'cocina') {
    $accessController->requireRole(['Taquero', 'Mesero', 'Administrador']);
    require_once __DIR__ . '/../controllers/CocinaController.php';
    (new CocinaController())->index();
    return;
}

if ($action === 'cocina_actualizar_estado') {
    $accessController->requireRole(['Taquero', 'Mesero', 'Administrador']);
    require_once __DIR__ . '/../controllers/CocinaController.php';
    (new CocinaController())->actualizarEstado();
    return;
}

if ($action === 'cocina_listos') {
    $accessController->requireRole(['Taquero', 'Mesero', 'Administrador']);
    require_once __DIR__ . '/../controllers/CocinaController.php';
    (new CocinaController())->listos();
    return;
}

// --- Panel de Caja (Lista de mesas para cobrar) ---
if ($action === 'caja_panel') {
    $accessController->requireRole(['Cajero']);
    require_once __DIR__ . '/../controllers/CajaController.php';
    (new CajaController())->lista(); 
     return;
}

// --- Caja (Cobro Real) ---
if ($action === 'caja') {
    $accessController->requireRole(['Cajero', 'Administrador']);
    require_once __DIR__ . '/../controllers/CajaController.php';
    (new CajaController())->index();
    return;
}

if ($action === 'caja_procesar_pago') {
    $accessController->requireRole(['Cajero', 'Administrador']);
    require_once __DIR__ . '/../controllers/CajaController.php';
    (new CajaController())->procesarPago();
    return;
}

if ($action === 'ticket') {
    $accessController->requireRole(['Cajero', 'Administrador']);
    require_once __DIR__ . '/../controllers/CajaController.php';
    (new CajaController())->ticket();
    return;
}

if ($action === 'comanda') {
    $accessController->requireRole(['Mesero']);
    (new ComandaController())->index();
    return;
}

if ($action === 'comanda_agregar') {
    $accessController->requireRole(['Mesero']);
    (new ComandaController())->agregarProducto();
    return;
}

if ($action === 'comanda_eliminar') {
    $accessController->requireRole(['Mesero']);
    (new ComandaController())->eliminarProducto();
    return;
}

if ($action === 'comanda_enviar') {
    $accessController->requireRole(['Mesero']);
    (new ComandaController())->enviarACocina();
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
