<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db.php';

try {
    // Create activity_log table
    $sql = "CREATE TABLE IF NOT EXISTS activity_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user VARCHAR(100) NOT NULL,
        action TEXT NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    
    // Insert a test record
    $stmt = $pdo->prepare("INSERT INTO activity_log (user, action) VALUES (?, ?)");
    $stmt->execute(['System', 'Activity log table created']);
    
    echo "<h2 style='color: green;'>Activity Log Table Created Successfully!</h2>";
    echo "<p>You can now access the admin dashboard.</p>";
    echo "<p><a href='dashboard_admin.php'>Go to Admin Dashboard</a></p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Error</h2>";
    echo "<p>Database error: " . $e->getMessage() . "</p>";
} 