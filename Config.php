<?php
session_start();
$host = "127.0.0.1";
$user = "root";
$pass = "root"; // Check your local password
$db   = "quick_kart_db";

$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select DB if exists
$conn->select_db($db);

// Helper function for JSON response
function sendJson($status, $msg, $data = []) {
    echo json_encode(['status' => $status, 'message' => $msg, 'data' => $data]);
    exit;
}
?>