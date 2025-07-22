<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

require_once 'includes/db.php';

// If an ID is provided, return a single photographer
if (isset($_GET['id']) && intval($_GET['id']) > 0) {
    $id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare('SELECT id, fullname, email, specialty, experience, location, portfolio, status FROM photographers WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $photographer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($photographer) {
            echo json_encode(['success' => true, 'photographer' => $photographer]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Photographer not found']);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
    exit;
}

// Otherwise, return all photographers (for the admin list)
try {
    $stmt = $pdo->query('SELECT id, fullname, email, specialty, experience, location, portfolio, status FROM photographers ORDER BY created_at DESC');
    $photographers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Get total photographers
    $totalPhotographers = count($photographers);
    // Get total users (user_type = Client)
    $stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users WHERE user_type = 'Client'");
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
    echo json_encode([
        'success' => true,
        'photographers' => $photographers,
        'stats' => [
            'total_photographers' => $totalPhotographers,
            'total_users' => $totalUsers,
            'total_bookings' => 0,
            'total_sales' => 0
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} 