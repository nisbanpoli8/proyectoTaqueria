<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Tacos Roy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-white flex min-h-screen font-sans">

    <!-- ================= SIDEBAR ================= -->
    <aside class="w-64 bg-[#FBBF24] min-h-screen flex flex-col p-4 shadow-xl z-10">
        <!-- Logo y Título -->
        <div class="flex flex-col items-center text-center mb-8 mt-4">
            <!-- Aquí va tu logo -->
            <img src="assets/images/logoTacosRoy.png" alt="Logo Tacos Roy" class="w-24 mb-2">
            <h1 class="text-2xl font-bold text-black font-brand">Tacos Roy</h1>
            <h2 class="text-md font-bold text-black">Admin Panel</h2>
        </div>

        <!-- Menú de navegación -->
        <nav class="flex flex-col gap-2 flex-grow">
            <!-- Inicio -->
            <a href="index.php?action=admin" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors">
                <i class="fa-solid fa-house text-xl w-6 text-center"></i>
                <span>Inicio</span>
            </a>
            <!-- Menú (Productos) -->
            <a href="index.php?action=productos" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors">
                <i class="fa-solid fa-utensils text-xl w-6 text-center"></i>
                <span>Menú</span>
            </a>
            <!-- Inventario -->
            <a href="index.php?action=inventario" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors">
                <i class="fa-solid fa-boxes-stacked text-xl w-6 text-center"></i>
                <span>Inventario</span>
            </a>
            <!-- Historial / Auditoría -->
            <a href="index.php?action=auditoria" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors">
                <i class="fa-solid fa-clipboard-list text-xl w-6 text-center"></i>
                <span>Historial</span>
            </a>
            <!-- Reportes / Corte de Caja -->
            <a href="index.php?action=reportes" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors">
                <i class="fa-solid fa-cash-register text-xl w-6 text-center"></i>
                <span>Reportes</span>
            </a>

            <!-- Espaciador para empujar Cerrar Sesión al fondo -->
            <div class="flex-grow"></div>

            <!-- Cerrar Sesión -->
            <a href="index.php?action=logout" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors">
                <i class="fa-solid fa-right-from-bracket text-xl w-6 text-center text-red-600"></i>
                <span>Cerrar Sesión</span>
            </a>
        </nav>
    </aside>

    <!-- ================= CONTENIDO PRINCIPAL ================= -->
    <main class="flex-1 flex flex-col">
        
        <!-- Top Bar -->
        <header class="flex justify-end p-6">
            <div class="bg-gray-300 px-6 py-2 rounded-full font-bold text-black">
                Admin: <?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? 'Usuario'); ?>
            </div>
        </header>

        <!-- Contenido -->
        <div class="px-10 pb-10">
            <h1 class="text-4xl font-bold text-center mb-10 font-brand">Acciones principales</h1>

            <section id="alertas" class="max-w-5xl mx-auto mb-8">
                <?php include __DIR__ . '/inventario/alertas.php'; ?>
            </section>

            <!-- Grid de Tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">

                <!-- Tarjeta 1: Agregar Platillo (Cian) -->
                <div class="bg-[#5CE1E6] p-6 rounded-lg shadow-md flex flex-col justify-between h-48 relative overflow-hidden group">
                    <h2 class="font-bold text-sm text-black uppercase text-center mb-2">Agregar un nuevo platillo</h2>
                    <div class="flex items-center justify-between mt-auto">
                        <!-- Icono grande representativo -->
                        <i class="fa-solid fa-bell-concierge text-6xl text-black"></i>
                        <a href="index.php?action=productos_nuevo" class="bg-white border-2 border-black px-4 py-1 rounded-full font-bold text-sm text-black hover:bg-gray-100 transition">
                            NUEVO
                        </a>
                    </div>
                </div>

                <!-- Tarjeta 2: Agregar Insumos (Verde Lima) -->
                <div class="bg-[#B5E61D] p-6 rounded-lg shadow-md flex flex-col justify-between h-48 relative overflow-hidden">
                    <h2 class="font-bold text-sm text-black uppercase text-center mb-2">Agregar nuevos insumos</h2>
                    <div class="flex items-center justify-between mt-auto">
                        <i class="fa-solid fa-cart-flatbed text-6xl text-black"></i>
                        <a href="index.php?action=insumos_nuevo" class="bg-white border-2 border-black px-4 py-1 rounded-full font-bold text-sm text-black hover:bg-gray-100 transition">
                            NUEVO
                        </a>
                    </div>
                </div>

                <!-- Tarjeta 3: Evaluar Almacén (Naranja) -->
                <div class="bg-[#FCA311] p-6 rounded-lg shadow-md flex flex-col justify-between h-48 relative overflow-hidden">
                    <h2 class="font-bold text-sm text-black uppercase text-center mb-2">Evaluar almacén</h2>
                    <div class="flex items-center justify-between mt-auto">
                        <i class="fa-solid fa-file-magnifying-glass text-6xl text-black"></i>
                        <a href="index.php?action=inventario_alertas#alertas" class="bg-white border-2 border-black px-4 py-1 rounded-full font-bold text-sm text-black hover:bg-gray-100 transition">
                            EVALUAR
                        </a>
                    </div>
                </div>

                <!-- Tarjeta 4: Generar Corte de Caja (Morado) - Centrada en la siguiente fila -->
                <div class="lg:col-start-2 bg-[#D883FF] p-6 rounded-lg shadow-md flex flex-col justify-between h-48 relative overflow-hidden">
                    <h2 class="font-bold text-sm text-black uppercase text-center mb-2">Generar corte de caja</h2>
                    <div class="flex items-center justify-between mt-auto">
                        <i class="fa-solid fa-cash-register text-6xl text-black"></i>
                        <a href="index.php?action=reportes" class="bg-white border-2 border-black px-4 py-1 rounded-full font-bold text-sm text-black hover:bg-gray-100 transition">
                            GENERAR
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>