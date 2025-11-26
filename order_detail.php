<?php require_once 'common/header.php'; 

$oid = $_GET['id'];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $st = $_POST['status'];
    $conn->query("UPDATE orders SET status='$st' WHERE id=$oid");
    echo "<script>location.reload()</script>";
}

$o = $conn->query("SELECT * FROM orders WHERE id=$oid")->fetch_assoc();
$items = $conn->query("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=$oid");
?>

<div class="flex flex-col md:flex-row gap-6">
    <div class="w-full md:w-2/3">
        <div class="bg-white rounded shadow p-4 mb-4">
            <h2 class="text-lg font-bold mb-3 border-b pb-2">Order Items</h2>
            <?php while($i = $items->fetch_assoc()): ?>
            <div class="flex gap-4 mb-4 items-center">
                <img src="../uploads/<?= $i['image'] ?>" class="w-16 h-16 object-cover rounded bg-gray-100">
                <div>
                    <h4 class="font-bold"><?= $i['name'] ?></h4>
                    <p class="text-sm text-gray-600">Qty: <?= $i['quantity'] ?> x ₹<?= $i['price'] ?></p>
                </div>
            </div>
            <?php endwhile; ?>
            <div class="border-t pt-2 text-right text-xl font-bold">
                Total: ₹<?= number_format($o['total_amount']) ?>
            </div>
        </div>
    </div>

    <div class="w-full md:w-1/3 space-y-4">
        <div class="bg-white rounded shadow p-4">
            <h2 class="text-lg font-bold mb-3 border-b pb-2">Customer Info</h2>
            <p><strong>Name:</strong> <?= $o['name'] ?></p>
            <p><strong>Phone:</strong> <?= $o['phone'] ?></p>
            <p><strong>Address:</strong><br><?= nl2br($o['address']) ?></p>
        </div>

        <div class="bg-white rounded shadow p-4">
            <h2 class="text-lg font-bold mb-3 border-b pb-2">Update Status</h2>
            <form method="POST">
                <select name="status" class="w-full border p-2 rounded mb-3">
                    <?php 
                    $sts = ['Placed', 'Dispatched', 'Delivered', 'Cancelled'];
                    foreach($sts as $s) {
                        $sel = $o['status'] == $s ? 'selected' : '';
                        echo "<option value='$s' $sel>$s</option>";
                    }
                    ?>
                </select>
                <button class="w-full bg-blue-600 text-white font-bold py-2 rounded">Update</button>
            </form>
        </div>
    </div>
</div>
<?php require_once 'common/bottom.php'; ?>