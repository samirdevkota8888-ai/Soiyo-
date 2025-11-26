<?php
require_once 'common/header.php';
require_once 'common/sidebar.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>"; exit;
}
if (empty($_SESSION['cart'])) {
    echo "<script>window.location.href='index.php';</script>"; exit;
}

$uid = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();

// --- ORDER SUBMIT HANDLE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $raw_address = $_POST['address'];
    $payment_method = $_POST['payment_method']; 
    
    $address = $raw_address . "\n[Payment: " . $payment_method . "]";
    $total = 0;

    // 1. Screenshot Upload Logic
    $proof_image = NULL;
    if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] == 0) {
        $ext = pathinfo($_FILES['screenshot']['name'], PATHINFO_EXTENSION);
        $proof_image = 'pay_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['screenshot']['tmp_name'], 'uploads/' . $proof_image);
    }

    // Calculate Total
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $res = $conn->query("SELECT price FROM products WHERE id=$pid");
        $row = $res->fetch_assoc();
        $total += $row['price'] * $qty;
    }

    // 2. Insert Order (with payment_proof)
    $stmt = $conn->prepare("INSERT INTO orders (user_id, name, phone, address, total_amount, payment_proof) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssds", $uid, $name, $phone, $address, $total, $proof_image);
    
    if($stmt->execute()){
        $order_id = $stmt->insert_id;
        // Insert Items
        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($_SESSION['cart'] as $pid => $qty) {
            $p = $conn->query("SELECT price FROM products WHERE id=$pid")->fetch_assoc();
            $price = $p['price'];
            $stmt_item->bind_param("iiid", $order_id, $pid, $qty, $price);
            $stmt_item->execute();
        }
        unset($_SESSION['cart']);
        echo "<script>window.location.href='order.php';</script>"; 
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<div class="container mx-auto px-4 pt-4 pb-20">
    <h1 class="text-2xl font-bold mb-4">Checkout</h1>
    
    <!-- ENCTYPE is important for file upload -->
    <form method="POST" enctype="multipart/form-data" class="bg-white p-5 rounded-lg shadow space-y-4">
        
        <!-- Personal Info -->
        <div>
            <label class="text-sm font-bold text-gray-600 block mb-1">Full Name</label>
            <input type="text" name="name" value="<?= $user['name'] ?>" required class="w-full border p-3 rounded-lg outline-none bg-gray-50">
        </div>
        <div>
            <label class="text-sm font-bold text-gray-600 block mb-1">Phone Number</label>
            <input type="text" name="phone" value="<?= $user['phone'] ?>" required class="w-full border p-3 rounded-lg outline-none bg-gray-50">
        </div>
        
        <!-- Address & GPS -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <label class="text-sm font-bold text-gray-600">Delivery Address</label>
                <button type="button" onclick="getGPS()" id="gpsBtn" class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded border border-blue-200 font-bold flex items-center gap-1 hover:bg-blue-200">
                    <i class="fas fa-map-marker-alt"></i> Share My Location
                </button>
            </div>
            <textarea name="address" id="addrBox" required rows="3" class="w-full border p-3 rounded-lg outline-none bg-gray-50"><?= $user['address'] ?></textarea>
            <p id="gpsMsg" class="text-xs text-green-600 mt-1 hidden font-bold"><i class="fas fa-check"></i> Location added!</p>
        </div>
        
        <div class="pt-4 border-t">
            <h3 class="font-bold text-lg text-gray-800 mb-3">Payment Method</h3>
            
            <div class="space-y-3">
                <!-- COD -->
                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 transition">
                    <input type="radio" name="payment_method" value="COD" checked onclick="togglePayment('cod')" class="w-5 h-5 text-blue-600">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-money-bill-wave text-blue-600"></i>
                        <span class="font-semibold text-gray-700">Cash on Delivery</span>
                    </div>
                </label>

                <!-- eSewa -->
                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer has-[:checked]:border-green-600 has-[:checked]:bg-green-50 transition">
                    <input type="radio" name="payment_method" value="eSewa" onclick="togglePayment('esewa')" class="w-5 h-5 text-green-600">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-wallet text-green-600"></i>
                        <span class="font-semibold text-gray-700">eSewa Mobile Wallet</span>
                    </div>
                </label>
            </div>

            <!-- eSewa Section with Upload -->
            <div id="esewa-section" class="hidden mt-4 bg-gray-100 rounded-lg p-4 border border-gray-200 text-center">
                <p class="text-sm text-gray-600 font-bold mb-2">Scan QR to Pay</p>
                <div class="bg-white p-2 inline-block rounded-lg shadow-sm">
                    <img src="uploads/esewa_qr.jpg" alt="eSewa QR" class="w-40 h-40 object-contain mx-auto" onerror="this.src='https://via.placeholder.com/150?text=No+QR+Image'">
                </div>
                
                <div class="mt-3">
                    <h4 class="text-lg font-bold text-green-700">eSewa Details</h4>
                    <p class="text-lg font-bold text-gray-800">Rupa Devkota</p>
                    <p class="text-lg font-bold text-blue-600">9843038123</p>
                </div>
                
                <!-- 📸 SCREENSHOT UPLOAD -->
                <div class="mt-4 text-left bg-white p-3 rounded border border-gray-300">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Upload Payment Screenshot *</label>
                    <input type="file" name="screenshot" accept="image/*" id="screenInput" class="w-full text-sm text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-green-600 text-white font-bold py-3.5 rounded-lg shadow-lg hover:bg-green-700 transition mt-6 text-lg">
            Place Order
        </button>
    </form>
</div>

<script>
    function togglePayment(method) {
        const section = document.getElementById('esewa-section');
        const input = document.getElementById('screenInput');
        
        if (method === 'esewa') {
            section.classList.remove('hidden');
            input.setAttribute('required', 'true'); // Screenshot required for eSewa
        } else {
            section.classList.add('hidden');
            input.removeAttribute('required'); // Not required for COD
        }
    }

    function getGPS() {
        const btn = document.getElementById('gpsBtn');
        const box = document.getElementById('addrBox');
        const msg = document.getElementById('gpsMsg');

        if (navigator.geolocation) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Getting Location...';
            btn.disabled = true;
            navigator.geolocation.getCurrentPosition(
                function(p) {
                    box.value += "\n\n📍 Map: https://maps.google.com/?q=" + p.coords.latitude + "," + p.coords.longitude;
                    btn.innerHTML = '<i class="fas fa-check"></i> Location Shared';
                    msg.classList.remove('hidden');
                }, 
                function(e) { 
                    alert("GPS Error. Enable Location."); 
                    btn.disabled = false;
                }
            );
        } else { alert("GPS not supported."); }
    }
</script>

<?php require_once 'common/bottom.php'; ?>