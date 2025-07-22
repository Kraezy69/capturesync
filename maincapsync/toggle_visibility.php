<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Photographer') {
    header('Location: main.php');
    exit;
}

require_once 'includes/db.php';

try {
    // Toggle visibility
    $stmt = $pdo->prepare("UPDATE photographers SET is_visible = NOT is_visible WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    
    // Get the new visibility status
    $stmt = $pdo->prepare("SELECT is_visible FROM photographers WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Update session
    $_SESSION['is_visible'] = $result['is_visible'];
    
    header('Location: dashboard_photographer.php?visibility=' . ($result['is_visible'] ? 'public' : 'private'));
    exit;
} catch (PDOException $e) {
    header('Location: dashboard_photographer.php?error=' . urlencode($e->getMessage()));
    exit;
}
?> 