<?php
session_start();
require_once 'includes/db.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

try {
    // Get total photographers (from signup_photographer, i.e., photographers table)
    $stmt = $pdo->query("SELECT COUNT(*) as total_photographers FROM photographers");
    $totalPhotographers = $stmt->fetch(PDO::FETCH_ASSOC)['total_photographers'];

    // Get total users (clients)
    $stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users WHERE user_type = 'Client'");
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

    // Get total bookings (dummy value or real if you have a bookings table)
    $totalBookings = 0;
    if ($pdo->query("SHOW TABLES LIKE 'bookings'")->rowCount() > 0) {
        $stmt = $pdo->query("SELECT COUNT(*) as total_bookings FROM bookings");
        $totalBookings = $stmt->fetch(PDO::FETCH_ASSOC)['total_bookings'];
    }

    // Get total sales (dummy value or real if you have a sales/transactions table)
    $totalSales = 0;
    if ($pdo->query("SHOW TABLES LIKE 'sales'")->rowCount() > 0) {
        $stmt = $pdo->query("SELECT SUM(amount) as total_sales FROM sales");
        $totalSales = $stmt->fetch(PDO::FETCH_ASSOC)['total_sales'] ?? 0;
    }

    $stats = [
        'total_photographers' => $totalPhotographers,
        'total_users' => $totalUsers,
        'total_bookings' => $totalBookings,
        'total_sales' => $totalSales
    ];

    echo json_encode([
        'success' => true,
        'stats' => $stats
    ]);

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while fetching statistics'
    ]);
} 