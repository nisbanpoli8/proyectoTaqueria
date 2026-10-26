<?php
// views/caja_lista.php
declare(strict_types=1);

$nombreCompleto = $_SESSION['nombre_completo'] ?? 'Cajero';
$mesasPendientes = $mesasPendientes ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comandas por Cobrar | Tacos Roy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'taco-yellow': '#FBBF24', 'taco-yellow-dark': '#F59E0B',
                        'taco-brown': '#4A2E1D', 'taco-yellow-warm': '#FFFBEB',
                    },
                    fontFamily: { 'brand': ['"Lilita One"', 'cursive'], 'sans': ['"Poppins"', 'sans-serif'] }
                }
            }
        };
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #3D2314;
            background-image: radial-gradient(#78350F 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="min-h-screen p-6">
    <div class="max-w-5xl mx-auto">
        <header class="mb-8 flex justify-between items-center bg-taco-yellow-warm p-4 rounded-3xl border-4 border-taco-brown shadow-lg">
            <div class="flex items-center gap-4">
                <div class="text-4xl">🌮</div>
                <div>
                    <h1 class="font-brand text-3xl text-taco-brown">Comandas por Cobrar</h1>
                    <p class="text-sm font-semibold text-taco-brown/80">Bienvenido, <?php echo htmlspecialchars($nombreCompleto); ?></p>
                </div>
            </div>
            <a href="index.php?action=logout" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-xl border-2 border-taco-brown">
                <i class="fa-solid fa-right-from-bracket"></i> Salir
            </a>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($mesasPendientes)): ?>
                <div class="col-span-full text-center bg-white p-10 rounded-3xl border-4 border-taco-brown">
                    <i class="fa-solid fa-mug-hot text-6xl text-taco-brown/30 mb-4"></i>
                    <h2 class="font-brand text-2xl text-taco-brown">No hay cuentas pendientes</h2>
                    <p class="text-taco-brown/70">Las mesas que soliciten la cuenta aparecerán aquí.</p>
                </div>
            <?php else: ?>
                <?php foreach ($mesasPendientes as $mesa): ?>
                    <div class="bg-taco-yellow-warm rounded-3xl border-4 border-taco-brown p-6 shadow-lg text-center">
                        <h2 class="font-brand text-4xl text-taco-brown mb-2"><?php echo htmlspecialchars($mesa['numero_mesa']); ?></h2>
                        <p class="text-sm text-taco-brown/80 mb-6"><i class="fa-solid fa-user-group"></i> <?php echo $mesa['capacidad']; ?> personas</p>
                        <a href="index.php?action=caja&id_mesa=<?php echo $mesa['id']; ?>" class="block w-full bg-taco-yellow hover:bg-taco-yellow-dark text-taco-brown font-bold py-3 rounded-xl border-2 border-taco-brown transition">
                            <i class="fa-solid fa-cash-register"></i> Cobrar
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>