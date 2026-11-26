<?php 
// Detectamos en qué página estamos para iluminar el botón correcto
$pagina_actual = $_GET['action'] ?? 'admin'; 
?>
<aside class="w-64 bg-[#FBBF24] min-h-screen flex flex-col p-4 shadow-xl z-10">
    <div class="flex flex-col items-center text-center mb-8 mt-4">
        <img src="assets/images/logoTacosRoy.png" alt="Logo Tacos Roy" class="w-24 mb-2">
        <h1 class="text-2xl font-bold text-black font-brand">Tacos Roy</h1>
        <h2 class="text-md font-bold text-black">Admin Panel</h2>
    </div>

    <nav class="flex flex-col gap-2 flex-grow">
        <a href="index.php?action=admin" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors <?php echo ($pagina_actual === 'admin') ? 'bg-[#FDE68A] border-4 border-[#4A2E1D]' : ''; ?>">
            <i class="fa-solid fa-house text-xl w-6 text-center"></i> Inicio
        </a>
        
        <a href="index.php?action=productos" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors <?php echo ($pagina_actual === 'productos') ? 'bg-[#FDE68A] border-4 border-[#4A2E1D]' : ''; ?>">
            <i class="fa-solid fa-utensils text-xl w-6 text-center"></i> Menú
        </a>
        
        <a href="index.php?action=inventario" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors <?php echo ($pagina_actual === 'inventario') ? 'bg-[#FDE68A] border-4 border-[#4A2E1D]' : ''; ?>">
            <i class="fa-solid fa-boxes-stacked text-xl w-6 text-center"></i> Inventario
        </a>
        
        <a href="index.php?action=auditoria" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors <?php echo ($pagina_actual === 'auditoria') ? 'bg-[#FDE68A] border-4 border-[#4A2E1D]' : ''; ?>">
            <i class="fa-solid fa-clipboard-list text-xl w-6 text-center"></i> Historial
        </a>
        
        <a href="index.php?action=reportes" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors <?php echo ($pagina_actual === 'reportes') ? 'bg-[#FDE68A] border-4 border-[#4A2E1D]' : ''; ?>">
            <i class="fa-solid fa-cash-register text-xl w-6 text-center"></i> Reportes
        </a>
        
        <div class="flex-grow"></div>
        
        <a href="index.php?action=logout" class="flex items-center gap-4 px-4 py-3 text-black font-bold hover:bg-white/40 rounded-lg transition-colors">
            <i class="fa-solid fa-right-from-bracket text-xl w-6 text-center text-red-600"></i> Cerrar Sesión
        </a>
    </nav>
</aside>