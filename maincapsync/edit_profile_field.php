<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Photographer') {
    header('Location: main.php');
    exit;
}

require_once 'includes/db.php';

$field = $_POST['field'] ?? '';
$value = $_POST['value'] ?? '';
$allowed_fields = ['specialty', 'experience', 'price', 'about_me'];

if (!in_array($field, $allowed_fields) || $value === '') {
    header('Location: dashboard_photographer.php?edit=error&message=Invalid+field+or+value');
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE photographers SET $field = :value WHERE id = :id");
    $stmt->execute(['value' => $value, 'id' => $_SESSION['user_id']]);
    $_SESSION[$field] = $value;
    header('Location: dashboard_photographer.php?edit=success');
    exit;
} catch (PDOException $e) {
    header('Location: dashboard_photographer.php?edit=error&message=' . urlencode($e->getMessage()));
    exit;
} 