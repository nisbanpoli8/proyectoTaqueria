<?php
// public/index.php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../controllers/AuthController.php';

const SESSION_TIMEOUT_SECONDS = 600;
const SESSION_WARNING_SECONDS = 10;

$action = $_GET['action'] ?? 'login';

// Cierre por inactividad
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

// Rutas
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

// Verificar sesión activa para todas las rutas privadas
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?action=login');
    exit;
}

if ($action === 'home') {
    require_once __DIR__ . '/../views/home.php';
    return;
}

// Acción por defecto (redirige a home)
header('Location: index.php?action=home');
exit;