<?php 
require_once 'common/header.php';
require_once 'common/sidebar.php';

$id = (int)$_GET['id'];
$p = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
if(!$p) die("Product not found");
?>

<div class="bg-white pb-20 min-h-screen">
    <div class="w-full h-72 bg-gray-100 flex items-center justify-center">
        <?php if($p['image']): ?>
            <img src="uploads/<?= $p['image'] ?>" class="h-full w-full object-contain">
        <?php else: ?>
            <i class="fas fa-box text-6xl text-gray-300"></i>
        <?php endif; ?>
    </div>
    
    <div class="p-5 relative -mt-6 bg-white rounded-t-3xl shadow-[0_-5px_15px_rgba(0,0,0,0.05)]">
        <h1 class="text-2xl font-bold text-gray-800"><?= $p['name'] ?></h1>
        <div class="flex justify-between items-center mt-2">
            <span class="text-3xl font-bold text-blue-600">₹<?= number_format($p['price']) ?></span>
            <span class="px-3 py-1 text-xs rounded-full <?= $p['stock']>0?'bg-green-100 text-green-700':'bg-red-100 text-red-700' ?>">
                <?= $p['stock']>0 ? 'In Stock' : 'Out of Stock' ?>
            </span>
        </div>
        
        <div class="mt-6">
            <h3 class="font-semibold text-gray-700 mb-2">Description</h3>
            <p class="text-gray-500 text-sm leading-relaxed"><?= nl2br($p['description']) ?></p>
        </div>

        <!-- Add to Cart Footer -->
        <div class="fixed bottom-16 left-0 w-full bg-white border-t p-4 flex gap-4 items-center">
            <div class="flex items-center border rounded-lg h-10">
                <button onclick="updateQty(-1)" class="w-8 h-full bg-gray-100 hover:bg-gray-200">-</button>
                <input type="text" id="qty" value="1" readonly class="w-10 text-center text-sm outline-none">
                <button onclick="updateQty(1)" class="w-8 h-full bg-gray-100 hover:bg-gray-200">+</button>
            </div>
            <button onclick="addToCart(<?= $p['id'] ?>)" class="flex-1 bg-blue-600 text-white font-bold h-10 rounded-lg hover:bg-blue-700">
                Add to Cart
            </button>
        </div>
    </div>
</div>

<script>
    function updateQty(change) {
        let q = document.getElementById('qty');
        let val = parseInt(q.value) + change;
        if(val < 1) val = 1;
        q.value = val;
    }
    
    async function addToCart(pid) {
        showLoader();
        let qty = document.getElementById('qty').value;
        const fd = new FormData();
        fd.append('action', 'add');
        fd.append('pid', pid);
        fd.append('qty', qty);
        
        await fetch('cart.php', { method: 'POST', body: fd });
        window.location.href = 'cart.php';
    }
</script>

<?php require_once 'common/bottom.php'; ?>