<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- Updated Title -->
    <title>Soiyo</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; touch-action: manipulation; }
        /* Hide scrollbar for horizontal scroll */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <script>
        // Disable Right Click & Zoom
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && (event.key === '+' || event.key === '-' || event.key === '0')) {
                event.preventDefault();
            }
        });
    </script>
</head>
<body class="bg-gray-50 text-gray-800 pb-20">
    <!-- Top Nav -->
    <nav class="bg-blue-600 text-white p-4 sticky top-0 z-50 shadow-md flex justify-between items-center">
        <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()" class="text-xl"><i class="fas fa-bars"></i></button>
            <!-- Updated App Name -->
            <a href="index.php" class="text-xl font-bold tracking-wide italic">Soiyo</a>
        </div>
        <div class="relative">
            <a href="cart.php" class="text-xl relative">
                <i class="fas fa-shopping-cart"></i>
                <?php 
                $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                if($cart_count > 0) echo "<span class='absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5'>$cart_count</span>";
                ?>
            </a>
        </div>
    </nav>
    
    <!-- Loading Modal -->
    <div id="loader" class="fixed inset-0 bg-black bg-opacity-50 z-[60] hidden flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-xl text-center">
            <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-3"></i>
            <p class="font-semibold">Processing...</p>
        </div>
    </div>