<!-- Sidebar -->
    <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity"></div>
    <div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white z-50 transform -translate-x-full transition-transform duration-300 shadow-2xl">
        <div class="bg-blue-600 p-6 text-white">
            <h2 class="text-2xl font-bold">Menu</h2>
            <?php if(isset($_SESSION['user_id'])): ?>
                <p class="text-sm mt-2">Hi, <?php echo $_SESSION['user_name']; ?></p>
            <?php else: ?>
                <a href="login.php" class="text-sm underline mt-2 inline-block">Login / Signup</a>
            <?php endif; ?>
        </div>
        <ul class="p-4 space-y-4 text-gray-700 font-medium">
            <li><a href="index.php" class="block hover:text-blue-600"><i class="fas fa-home w-8"></i> Home</a></li>
            <li><a href="product.php" class="block hover:text-blue-600"><i class="fas fa-box w-8"></i> All Products</a></li>
            <li><a href="order.php" class="block hover:text-blue-600"><i class="fas fa-receipt w-8"></i> My Orders</a></li>
            <li><a href="cart.php" class="block hover:text-blue-600"><i class="fas fa-shopping-cart w-8"></i> Cart</a></li>
            <li><a href="profile.php" class="block hover:text-blue-600"><i class="fas fa-user w-8"></i> Profile</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li class="border-t pt-4"><a href="profile.php?logout=true" class="block text-red-500"><i class="fas fa-sign-out-alt w-8"></i> Logout</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <script>
        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            const ov = document.getElementById('sidebarOverlay');
            if (sb.classList.contains('-translate-x-full')) {
                sb.classList.remove('-translate-x-full');
                ov.classList.remove('hidden');
            } else {
                sb.classList.add('-translate-x-full');
                ov.classList.add('hidden');
            }
        }
        function showLoader(){ document.getElementById('loader').classList.remove('hidden'); }
        function hideLoader(){ document.getElementById('loader').classList.add('hidden'); }
    </script>