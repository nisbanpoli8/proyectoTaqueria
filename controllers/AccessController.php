<?php

declare(strict_types=1);

class AccessController
{
    public function requireLogin(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    public function requireRole(array $rolesPermitidos): void
    {
        $this->requireLogin();

        $rolActual = trim((string) ($_SESSION['rol'] ?? ''));

        if ($rolesPermitidos === []) {
            return;
        }

        foreach ($rolesPermitidos as $rolPermitido) {
            if ($rolActual === (string) $rolPermitido) {
                return;
            }
        }

        $_SESSION['flash_error'] = 'No tienes permisos para acceder a esta sección.';
        $this->redirectByRole();
    }

    public function redirectByRole(): void
    {
        $rolActual = trim((string) ($_SESSION['rol'] ?? ''));

        switch ($rolActual) {
            case 'Administrador':
                header('Location: index.php?action=admin');
                break;
            case 'Cajero':
                header('Location: index.php?action=caja');
                break;
            case 'Mesero':
                header('Location: index.php?action=mesas');
                break;
            default:
                header('Location: index.php?action=login');
                break;
        }

        exit;
    }
}
