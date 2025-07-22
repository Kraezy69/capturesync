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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Log the incoming data
    error_log("Received POST data: " . print_r($_POST, true));
    
    // Validate input
    $required_fields = ['fullname', 'email', 'password', 'specialty', 'experience', 'location', 'portfolio', 'status'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields: ' . implode(', ', $missing_fields)
        ]);
        exit;
    }
    
    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }
    
    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM photographers WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            exit;
        }
        
        // Hash password
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Insert new photographer
        $stmt = $pdo->prepare("
            INSERT INTO photographers (fullname, email, password, specialty, experience, location, portfolio, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $result = $stmt->execute([
            $_POST['fullname'],
            $_POST['email'],
            $hashed_password,
            $_POST['specialty'],
            $_POST['experience'],
            $_POST['location'],
            $_POST['portfolio'],
            $_POST['status'] ?? 'active'
        ]);

        if (!$result) {
            error_log("Database error in add_photographer.php: " . print_r($stmt->errorInfo(), true));
            throw new PDOException("Failed to insert photographer");
        }
        
        // Log the activity
        $stmt = $pdo->prepare("
            INSERT INTO activity_log (user, action, timestamp)
            VALUES (?, ?, NOW())
        ");
        
        $stmt->execute([
            $_SESSION['fullname'],
            "Added new photographer: " . $_POST['fullname']
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Photographer added successfully'
        ]);
        
    } catch (PDOException $e) {
        error_log("Database error in add_photographer.php: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
} 