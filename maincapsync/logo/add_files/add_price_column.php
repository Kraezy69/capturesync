<?php
require_once 'includes/db.php';

try {
    // Add price column if it doesn't exist
    $sql = "ALTER TABLE photographers ADD COLUMN IF NOT EXISTS price DECIMAL(10,2) DEFAULT 1500.00";
    $pdo->exec($sql);
    echo "Price column added successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 