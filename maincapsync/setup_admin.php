<?php
require_once 'includes/db.php';

try {
    // Read and execute the SQL file
    $sql = file_get_contents('admin_setup.sql');
    $pdo->exec($sql);
    
    echo "Admin setup completed successfully!<br>";
    echo "You can now login with:<br>";
    echo "Email: admin@capturesync.com<br>";
    echo "Password: admin123<br>";
    echo "<br>Please delete this file and admin_setup.sql after successful setup.";
    
} catch (PDOException $e) {
    echo "Error during setup: " . $e->getMessage();
    error_log($e->getMessage());
} 