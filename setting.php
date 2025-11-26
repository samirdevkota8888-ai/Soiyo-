<?php require_once 'common/header.php'; 

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user = $_POST['username'];
    $sql = "UPDATE admin SET username='$user'";
    if(!empty($_POST['password'])){
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql .= ", password='$pass'";
    }
    $sql .= " WHERE id={$_SESSION['admin_id']}";
    $conn->query($sql);
    echo "<p class='bg-green-100 text-green-700 p-3 rounded mb-4'>Updated Successfully</p>";
}
$admin = $conn->query("SELECT * FROM admin WHERE id={$_SESSION['admin_id']}")->fetch_assoc();
?>
<h1 class="text-2xl font-bold mb-6">Settings</h1>
<form method="POST" class="bg-white p-6 rounded shadow max-w-md">
    <div class="mb-4">
        <label class="block font-bold mb-1">Admin Username</label>
        <input type="text" name="username" value="<?= $admin['username'] ?>" class="w-full border p-2 rounded">
    </div>
    <div class="mb-4">
        <label class="block font-bold mb-1">New Password</label>
        <input type="password" name="password" placeholder="Leave empty to keep current" class="w-full border p-2 rounded">
    </div>
    <button class="bg-blue-600 text-white px-6 py-2 rounded font-bold">Update</button>
</form>
<?php require_once 'common/bottom.php'; ?>