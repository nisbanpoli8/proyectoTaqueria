<?php
// views/caja_panel.php
declare(strict_types=1);

$nombreCompleto = $_SESSION['nombre_completo'] ?? 'Cajero';
$rol = $_SESSION['rol'] ?? 'Cajero';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Caja | Taquería Tacos Roy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'taco-yellow': '#FBBF24',
                        'taco-yellow-dark': '#F59E0B',
                        'taco-brown': '#4A2E1D',
                        'taco-yellow-warm': '#FFFBEB',
                    },
                    fontFamily: {
                        'brand': ['"Lilita One"', 'cursive'],
                        'sans': ['"Poppins"', 'sans-serif'],
                    }
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
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-taco-yellow-warm rounded-3xl border-4 border-taco-brown max-w-md w-full p-8 shadow-2xl text-center">
        <div class="text-6xl text-taco-brown mb-4">
            <i class="fa-solid fa-cash-register"></i>
        </div>
        <h1 class="font-brand text-3xl text-taco-brown mb-2">¡Bienvenido, <?php echo htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8'); ?>!</h1>
        <p class="text-sm text-taco-brown-light mb-8">Estás en el panel de caja. Para cobrar, ve al mapa de mesas y selecciona la mesa que solicitó la cuenta.</p>
        
        <div class="space-y-3">
            <a href="index.php?action=mesas" class="block w-full py-3 bg-taco-yellow hover:bg-taco-yellow-dark text-taco-brown font-bold rounded-xl border-2 border-taco-brown transition-all">
                <i class="fa-solid fa-chair mr-2"></i> Ver Mapa de Mesas
            </a>
            <a href="index.php?action=logout" class="block w-full py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl border-2 border-taco-brown transition-all">
                <i class="fa-solid fa-right-from-bracket mr-2"></i> Cerrar Sesión
            </a>
        </div>
    </div>
</body>
</html>