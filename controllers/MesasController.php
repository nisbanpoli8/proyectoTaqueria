<?php
// controllers/MesasController.php

declare(strict_types=1);

require_once __DIR__ . '/../models/Mesa.php';

class MesasController
{
    // ✅ CORREGIDO: constantes en minúsculas
    private const ESTADO_LIBRE = 'libre';
    private const ESTADO_OCUPADA = 'ocupada';
    private const ESTADO_CUENTA = 'reservada'; // ⚠️ Tu BD usa 'reservada' en lugar de 'cuenta'

    public function index(): void
    {
        $mesas = $this->obtenerMesas();
        $filtros = [
            'todas' => count($mesas),
            'libres' => 0,
            'ocupadas' => 0,
            'cuenta' => 0,
        ];

        foreach ($mesas as &$mesa) {
            $estado = $this->estadoMesa((int) $mesa['id']);
            $mesa['estado'] = $estado;

            if ($estado === self::ESTADO_LIBRE) {
                $filtros['libres']++;
            } elseif ($estado === self::ESTADO_OCUPADA) {
                $filtros['ocupadas']++;
            } elseif ($estado === self::ESTADO_CUENTA) {
                $filtros['cuenta']++;
            }
        }
        unset($mesa);

        $vista = $_GET['vista'] ?? 'todas';

        require_once __DIR__ . '/../views/mesas.php';
    }

    public function ocupar(): void
    {
        $idMesa = (int) ($_POST['id_mesa'] ?? 0);
        $comensales = (int) ($_POST['comensales'] ?? 0);

        if ($idMesa > 0) {
            $modelo = new Mesa();
            // ✅ CORREGIDO: actualiza la BD en lugar de la sesión
            $modelo->actualizarEstado($idMesa, self::ESTADO_OCUPADA);

            if ($comensales > 0) {
                $_SESSION['mesas_comensales'][$idMesa] = $comensales;
            }
        }

        header('Location: index.php?action=mesas');
        exit;
    }

    public function liberar(): void
    {
        $idMesa = (int) ($_GET['id'] ?? 0);

        if ($idMesa > 0) {
            $modelo = new Mesa();
            // ✅ CORREGIDO: actualiza la BD en lugar de la sesión
            $modelo->actualizarEstado($idMesa, self::ESTADO_LIBRE);
            unset($_SESSION['mesas_comensales'][$idMesa]);
        }

        header('Location: index.php?action=mesas');
        exit;
    }

    public function solicitarCuenta(): void
    {
        $idMesa = (int) ($_GET['id'] ?? 0);

        if ($idMesa > 0) {
            $modelo = new Mesa();
            // ✅ CORREGIDO: actualiza la BD en lugar de la sesión
            $modelo->actualizarEstado($idMesa, self::ESTADO_CUENTA);
        }

        header('Location: index.php?action=mesas');
        exit;
    }

    private function obtenerMesas(): array
    {
        $modelo = new Mesa();
        $mesas = $modelo->obtenerTodas();

        foreach ($mesas as &$mesa) {
            $mesa['estado'] = $this->estadoMesa((int) $mesa['id']);
            $mesa['comensales'] = $_SESSION['mesas_comensales'][(int) $mesa['id']] ?? 0;
        }
        unset($mesa);

        return $mesas;
    }

    /**
     * ✅ CORREGIDO: lee el estado directamente de la BD (no de la sesión)
     * y lo devuelve en minúsculas (como está en el ENUM).
     */
    private function estadoMesa(int $idMesa): string
    {
        $modelo = new Mesa();
        $mesa = $modelo->obtenerPorId($idMesa);

        if ($mesa === null) {
            return self::ESTADO_LIBRE;
        }

        $disponibilidad = strtolower((string) ($mesa['disponibilidad'] ?? 'libre'));

        return in_array($disponibilidad, [self::ESTADO_LIBRE, self::ESTADO_OCUPADA, self::ESTADO_CUENTA], true)
            ? $disponibilidad
            : self::ESTADO_LIBRE;
    }
}