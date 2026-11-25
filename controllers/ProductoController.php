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