<?php
require_once 'common/config.php';

// Handle AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if ($action === 'add') {
        $pid = $_POST['pid'];
        $qty = $_POST['qty'];
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid] += $qty;
        } else {
            $_SESSION['cart'][$pid] = $qty;
        }
    }
    if ($action === 'update') {
        $pid = $_POST['pid'];
        $qty = $_POST['qty'];
        if ($qty <= 0) unset($_SESSION['cart'][$pid]);
        else $_SESSION['cart'][$pid] = $qty;
    }
    if ($action === 'remove') {
        unset($_SESSION['cart'][$_POST['pid']]);
    }
    echo json_encode(['status'=>'success']);
    exit;
}

require_once 'common/header.php';
require_once 'common/sidebar.php';
?>

<div class="container mx-auto px-4 pt-4 pb-24">
    <h1 class="text-2xl font-bold mb-4">Your Cart</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="text-center mt-20 text-gray-400">
            <i class="fas fa-shopping-cart text-6xl mb-4"></i>
            <p>Cart is empty</p>
            <a href="index.php" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-full">Shop Now</a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
        <?php 
        $total = 0;
        foreach($_SESSION['cart'] as $pid => $qty):
            $p = $conn->query("SELECT * FROM products WHERE id=$pid")->fetch_assoc();
            $sub = $p['price'] * $qty;
            $total += $sub;
        ?>
            <div class="bg-white p-3 rounded-lg shadow flex gap-3 relative">
                <div class="w-20 h-20 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                    <?php if($p['image']): ?>
                        <img src="uploads/<?= $p['image'] ?>" class="w-full h-full object-cover">
                    <?php endif; ?>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-sm truncate"><?= $p['name'] ?></h4>
                    <p class="text-blue-600 font-bold text-sm mt-1">₹<?= number_format($p['price']) ?></p>
                    
                    <div class="flex items-center gap-3 mt-2">
                        <div class="flex items-center border rounded">
                            <button onclick="updateCart(<?= $pid ?>, <?= $qty-1 ?>)" class="px-2 py-0.5 bg-gray-50">-</button>
                            <span class="px-2 text-sm font-semibold"><?= $qty ?></span>
                            <button onclick="updateCart(<?= $pid ?>, <?= $qty+1 ?>)" class="px-2 py-0.5 bg-gray-50">+</button>
                        </div>
                        <button onclick="updateCart(<?= $pid ?>, 0)" class="text-red-500 text-sm"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

        <div class="fixed bottom-16 left-0 w-full bg-white border-t p-4">
            <div class="flex justify-between items-center mb-3">
                <span class="text-gray-600">Total Amount</span>
                <span class="text-xl font-bold">₹<?= number_format($total) ?></span>
            </div>
            <a href="checkout.php" class="block w-full bg-blue-600 text-center text-white font-bold py-3 rounded-lg hover:bg-blue-700">
                Proceed to Checkout
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
    async function updateCart(pid, qty) {
        showLoader();
        const fd = new FormData();
        fd.append('action', qty === 0 ? 'remove' : 'update');
        fd.append('pid', pid);
        fd.append('qty', qty);
        await fetch('cart.php', { method: 'POST', body: fd });
        location.reload();
    }
</script>

<?php require_once 'common/bottom.php'; ?>