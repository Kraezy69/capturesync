<?php
require_once 'includes/db.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $error = "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $contact = trim($_POST["contact"]);
    $location = trim($_POST["location"]);

    // Validate inputs
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password) || empty($contact) || empty($location)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]).{8,}$/", $password)) {
        $error = "Password must be at least 8 characters, include an uppercase letter, a number, and a special character.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Email already registered.";
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, contact, location, user_type) VALUES (?, ?, ?, ?, ?, 'Client')");
            if ($stmt->execute([$fullname, $email, $hashed_password, $contact, $location])) {
                header('Location: main.php?registered=1');
                exit;
            } else {
                $error = "Failed to create account. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Sign Up - CaptureSync</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header class="main-header">
        <a href="landing.php" class="logo-link">
            <img src="logo/C__7_-removebg-preview.png" alt="CaptureSync Logo">
            <span>CAPTURESYNC</span>
        </a>
        <div class="header-right">
            <button class="go-back-btn" onclick="history.back()">Go Back</button>
        </div>
    </header>
    <div class="login-container">
        <div class="login-box signup-form">
            <div class="form-header">
                <h2>Create Client Account</h2>
                <p>Join CaptureSync to discover and book talented photographers</p>
            </div>

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>

            <form method="POST" action="signup.php" id="signup-form" autocomplete="off">
                <div class="form-row">
                    <div class="input-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" name="fullname" id="fullname" required value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                    </div>
                    <div class="input-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=[\]{};':\\|,.<>\/?]).{8,}$" title="Password must be at least 8 characters, include an uppercase letter, a number, and a special character.">
                    </div>
                    <div class="input-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group">
                        <label for="contact">Contact Number</label>
                        <input type="tel" name="contact" id="contact" required value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''; ?>">
                    </div>
                    <div class="input-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" placeholder="City, Country" required value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                    </div>
                </div>

                <div id="client-error" class="error" style="display:none;"></div>
                <button type="submit" class="btn">Create Account</button>

                <div class="links">
                    <a href="main.php">Already have an account? Login</a>
                </div>
            </form>
            <a href="choose_signup.php" class="back-to-options">← Back to signup options</a>
        </div>
    </div>
    <script>
    // Client-side password match validation
    document.getElementById('signup-form').addEventListener('submit', function(e) {
        var pwd = document.getElementById('password');
        var cpwd = document.getElementById('confirm_password');
        var errorDiv = document.getElementById('client-error');
        if (pwd.value !== cpwd.value) {
            e.preventDefault();
            errorDiv.textContent = 'Passwords do not match. Please re-enter your password.';
            errorDiv.style.display = 'block';
            pwd.value = '';
            cpwd.value = '';
            pwd.style.borderColor = '#e74c3c';
            cpwd.style.borderColor = '#e74c3c';
            pwd.focus();
        } else {
            errorDiv.style.display = 'none';
            pwd.style.borderColor = '';
            cpwd.style.borderColor = '';
        }
    });
    </script>
</body>
</html>
