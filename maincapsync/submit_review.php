<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Client') {
    header('Location: main.php');
    exit;
}

$client_id = $_SESSION['user_id'];
$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
$photographer_id = isset($_POST['photographer_id']) ? intval($_POST['photographer_id']) : 0;
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

if ($booking_id <= 0 || $photographer_id <= 0 || $rating < 1 || $rating > 5 || empty($comment)) {
    header('Location: bookings_client.php?review=invalid');
    exit;
}

// Prevent duplicate reviews for the same booking
$stmt = $pdo->prepare('SELECT id FROM reviews WHERE booking_id = ? AND client_id = ?');
$stmt->execute([$booking_id, $client_id]);
if ($stmt->fetch()) {
    header('Location: bookings_client.php?review=duplicate');
    exit;
}

// Insert the review
$stmt = $pdo->prepare('INSERT INTO reviews (booking_id, client_id, photographer_id, rating, comment) VALUES (?, ?, ?, ?, ?)');
if ($stmt->execute([$booking_id, $client_id, $photographer_id, $rating, $comment])) {
    header('Location: bookings_client.php?review=success');
    exit;
} else {
    header('Location: bookings_client.php?review=error');
    exit;
} 