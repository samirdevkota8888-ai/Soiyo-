<?php
// फाइल नाम: update_db.php
require_once 'common/config.php';

// orders टेबलमा payment_proof भन्ने कोठा थप्ने
$sql = "ALTER TABLE orders ADD COLUMN payment_proof VARCHAR(255) NULL AFTER status";

if ($conn->query($sql) === TRUE) {
    echo "<h1 style='color:green; text-align:center;'>✅ Database Updated Successfully!</h1>";
    echo "<p style='text-align:center;'>Now delete this file.</p>";
} else {
    echo "Error updating database: " . $conn->error;
}
?>