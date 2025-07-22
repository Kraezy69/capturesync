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

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get form data
$id = $_POST['photographer_id'] ?? null;
$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$specialty = $_POST['specialty'] ?? '';
$experience = $_POST['experience'] ?? '';
$location = $_POST['location'] ?? '';
$portfolio = $_POST['portfolio'] ?? '';
$status = $_POST['status'] ?? 'active';

// Validate required fields
if (!$id || !$fullname || !$email || !$specialty || !$experience || !$location) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

try {
    // Check if photographer exists
    $stmt = $pdo->prepare("SELECT * FROM photographers WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $photographer = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$photographer) {
        echo json_encode(['success' => false, 'message' => 'Photographer not found']);
        exit;
    }

    // Prepare update query
    if (!empty($password)) {
        // Hash new password if provided
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE photographers SET fullname = :fullname, email = :email, password = :password, specialty = :specialty, experience = :experience, location = :location, portfolio = :portfolio, status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $params = [
            'id' => $id,
            'fullname' => $fullname,
            'email' => $email,
            'password' => $hashedPassword,
            'specialty' => $specialty,
            'experience' => $experience,
            'location' => $location,
            'portfolio' => $portfolio,
            'status' => $status
        ];
    } else {
        // Don't update password if not provided
        $updateQuery = "UPDATE photographers SET fullname = :fullname, email = :email, specialty = :specialty, experience = :experience, location = :location, portfolio = :portfolio, status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $params = [
            'id' => $id,
            'fullname' => $fullname,
            'email' => $email,
            'specialty' => $specialty,
            'experience' => $experience,
            'location' => $location,
            'portfolio' => $portfolio,
            'status' => $status
        ];
    }

    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute($params);

    if ($stmt->rowCount() > 0) {
        // Log the activity
        $logStmt = $pdo->prepare("INSERT INTO activity_log (user, action) VALUES (?, ?)");
        $logStmt->execute([
            $_SESSION['fullname'],
            "Updated photographer: $fullname"
        ]);
        echo json_encode(['success' => true, 'message' => 'Photographer updated successfully']);
    } else {
        echo json_encode(['success' => true, 'message' => 'No changes made, but update was successful.']);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} 