<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db.php';

try {
    // New password to set
    $new_password = 'admin123';
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update the password for admin@capturesync.com
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = 'admin@capturesync.com'");
    $stmt->execute([$hashed_password]);
    
    if ($stmt->rowCount() > 0) {
        echo "<h2 style='color: green;'>Password Reset Successful!</h2>";
        echo "<p>You can now login with:</p>";
        echo "<p>Email: admin@capturesync.com</p>";
        echo "<p>Password: admin123</p>";
        echo "<p><a href='main.php'>Go to Login Page</a></p>";
    } else {
        echo "<h2 style='color: red;'>Password Reset Failed</h2>";
        echo "<p>Admin account not found.</p>";
    }
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Error</h2>";
    echo "<p>Database error: " . $e->getMessage() . "</p>";
} 