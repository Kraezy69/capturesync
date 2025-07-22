<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db.php';

try {
    // Create photographers table
    $sql = "CREATE TABLE IF NOT EXISTS photographers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        specialty VARCHAR(100) NOT NULL,
        experience INT NOT NULL,
        location VARCHAR(100) NOT NULL,
        portfolio_url VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    
    // Add profile_pic column if it doesn't exist
    $sql = "ALTER TABLE photographers ADD COLUMN IF NOT EXISTS profile_pic VARCHAR(255) DEFAULT NULL";
    $pdo->exec($sql);
    
    // Add cover_photo column if it doesn't exist
    $sql = "ALTER TABLE photographers ADD COLUMN IF NOT EXISTS cover_photo VARCHAR(255) DEFAULT NULL";
    $pdo->exec($sql);
    
    echo "<h2 style='color: green;'>Photographers Table Created Successfully!</h2>";
    echo "<p>The table structure has been set up with all required fields:</p>";
    echo "<ul>";
    echo "<li>ID (auto-incrementing)</li>";
    echo "<li>Full Name</li>";
    echo "<li>Email (unique)</li>";
    echo "<li>Password (hashed)</li>";
    echo "<li>Specialty</li>";
    echo "<li>Experience (years)</li>";
    echo "<li>Location</li>";
    echo "<li>Portfolio URL</li>";
    echo "<li>Created At (timestamp)</li>";
    echo "</ul>";
    echo "<p><a href='dashboard_admin.php'>Return to Admin Dashboard</a></p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Error</h2>";
    echo "<p>Database error: " . $e->getMessage() . "</p>";
    // Log the detailed error for debugging
    error_log("Database Error in create_photographers_table.php: " . $e->getMessage());
} 