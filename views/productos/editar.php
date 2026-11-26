<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto | Tacos Roy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <main class="flex-1 p-10">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold mb-6 text-gray-800">Editar Producto</h2>
            
            <form action="index.php?action=productos_actualizar" method="POST" class="space-y-4">
                
                <!-- ID oculto -->
                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

                <div>
                    <label class="block font-bold mb-2">Nombre del Producto</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre_producto'] ?? ''); ?>" required class="w-full p-2 border rounded">
                </div>

                <div>
                    <label class="block font-bold mb-2">Categoría</label>
                    <select name="idCategoria" required class="w-full p-2 border rounded">
                        <?php foreach ($categorias as $cat): ?>
                            <!-- Usamos $cat['id'] y $cat['nombre_categoria'] -->
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $producto['id_categoria']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block font-bold mb-2">Precio</label>
                    <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>" required class="w-full p-2 border rounded">
                </div>

                <div>
                    <label class="block font-bold mb-2">Descripción</label>
                    <textarea name="descripcion" class="w-full p-2 border rounded"><?php echo htmlspecialchars($producto['descripcion'] ?? ''); ?></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="disponible" id="disponible" <?php echo ($producto['disponible'] ?? 0) ? 'checked' : ''; ?>>
                    <label for="disponible" class="font-bold">Producto Disponible</label>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">Guardar Cambios</button>
                    <a href="index.php?action=productos" class="bg-gray-400 text-white px-6 py-2 rounded font-bold hover:bg-gray-500">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>