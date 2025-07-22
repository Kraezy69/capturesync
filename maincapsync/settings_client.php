<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Client') {
    header('Location: main.php');
    exit;
}
require_once 'includes/db.php';

$user_id = $_SESSION['user_id'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET fullname = ?, email = ?, password = ? WHERE id = ?");
            $stmt->execute([$fullname, $email, $hashed_password, $user_id]);
        } else {
            $msg = "Passwords do not match.";
        }
    } else {
        $stmt = $pdo->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ?");
        $stmt->execute([$fullname, $email, $user_id]);
    }
    $_SESSION['fullname'] = $fullname;
    $_SESSION['email'] = $email;
    $msg = "Profile updated!";
}

// Fetch current info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Client Settings</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #004e8f; }
        form { max-width: 400px; }
        label { display: block; margin-bottom: 8px; }
        input { width: 100%; padding: 8px; margin-bottom: 16px; }
        button { background: #004e8f; color: white; padding: 10px; border: none; cursor: pointer; }
        .success { color: green; }
    </style>
</head>
<body>
    <h2>Settings</h2>
    <?php if (!empty($msg)) echo "<div class='success'>$msg</div>"; ?>
    <form method="POST">
        <label>Full Name: <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required></label>
        <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required></label>
        <label>New Password: <input type="password" name="new_password" placeholder="Leave blank to keep current password" pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$" title="Password must contain at least one capital letter, one number, one special character, and be at least 8 characters long"></label>
        <label>Confirm New Password: <input type="password" name="confirm_password" placeholder="Confirm new password" pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$" title="Password must contain at least one capital letter, one number, one special character, and be at least 8 characters long"></label>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html> 