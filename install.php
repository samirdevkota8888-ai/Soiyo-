<?php
$host = "127.0.0.1";
$user = "root";
$pass = "root";

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "CREATE DATABASE IF NOT EXISTS quick_kart_db";
$conn->query($sql);
$conn->select_db("quick_kart_db");

// Users Table
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Admin Table
$conn->query("CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255)
)");

// Categories
$conn->query("CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    image VARCHAR(255)
)");

// Products
$conn->query("CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cat_id INT,
    name VARCHAR(200),
    description TEXT,
    price DECIMAL(10,2),
    stock INT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Orders
$conn->query("CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100),
    address TEXT,
    phone VARCHAR(20),
    total_amount DECIMAL(10,2),
    status ENUM('Placed','Dispatched','Delivered','Cancelled') DEFAULT 'Placed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Order Items
$conn->query("CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2)
)");

// Default Admin (user: admin, pass: admin123)
$pass = password_hash("admin123", PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO admin (username, password) VALUES ('admin', '$pass')");

// Create uploads folder
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

echo "<h1 style='font-family:sans-serif; text-align:center; margin-top:50px;'>Installation Successful! <br> <a href='login.php'>Go to Login</a></h1>";
?>