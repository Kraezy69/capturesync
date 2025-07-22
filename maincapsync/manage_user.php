<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header('Location: main.php');
    exit;
}
require_once 'includes/db.php';

$msg = '';
$error = '';

// Handle Delete User
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $user_id = $_GET['id'];
    $user_type = $_GET['type'];
    
    try {
        if ($user_type === 'Client') {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND user_type = 'Client'");
        } else {
            $stmt = $pdo->prepare("DELETE FROM photographers WHERE id = ?");
        }
        $stmt->execute([$user_id]);
        header('Location: dashboard_admin.php?msg=User deleted successfully');
        exit;
    } catch (PDOException $e) {
        header('Location: dashboard_admin.php?error=Error deleting user: ' . urlencode($e->getMessage()));
        exit;
    }
}

// Handle Add/Edit User
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $user_type = $_POST['user_type'];
    $password = trim($_POST['password']);
    
    try {
        if ($user_id) {
            // Edit existing user
            if ($user_type === 'Client') {
                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET fullname = ?, email = ?, password = ? WHERE id = ? AND user_type = 'Client'");
                    $stmt->execute([$fullname, $email, $hashed_password, $user_id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ? AND user_type = 'Client'");
                    $stmt->execute([$fullname, $email, $user_id]);
                }
            } else {
                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE photographers SET fullname = ?, email = ?, password = ? WHERE id = ?");
                    $stmt->execute([$fullname, $email, $hashed_password, $user_id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE photographers SET fullname = ?, email = ? WHERE id = ?");
                    $stmt->execute([$fullname, $email, $user_id]);
                }
            }
            header('Location: dashboard_admin.php?msg=User updated successfully');
        } else {
            // Add new user
            if (empty($password)) {
                header('Location: dashboard_admin.php?error=Password is required for new users');
                exit;
            }
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            if ($user_type === 'Client') {
                $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, user_type) VALUES (?, ?, ?, 'Client')");
                $stmt->execute([$fullname, $email, $hashed_password]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO photographers (fullname, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$fullname, $email, $hashed_password]);
            }
            header('Location: dashboard_admin.php?msg=User added successfully');
        }
        exit;
    } catch (PDOException $e) {
        header('Location: dashboard_admin.php?error=Error saving user: ' . urlencode($e->getMessage()));
        exit;
    }
}
?> 