<?php
// views/admin_panel.php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Confirmación de acceso
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?action=login');
    exit;
}

if (($_SESSION['rol'] ?? '') !== 'Administrador') {
    $_SESSION['flash_error'] = 'Acceso denegado: no tienes permisos para esta sección.';
    header('Location: index.php?action=home');
    exit;
}

$nombreCompleto = $_SESSION['nombre_completo'] ?? 'Administrador';
$rol = $_SESSION['rol'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Confirmado | Taquería Tacos Roy</title>
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
                        'taco-yellow-light': '#FEF3C7',
                        'taco-yellow-warm': '#FFFBEB',
                        'taco-brown-dark': '#3D2314',
                        'taco-brown': '#4A2E1D',
                        'taco-brown-light': '#78350F',
                        'taco-terracotta': '#9A3412',
                    },
                    fontFamily: {
                        'brand': ['"Lilita One"', 'cursive'],
                        'sans': ['"Poppins"', 'sans-serif'],
                    },
                    boxShadow: {
                        'craft': '0 15px 25px -5px rgba(61, 35, 20, 0.25), 0 8px 10px -6px rgba(61, 35, 20, 0.15)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #3D2314;
            background-image: 
                radial-gradient(circle at 50% 50%, rgba(245, 158, 11, 0.12) 0%, rgba(61, 35, 20, 0.9) 100%),
                radial-gradient(#78350F 1px, transparent 1px);
            background-size: 100% 100%, 24px 24px;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <!-- Cuadro de Confirmación -->
    <div class="bg-taco-yellow-warm rounded-3xl border-4 border-taco-brown max-w-md w-full p-8 shadow-craft text-center">

        <div class="text-6xl text-emerald-600 mb-4">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <h1 class="font-brand text-3xl text-taco-brown mb-3">
            ¡Acceso Confirmado!
        </h1>

        <p class="text-sm text-taco-brown-light mb-2">
            Bienvenido, <strong><?php echo htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8'); ?></strong>
        </p>

        <p class="text-xs text-taco-brown-light mb-6">
            Rol: <strong><?php echo htmlspecialchars($rol, ENT_QUOTES, 'UTF-8'); ?></strong>
        </p>

        <a href="index.php?action=home"
           class="inline-block px-6 py-3 bg-taco-yellow hover:bg-taco-yellow-dark border-2 border-taco-brown text-taco-brown font-brand text-base uppercase rounded-xl transition-all cursor-pointer hover:scale-105">
            <i class="fa-solid fa-arrow-right mr-2"></i>
            Continuar
        </a>

    </div>

</body>
</html>