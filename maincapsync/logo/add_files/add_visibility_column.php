<?php
require_once 'includes/db.php';

try {
    // Add is_visible column to photographers table
    $sql = "ALTER TABLE photographers ADD COLUMN is_visible BOOLEAN DEFAULT FALSE";
    $conn->exec($sql);
    echo "Successfully added is_visible column to photographers table";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 