<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db.php';

try {
    // Check users table
    $stmt = $pdo->query("SELECT * FROM users WHERE user_type = 'Admin'");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Admin Accounts Found:</h2>";
    echo "<pre>";
    foreach ($admins as $admin) {
        echo "ID: " . $admin['id'] . "\n";
        echo "Name: " . $admin['fullname'] . "\n";
        echo "Email: " . $admin['email'] . "\n";
        echo "User Type: " . $admin['user_type'] . "\n";
        echo "------------------------\n";
    }
    echo "</pre>";

    // Create a new admin if none exists
    if (empty($admins)) {
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (fullname, email, password, user_type) 
            VALUES ('System Administrator', 'admin@capturesync.com', ?, 'Admin')
        ");
        $stmt->execute([$password]);
        echo "<p style='color: green;'>New admin account created!</p>";
        echo "<p>Email: admin@capturesync.com<br>Password: admin123</p>";
    }

} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
} 