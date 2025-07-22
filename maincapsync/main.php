<?php
// main.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db.php';
session_start();

// Session expiration: 30 minutes
$session_timeout = 1800; // 30*60 seconds
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_timeout)) {
    session_unset();
    session_destroy();
    header('Location: main.php?expired=1');
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

// Process login form if submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Debug information
    error_log("Login attempt - Email: " . $email);

    try {
        // First check in users table
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        // Debug user data
        error_log("User found in database: " . ($user ? "Yes" : "No"));
        if ($user) {
            error_log("User type: " . $user['user_type']);
            error_log("Stored password hash: " . $user['password']);
            error_log("Provided password: " . $password);
            
            // Test password verification
            $verify = password_verify($password, $user['password']);
            error_log("Password verification result: " . ($verify ? "Success" : "Failed"));

            if ($verify) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['fullname'] = $user['fullname'];
                // Add these lines to store contact and location in session for clients
                if ($user['user_type'] === 'Client') {
                    $_SESSION['contact'] = $user['contact'];
                    $_SESSION['location'] = $user['location'];
                }
                // Log login event
                $logStmt = $pdo->prepare("INSERT INTO activity_log (user, action) VALUES (?, ?)");
                $logStmt->execute([$_SESSION['fullname'], 'Logged in']);
                error_log("Session data set: " . print_r($_SESSION, true));
                
                // Redirect based on user type
                switch($user['user_type']) {
                    case 'Admin':
                        error_log("Redirecting to admin dashboard");
                        header('Location: dashboard_admin.php');
                        break;
                    case 'Client':
                        header('Location: dashboard_client.php');
                        break;
                    default:
                        header('Location: dashboard.php');
                }
                exit;
            } else {
                error_log("Password verification failed");
                $error = 'Invalid email or password';
            }
        } else {
            // If not found in users table, check photographers table
            $stmt = $pdo->prepare("SELECT * FROM photographers WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $photographer = $stmt->fetch();

            if ($photographer && password_verify($password, $photographer['password'])) {
                $_SESSION['user_id'] = $photographer['id'];
                $_SESSION['email'] = $photographer['email'];
                $_SESSION['user_type'] = 'Photographer';
                $_SESSION['fullname'] = $photographer['fullname'];
                $_SESSION['experience'] = $photographer['experience'];
                $_SESSION['specialty'] = $photographer['specialty'];
                $_SESSION['portfolio'] = $photographer['portfolio'];
                $_SESSION['profile_pic'] = $photographer['profile_pic'];
                $_SESSION['cover_photo'] = $photographer['cover_photo'];
                $_SESSION['price'] = $photographer['price'];
                $_SESSION['about_me'] = $photographer['about_me'];
                // Log login event
                $logStmt = $pdo->prepare("INSERT INTO activity_log (user, action) VALUES (?, ?)");
                $logStmt->execute([$_SESSION['fullname'], 'Logged in']);
                header('Location: dashboard_photographer.php');
                exit;
            }
        }

        // If not found in either table
        if (!isset($error)) {
            $error = 'Invalid email or password';
        }
        error_log("Login failed: " . $error);

    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
        error_log("Database error: " . $e->getMessage());
    }
}

// Debug: Show current session data
error_log("Current session data: " . print_r($_SESSION, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CaptureSync</title>
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
        <div class="login-box">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="main.php" method="POST">
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="show-password-group">
                    <input type="checkbox" id="show-password">
                    <label for="show-password">Show Password</label>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="links">
                    <a href="choose_signup.php">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
    <script>
    // If on landing.php, clicking logo refreshes
    document.querySelector('.logo-link').addEventListener('click', function(e) {
        if (window.location.pathname.endsWith('landing.php')) {
            e.preventDefault();
            window.location.reload();
        }
    });
    // Show/hide password logic
    document.getElementById('show-password').addEventListener('change', function() {
        var pwd = document.getElementById('password');
        pwd.type = this.checked ? 'text' : 'password';
    });
    </script>
</body>
</html>
