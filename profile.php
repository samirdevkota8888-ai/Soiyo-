<?php
require_once 'common/header.php';
require_once 'common/sidebar.php';

if (isset($_GET['logout'])) {
    session_destroy();
    echo "<script>location.href='index.php'</script>"; exit;
}

if (!isset($_SESSION['user_id'])) echo "<script>location.href='login.php'</script>";

$uid = $_SESSION['user_id'];
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $addr = $_POST['address'];
    
    $conn->query("UPDATE users SET name='$name', email='$email', phone='$phone', address='$addr' WHERE id=$uid");
    
    if (!empty($_POST['password'])) {
        $p = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$p' WHERE id=$uid");
    }
    $msg = "Profile Updated Successfully!";
}

$u = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
?>

<div class="container mx-auto px-4 pt-4 pb-24">
    <h1 class="text-2xl font-bold mb-4">My Profile</h1>
    
    <?php if($msg): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm font-bold"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-5 rounded-lg shadow space-y-4">
        <div>
            <label class="text-xs font-bold text-gray-500 uppercase">Name</label>
            <input type="text" name="name" value="<?= $u['name'] ?>" class="w-full border-b py-2 outline-none focus:border-blue-600">
        </div>
        <div>
            <label class="text-xs font-bold text-gray-500 uppercase">Email</label>
            <input type="email" name="email" value="<?= $u['email'] ?>" class="w-full border-b py-2 outline-none focus:border-blue-600">
        </div>
        <div>
            <label class="text-xs font-bold text-gray-500 uppercase">Phone</label>
            <input type="text" name="phone" value="<?= $u['phone'] ?>" class="w-full border-b py-2 outline-none focus:border-blue-600">
        </div>
        <div>
            <label class="text-xs font-bold text-gray-500 uppercase">Address</label>
            <input type="text" name="address" value="<?= $u['address'] ?>" class="w-full border-b py-2 outline-none focus:border-blue-600">
        </div>
        <div>
            <label class="text-xs font-bold text-gray-500 uppercase">New Password (Optional)</label>
            <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full border-b py-2 outline-none focus:border-blue-600">
        </div>
        
        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg shadow mt-4">Save Changes</button>
    </form>
    
    <a href="?logout=true" class="block w-full text-center text-red-500 font-bold mt-6 bg-white p-3 rounded shadow">Logout</a>
</div>

<?php require_once 'common/bottom.php'; ?>