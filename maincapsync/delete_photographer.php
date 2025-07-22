<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid photographer ID']);
        exit;
    }
    
    try {
        // Get photographer name for activity log
        $stmt = $pdo->prepare("SELECT fullname FROM photographers WHERE id = ?");
        $stmt->execute([$data['id']]);
        $photographer = $stmt->fetch();
        
        if (!$photographer) {
            echo json_encode(['success' => false, 'message' => 'Photographer not found']);
            exit;
        }
        
        // Delete photographer
        $stmt = $pdo->prepare("DELETE FROM photographers WHERE id = ?");
        $stmt->execute([$data['id']]);
        
        if ($stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Failed to delete photographer']);
            exit;
        }
        
        // Log the activity
        $stmt = $pdo->prepare("
            INSERT INTO activity_log (user, action, timestamp)
            VALUES (?, ?, NOW())
        ");
        
        $stmt->execute([
            $_SESSION['fullname'],
            "Deleted photographer: " . $photographer['fullname']
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Photographer deleted successfully'
        ]);
        
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Database error occurred'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
} 