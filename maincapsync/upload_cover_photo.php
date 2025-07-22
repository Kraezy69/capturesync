<?php
session_start();

// Only allow logged-in photographers
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Photographer') {
    header('Location: main.php');
    exit;
}

require_once 'includes/db.php';

// Handle file upload
if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['cover_photo']['tmp_name'];
    $fileName = $_FILES['cover_photo']['name'];
    $fileSize = $_FILES['cover_photo']['size'];
    $fileType = $_FILES['cover_photo']['type'];
    $fileNameCmps = explode('.', $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileExtension, $allowedfileExtensions)) {
        // Directory for uploads
        $uploadFileDir = 'cover_photos/';
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }
        $newFileName = 'cover_' . $_SESSION['user_id'] . '_' . time() . '.' . $fileExtension;
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Update DB
            $stmt = $pdo->prepare('UPDATE photographers SET cover_photo = ? WHERE id = ?');
            $stmt->execute([$dest_path, $_SESSION['user_id']]);
            // Update session
            $_SESSION['cover_photo'] = $dest_path;
            header('Location: dashboard_photographer.php?cover_upload=success');
            exit;
        } else {
            $error = 'Error moving the uploaded file.';
        }
    } else {
        $error = 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.';
    }
} else {
    $error = 'No file uploaded or upload error.';
}

header('Location: dashboard_photographer.php?cover_upload=error&message=' . urlencode($error));
exit; 