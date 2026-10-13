<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Producto
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? getDatabaseConnection();
    }

    public function listar(): array
    {
        $sql = 'SELECT p.*, c.nombre_categoria
                FROM productos p
                INNER JOIN categorias c ON c.id = p.id_categoria
                ORDER BY p.nombre_producto ASC';

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    public function obtenerPorCategoria(int $idCategoria): array
    {
        $sql = 'SELECT p.*, c.nombre_categoria
                FROM productos p
                INNER JOIN categorias c ON c.id = p.id_categoria
                WHERE p.id_categoria = :id_categoria
                AND p.disponible = 1
                ORDER BY p.nombre_producto ASC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_categoria' => $idCategoria]);

        return $stmt->fetchAll();
    }
}
