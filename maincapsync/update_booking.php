<?php
session_start();

// Check if user is logged in and is a photographer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Photographer') {
    header('Location: main.php');
    exit;
}

require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($booking_id > 0 && in_array($action, ['accept', 'decline'])) {
        try {
            // First verify this booking belongs to this photographer
            $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ? AND photographer_id = ?");
            $stmt->execute([$booking_id, $_SESSION['user_id']]);
            $booking = $stmt->fetch();
            
            if ($booking) {
                // Update the booking status
                $new_status = $action === 'accept' ? 'accepted' : 'declined';
                $update = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
                $update->execute([$new_status, $booking_id]);
                
                // Log the activity
                $log_message = "Booking #$booking_id has been " . $new_status;
                $log = $pdo->prepare("INSERT INTO activity_log (user_id, action, details) VALUES (?, ?, ?)");
                $log->execute([$_SESSION['user_id'], 'booking_update', $log_message]);
                
                header('Location: dashboard_photographer.php?booking=' . $new_status);
                exit;
            } else {
                // Booking not found or doesn't belong to this photographer
                header('Location: dashboard_photographer.php?error=unauthorized');
                exit;
            }
        } catch (PDOException $e) {
            error_log("Error updating booking: " . $e->getMessage());
            header('Location: dashboard_photographer.php?error=database');
            exit;
        }
    }
}

// If we get here, just redirect back to dashboard without an error
header('Location: dashboard_photographer.php');
exit; 