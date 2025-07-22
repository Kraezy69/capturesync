<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Photographer') {
    header('Location: main.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['output_file']) && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);
    $file = $_FILES['output_file'];
    $fileName = $file['name'];
    $fileTmpPath = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];
    $fileNameCmps = explode('.', $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'zip'];

    if (!in_array($fileExtension, $allowedfileExtensions)) {
        header('Location: dashboard_photographer.php?error=invalid_file_type');
        exit;
    }

    // Directory for this booking
    $uploadDir = 'booking_outputs/' . $booking_id . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $newFileName = uniqid('output_', true) . '.' . $fileExtension;
    $dest_path = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        // Insert into booking_files
        $stmt = $pdo->prepare('INSERT INTO booking_files (booking_id, file_name, file_path, file_type) VALUES (?, ?, ?, ?)');
        if ($stmt->execute([$booking_id, $fileName, $dest_path, $fileType])) {
            // Update booking status to waiting_client_approval
            $update = $pdo->prepare("UPDATE bookings SET status = 'waiting_client_approval' WHERE id = ?");
            $update->execute([$booking_id]);
            header('Location: dashboard_photographer.php?success=file_uploaded');
            exit;
        } else {
            header('Location: dashboard_photographer.php?error=database');
            exit;
        }
    } else {
        header('Location: dashboard_photographer.php?error=upload_failed');
        exit;
    }
} else {
    header('Location: dashboard_photographer.php?error=upload_failed');
    exit;
} 