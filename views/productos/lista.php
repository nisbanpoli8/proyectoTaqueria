<?php
// views/productos/lista.php
declare(strict_types=1);

$productos = $productos ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-brand { font-family: 'Lilita One', cursive; }
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-white flex min-h-screen">

    <!-- ================= SIDEBAR ================= -->
    <aside class="w-64 bg-[#FBBF24] min-h-screen flex flex-col p-4 shadow-xl z-10">
        <div class="flex flex-col items-center text-center mb-8 mt-4">
            <img src="assets/images/logoTacosRoy.png" alt="Logo Tacos Roy" class="w-24 mb-2">
            <h1 class="text-2xl font-bold text-black font-brand">Tacos Roy</h1>
            <h2 class="text-md font-bold text-black">Admin Panel</h2>
        </div>

        <nav class="flex flex-col gap-2 flex-grow">
            <a href="index.php?action=admin" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors">
                <i class="fa-solid fa-house text-xl w-6 text-center"></i> Inicio
            </a>
            <!-- Menú Activo -->
            <a href="index.php?action=productos" class="flex items-center gap-4 px-4 py-3 text-black font-bold bg-[#FDE68A] border-4 border-[#4A2E1D] rounded-lg">
                <i class="fa-solid fa-utensils text-xl w-6 text-center"></i> Menú
            </a>
            <a href="index.php?action=inventario" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors">
                <i class="fa-solid fa-boxes-stacked text-xl w-6 text-center"></i> Inventario
            </a>
            <a href="index.php?action=auditoria" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors">
                <i class="fa-solid fa-clipboard-list text-xl w-6 text-center"></i> Historial
            </a>
            <a href="index.php?action=reportes" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors">
                <i class="fa-solid fa-cash-register text-xl w-6 text-center"></i> Reportes
            </a>
            <div class="flex-grow"></div>
            <a href="index.php?action=logout" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors">
                <i class="fa-solid fa-right-from-bracket text-xl w-6 text-center text-red-600"></i> Cerrar Sesión
            </a>
        </nav>
    </aside>

    <!-- ================= CONTENIDO PRINCIPAL ================= -->
    <main class="flex-1 flex flex-col">
        
        <!-- Header Azul -->
        <header class="bg-[#5CE1E6] py-6 flex justify-center items-center shadow-sm">
            <h1 class="text-4xl font-bold text-black flex items-center gap-3 font-brand uppercase tracking-wider">
                <i class="fa-solid fa-utensils"></i> MENU
            </h1>
        </header>

        <!-- Barra de Herramientas (Buscador, Filtro, Nuevo) -->
        <div class="flex w-full border-b-2 border-gray-200 bg-white">
            <!-- Buscador -->
            <div class="w-1/3 p-3 flex items-center gap-3 border-r-2 border-gray-200">
                <i class="fa-solid fa-magnifying-glass text-2xl text-gray-400"></i>
                <input type="text" id="buscador" placeholder="Buscar producto" class="bg-transparent text-lg text-gray-700 placeholder-gray-400 outline-none w-full">
            </div>
            
            <!-- Filtro (Visual) -->
            <div class="w-1/3 p-3 flex items-center justify-center gap-2 border-r-2 border-gray-200 cursor-pointer hover:bg-gray-50">
                <span class="text-xl font-bold text-black">Categoría</span>
                <i class="fa-solid fa-chevron-down text-black"></i>
            </div>

            <!-- Botón Nuevo Producto -->
            <div class="w-1/3 p-3 flex items-center justify-center">
                <a href="index.php?action=productos_nuevo" class="flex items-center gap-2 text-xl font-bold text-black hover:text-blue-600 transition-colors">
                    <i class="fa-solid fa-plus"></i> Nuevo Producto
                </a>
            </div>
        </div>

        <!-- Lista de Platillos -->
        <div class="flex-1 overflow-y-auto">
            <table class="w-full text-left border-collapse" id="tablaProductos">
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="6" class="text-center p-10 text-gray-500 text-lg">No hay productos registrados aún.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $prod): ?>
                            <tr class="border-b-2 border-gray-300 hover:bg-gray-50 transition-colors fila-producto">
                                <td class="p-4 w-16 border-r-2 border-gray-300"></td> <!-- Espacio para imagen -->
                                
                                <td class="p-4 w-1/4">
                                    <h3 class="text-2xl font-bold text-black nombre-producto"><?php echo htmlspecialchars($prod['nombre_producto']); ?></h3>
                                    <p class="text-lg font-bold text-blue-500">Precio: $<?php echo number_format((float)$prod['precio'], 2); ?></p>
                                </td>
                                
                                <td class="p-4 text-lg text-black"><?php echo htmlspecialchars($prod['nombre_categoria'] ?? 'Sin categoría'); ?></td>
                                
                                <td class="p-4 text-lg text-gray-600"><?php echo htmlspecialchars($prod['descripcion']); ?></td>
                                
                                <td class="p-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <?php if ($prod['disponible']): ?>
                                            <span class="w-6 h-6 bg-green-400 rounded-full mb-1"></span>
                                            <span class="text-sm">Disponible</span>
                                        <?php else: ?>
                                            <span class="w-6 h-6 bg-red-400 rounded-full mb-1"></span>
                                            <span class="text-sm">Agotado</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                
                                <td class="p-4 text-center w-24">
                                    <a href="index.php?action=productos_editar&id=<?php echo $prod['id']; ?>" class="text-3xl text-black hover:text-blue-600 transition-colors">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

    <!-- Script para el Buscador -->
    <script>
        document.getElementById('buscador').addEventListener('keyup', function() {
            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll('.fila-producto');
            
            filas.forEach(fila => {
                let nombre = fila.querySelector('.nombre-producto').textContent.toLowerCase();
                if (nombre.includes(filtro)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>