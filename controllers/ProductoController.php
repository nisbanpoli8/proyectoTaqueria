<?php
// controllers/ProductoController.php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class ProductoController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getDatabaseConnection();
    }

    public function index(): void
    {
        // Leemos si viene un filtro por categoría
        $categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;

        $sql = "SELECT p.*, c.nombre_categoria 
                FROM productos p 
                LEFT JOIN categorias c ON p.id_categoria = c.id";
        
        if ($categoria_id > 0) {
            $sql .= " WHERE p.id_categoria = :id_categoria";
        }
        
        $sql .= " ORDER BY p.id DESC";
        
        $stmt = $this->pdo->prepare($sql);
        
        if ($categoria_id > 0) {
            $stmt->execute([':id_categoria' => $categoria_id]);
        } else {
            $stmt->execute();
        }
        
        $productos = $stmt->fetchAll();

        // Traemos las categorías para el filtro visual
        $stmtCat = $this->pdo->query("SELECT * FROM categorias");
        $categorias = $stmtCat->fetchAll();

        require __DIR__ . '/../views/productos/lista.php';
    }

    public function nuevo(): void
    {
        // Traemos las categorías para el formulario
        $stmtCat = $this->pdo->query("SELECT * FROM categorias");
        $categorias = $stmtCat->fetchAll();

        require __DIR__ . '/../views/productos/nuevo.php';
    }

    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $idCategoria = (int)($_POST['idCategoria'] ?? 0);
            $precio = (float)($_POST['precio'] ?? 0);
            $descripcion = $_POST['descripcion'] ?? '';
            $disponible = isset($_POST['disponible']) ? 1 : 0;

            // Validación básica (Criterio de aceptación HU-06)
            if (empty($nombre) || $idCategoria <= 0 || $precio <= 0) {
                $_SESSION['flash_error'] = 'Todos los campos obligatorios deben estar llenos.';
                header('Location: index.php?action=productos_nuevo');
                exit;
            }

            $sql = "INSERT INTO productos (nombre_producto, id_categoria, precio, descripcion, disponible) 
                    VALUES (:nombre, :idCategoria, :precio, :descripcion, :disponible)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':idCategoria' => $idCategoria,
                ':precio' => $precio,
                ':descripcion' => $descripcion,
                ':disponible' => $disponible
            ]);

            $_SESSION['flash_success'] = 'Producto registrado correctamente.';
            header('Location: index.php?action=productos');
            exit;
        }
    }

    public function editar($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            header('Location: index.php?action=productos');
            exit;
        }

        $stmtCat = $this->pdo->query("SELECT * FROM categorias");
        $categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/productos/editar.php';
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id']; 
            $nombre = $_POST['nombre'];
            $idCategoria = (int)$_POST['idCategoria'];
            $precio = (float)$_POST['precio'];
            $descripcion = $_POST['descripcion'];
            $disponible = isset($_POST['disponible']) ? 1 : 0;

            $sql = "UPDATE productos SET nombre_producto = :nombre, id_categoria = :idCategoria, 
                    precio = :precio, descripcion = :descripcion, disponible = :disponible 
                    WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':idCategoria' => $idCategoria,
                ':precio' => $precio,
                ':descripcion' => $descripcion,
                ':disponible' => $disponible,
                ':id' => $id
            ]);

            $_SESSION['flash_success'] = 'Producto actualizado correctamente.';
            header('Location: index.php?action=productos');
            exit;
        }
    }
}