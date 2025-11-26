<?php
// FILE: upload_qr.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['qr_image'])) {
        // Ensure uploads folder exists
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // We force the name to be 'esewa_qr.jpg' so checkout.php finds it
        $target_file = $target_dir . "esewa_qr.jpg";

        // Upload
        if (move_uploaded_file($_FILES['qr_image']['tmp_name'], $target_file)) {
            echo "<div style='font-family:sans-serif; text-align:center; padding:20px;'>";
            echo "<h1 style='color:green;'>✅ QR Code Saved!</h1>";
            echo "<img src='$target_file' style='width:200px; border:2px solid green; margin:10px;'><br>";
            echo "<a href='checkout.php' style='background:blue; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Go back to Checkout</a>";
            echo "</div>";
        } else {
            echo "❌ Error uploading. Check folder permissions.";
        }
    }
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload QR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px 20px; background: #f3f4f6; }
        form { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        input { margin: 20px 0; }
        button { background: #2563eb; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-size: 16px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Upload your eSewa QR</h2>
    <p>Select the screenshot of your QR code.</p>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="qr_image" required accept="image/*">
        <br>
        <button type="submit">Upload & Fix</button>
    </form>
</body>
</html>
<?php } ?>