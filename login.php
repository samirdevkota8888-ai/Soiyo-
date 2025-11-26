<?php
// Include header (which connects to DB and starts session)
// Note: The header file handles the HTML <head> and Tailwind CDN
require_once 'common/header.php';

// Handle Logout
if(isset($_GET['logout'])) { 
    session_destroy(); 
    echo "<script>window.location.href='login.php';</script>"; 
    exit;
}

// Redirect if already logged in
if(isset($_SESSION['admin_id'])) {
    echo "<script>window.location.href='index.php';</script>"; 
    exit;
}

// Handle Login Form Submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    // Check DB for admin user
    $result = $conn->query("SELECT * FROM admin WHERE username='$username'");
    
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        // Verify Hashed Password
        if(password_verify($password, $row['password'])){
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_user'] = $row['username'];
            echo "<script>window.location.href='index.php';</script>";
            exit;
        } else {
            $error = "Incorrect Password";
        }
    } else {
        $error = "Username not found";
    }
}
?>

<!-- Login Interface -->
<div class="min-h-[calc(100vh-60px)] flex items-center justify-center bg-gray-100 p-4">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm border border-gray-200">
        <div class="text-center mb-6">
            <i class="fas fa-user-shield text-4xl text-gray-700 mb-2"></i>
            <h2 class="text-2xl font-bold text-gray-800">Admin Panel</h2>
            <p class="text-sm text-gray-500">Sign in to manage store</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 text-sm font-semibold">
                <p><i class="fas fa-exclamation-circle"></i> <?= $error ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-1">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="username" placeholder="Enter username" required 
                        class="w-full pl-10 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600 bg-gray-50">
                </div>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" placeholder="Enter password" required 
                        class="w-full pl-10 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600 bg-gray-50">
                </div>
            </div>

            <button type="submit" class="w-full bg-gray-800 text-white p-3 rounded-lg font-bold hover:bg-gray-900 transition duration-200 shadow-lg">
                LOGIN
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="../index.php" class="text-sm text-gray-500 hover:text-gray-800 underline">
                &larr; Back to Shop
            </a>
        </div>
    </div>
</div>

<?php require_once 'common/bottom.php'; ?>