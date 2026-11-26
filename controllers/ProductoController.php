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

        // Función para mostrar el formulario de edición
    public function editar($id) {
        require_once __DIR__ . '/../config/database.php';
        $pdo = getDatabaseConnection();
        
        // Usamos 'id' porque es como se llama tu llave primaria
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            header('Location: index.php?action=productos');
            exit;
        }

        $stmtCat = $pdo->query("SELECT * FROM categorias");
        $categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/productos/editar.php';
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../config/database.php';
            $pdo = getDatabaseConnection();

            $id = (int)$_POST['id']; 
            $nombre = $_POST['nombre'];
            $idCategoria = (int)$_POST['idCategoria'];
            $precio = (float)$_POST['precio'];
            $descripcion = $_POST['descripcion'];
            $disponible = isset($_POST['disponible']) ? 1 : 0;

            // Actualizamos usando 'nombre_producto' y 'id_categoria'
            $sql = "UPDATE productos SET nombre_producto = :nombre, id_categoria = :idCategoria, 
                    precio = :precio, descripcion = :descripcion, disponible = :disponible 
                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':idCategoria' => $idCategoria,
                ':precio' => $precio,
                ':descripcion' => $descripcion,
                ':disponible' => $disponible,
                ':id' => $id
            ]);

            header('Location: index.php?action=productos');
            exit;
        }
    }

    public function index(): void
    {
        // Consultamos los productos con el nombre de su categoría
        $sql = "SELECT p.*, c.nombre_categoria 
                FROM productos p 
                LEFT JOIN categorias c ON p.id_categoria = c.id 
                ORDER BY p.id DESC";
        
        $stmt = $this->pdo->query($sql);
        $productos = $stmt->fetchAll();

        // Pasamos los productos a la vista
        require __DIR__ . '/../views/productos/lista.php';
    }

    
}