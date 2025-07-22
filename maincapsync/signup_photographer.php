<?php
require_once 'includes/db.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $experience = trim($_POST['experience']);
    $specialty = trim($_POST['specialty']);
    $location = trim($_POST['location']);
    $portfolio = trim($_POST['portfolio']);

    // Validate inputs
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password) || empty($experience) || empty($specialty) || empty($location) || empty($portfolio)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/', $password)) {
        $error = "Password must be at least 8 characters, include an uppercase letter, a number, and a special character.";
    } elseif (!filter_var($portfolio, FILTER_VALIDATE_URL)) {
        $error = "Portfolio URL should be a valid website link.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM photographers WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email already registered as photographer.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $pdo->beginTransaction();
    try {
                $stmt = $pdo->prepare("INSERT INTO photographers (fullname, email, password, experience, specialty, location, portfolio) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$fullname, $email, $hashed_password, $experience, $specialty, $location, $portfolio]);
        $pdo->commit();
                header('Location: main.php?registered=1');
                exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Error saving data: " . $e->getMessage());
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
    <title>Photographer Sign Up - CaptureSync</title>
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
                <h2>Create Photographer Account</h2>
                <p>Join CaptureSync as a photographer and showcase your talent!</p>
            </div>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <form method="POST" action="signup_photographer.php" id="signup-photographer-form" autocomplete="off">
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
                        <input type="password" name="password" id="password" required pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$" title="Password must be at least 8 characters, include an uppercase letter, a number, and a special character.">
                    </div>
                    <div class="input-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <label for="experience">Years of Experience</label>
                        <input type="text" name="experience" id="experience" required value="<?php echo isset($_POST['experience']) ? htmlspecialchars($_POST['experience']) : ''; ?>">
                    </div>
                    <div class="input-group">
                        <label for="specialty-select">Photography Specialty</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <select id="specialty-select">
                                <option value="">Select specialty</option>
                                <option value="Portrait">Portrait</option>
                                <option value="Wedding">Wedding</option>
                                <option value="Event">Event</option>
                                <option value="Sports">Sports</option>
                                <option value="Fashion">Fashion</option>
                                <option value="Wildlife">Wildlife</option>
                                <option value="Product">Product</option>
                                <option value="Real Estate">Real Estate</option>
                                <option value="Travel">Travel</option>
                                <option value="Food">Food</option>
                                <option value="Newborn">Newborn</option>
                                <option value="Landscape">Landscape</option>
                                <option value="Architecture">Architecture</option>
                                <option value="Street">Street</option>
                                <option value="Documentary">Documentary</option>
                                <option value="Fine Art">Fine Art</option>
                                <option value="Commercial">Commercial</option>
                                <option value="Aerial">Aerial</option>
                                <option value="Underwater">Underwater</option>
                                <option value="Pet">Pet</option>
                                <option value="Macro">Macro</option>
                            </select>
                            <input type="text" id="specialty-custom" placeholder="Add custom" style="flex:1;" />
                            <button type="button" id="add-specialty-btn" class="btn" style="width:auto;padding:8px 14px;font-size:14px;">Add</button>
                        </div>
                        <div id="specialty-list" style="margin-top:10px;display:flex;flex-wrap:wrap;gap:8px;"></div>
                        <input type="hidden" name="specialty" id="specialty" value="<?php echo isset($_POST['specialty']) ? htmlspecialchars($_POST['specialty']) : ''; ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" required value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                    </div>
                    <div class="input-group">
                        <label for="portfolio">Portfolio URL</label>
                        <input type="url" name="portfolio" id="portfolio" required value="<?php echo isset($_POST['portfolio']) ? htmlspecialchars($_POST['portfolio']) : ''; ?>">
                    </div>
                </div>
                <div id="photographer-error" class="error" style="display:none;"></div>
                <button type="submit" class="btn">Create Account</button>
                <div class="links">
                    <a href="main.php">Already have an account? Login</a>
                </div>
            </form>
            <a href="choose_signup.php" class="back-to-options">&larr; Back to signup options</a>
        </div>
    </div>
    <script>
    // Client-side password match validation
    document.getElementById('signup-photographer-form').addEventListener('submit', function(e) {
        var pwd = document.getElementById('password');
        var cpwd = document.getElementById('confirm_password');
        var errorDiv = document.getElementById('photographer-error');
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

    // Specialty add/remove logic
    const specialtySelect = document.getElementById('specialty-select');
    const specialtyCustom = document.getElementById('specialty-custom');
    const addSpecialtyBtn = document.getElementById('add-specialty-btn');
    const specialtyList = document.getElementById('specialty-list');
    const specialtyInput = document.getElementById('specialty');
    let specialties = [];

    // Restore specialties from POST if available
    <?php if (!empty($_POST['specialty'])): ?>
        specialties = <?php echo json_encode(array_map('trim', explode(',', $_POST['specialty']))); ?>;
    <?php endif; ?>

    function renderSpecialties() {
        specialtyList.innerHTML = '';
        specialties.forEach((spec, idx) => {
            const tag = document.createElement('span');
            tag.textContent = spec;
            tag.style.background = '#2563eb';
            tag.style.color = '#fff';
            tag.style.padding = '4px 10px';
            tag.style.borderRadius = '16px';
            tag.style.display = 'inline-flex';
            tag.style.alignItems = 'center';
            tag.style.fontSize = '14px';
            tag.style.marginRight = '4px';
            tag.style.marginBottom = '4px';
            const remove = document.createElement('span');
            remove.textContent = '×';
            remove.style.marginLeft = '8px';
            remove.style.cursor = 'pointer';
            remove.onclick = function() {
                specialties.splice(idx, 1);
                renderSpecialties();
            };
            tag.appendChild(remove);
            specialtyList.appendChild(tag);
        });
        specialtyInput.value = specialties.join(',');
    }

    addSpecialtyBtn.onclick = function() {
        let val = specialtySelect.value;
        let custom = specialtyCustom.value.trim();
        let toAdd = custom || val;
        if (toAdd && !specialties.includes(toAdd)) {
            specialties.push(toAdd);
            renderSpecialties();
        }
        specialtySelect.value = '';
        specialtyCustom.value = '';
    };

    specialtyCustom.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addSpecialtyBtn.click();
        }
    });

    renderSpecialties();
    </script>
</body>
</html>
