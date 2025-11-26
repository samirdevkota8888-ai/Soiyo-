<?php 
require_once 'common/header.php';
require_once 'common/sidebar.php';

// Fetch Categories
$cats = $conn->query("SELECT * FROM categories ORDER BY id DESC LIMIT 10");
// Fetch Featured Products
$prods = $conn->query("SELECT * FROM products ORDER BY RAND() LIMIT 8");
?>

<div class="container mx-auto px-4 pt-4">
    <!-- Categories Scroll -->
    <h3 class="font-bold text-lg mb-2 text-gray-700">Categories</h3>
    <div class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
        <?php while($c = $cats->fetch_assoc()): ?>
            <a href="product.php?cat=<?= $c['id'] ?>" class="flex-shrink-0 flex flex-col items-center w-20">
                <div class="w-16 h-16 rounded-full bg-white shadow-md flex items-center justify-center overflow-hidden border">
                    <?php if($c['image']): ?>
                        <img src="uploads/<?= $c['image'] ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-list text-gray-400"></i>
                    <?php endif; ?>
                </div>
                <span class="text-xs mt-1 text-center font-medium truncate w-full"><?= $c['name'] ?></span>
            </a>
        <?php endwhile; ?>
    </div>

    <!-- Featured Products -->
    <h3 class="font-bold text-lg my-4 text-gray-700">Featured For You</h3>
    <div class="grid grid-cols-2 gap-4 mb-20">
        <?php while($p = $prods->fetch_assoc()): ?>
            <div class="bg-white p-3 rounded-lg shadow hover:shadow-lg transition">
                <a href="product_detail.php?id=<?= $p['id'] ?>">
                    <div class="h-32 bg-gray-100 rounded mb-2 overflow-hidden flex items-center justify-center">
                        <?php if($p['image']): ?>
                            <img src="uploads/<?= $p['image'] ?>" class="h-full w-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-box text-3xl text-gray-300"></i>
                        <?php endif; ?>
                    </div>
                    <h4 class="font-semibold text-sm truncate"><?= $p['name'] ?></h4>
                    <p class="text-blue-600 font-bold text-sm">₹<?= number_format($p['price']) ?></p>
                </a>
                <button onclick="addToCart(<?= $p['id'] ?>, 1)" class="w-full mt-2 bg-blue-100 text-blue-600 text-xs font-bold py-1.5 rounded hover:bg-blue-200">
                    ADD TO CART
                </button>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
    async function addToCart(pid, qty) {
        showLoader();
        const fd = new FormData();
        fd.append('action', 'add');
        fd.append('pid', pid);
        fd.append('qty', qty);
        
        await fetch('cart.php', { method: 'POST', body: fd });
        location.reload(); 
    }
</script>

<?php require_once 'common/bottom.php'; ?>