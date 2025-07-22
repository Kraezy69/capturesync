<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Client') {
    header('Location: main.php');
    exit;
}
require_once 'includes/db.php';

// Fetch client info from session (or DB if needed)
$fullname = htmlspecialchars($_SESSION['fullname']);
$email = htmlspecialchars($_SESSION['email']);
$contact = isset($_SESSION['contact']) ? htmlspecialchars($_SESSION['contact']) : '';
$location = isset($_SESSION['location']) ? htmlspecialchars($_SESSION['location']) : '';
$profile_pic = !empty($_SESSION['profile_pic']) ? htmlspecialchars($_SESSION['profile_pic']) : 'profile_photos/photographer_2_1747652661.png';

// Fetch client log trail (all actions by this client)
$client_log_stmt = $pdo->prepare("SELECT * FROM activity_log WHERE user = ? ORDER BY timestamp DESC LIMIT 20");
$client_log_stmt->execute([$fullname]);
$client_logs = $client_log_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - CaptureSync</title>
    <link rel="stylesheet" href="client_profile.css">
</head>
<body>
<!-- Modern Sticky Navbar -->
<header class="modern-navbar">
    <div class="navbar-left">
        <a href="landing.php" class="navbar-logo-link" id="dashboard-logo-link">
            <img src="logo/C__7_-removebg-preview.png" alt="CaptureSync Logo" class="navbar-logo-img">
            <span class="navbar-logo-text">CAPTURESYNC</span>
        </a>
    </div>
    <div class="navbar-center">
        <span class="navbar-page-title">My Profile</span>
    </div>
    <div class="navbar-right">
        <a href="#" class="navbar-icon-link" title="Messages"><span class="navbar-icon">💬</span></a>
        <a href="#" class="navbar-icon-link" title="Notifications"><span class="navbar-icon">🔔</span></a>
        <div class="navbar-profile-menu" tabindex="0">
            <img src="<?= $profile_pic ?>" alt="Profile" class="navbar-profile-avatar">
            <span class="navbar-profile-name"><?= $fullname ?></span>
            <div class="navbar-dropdown-menu">
                <a href="client_profile.php"><i>👤</i> Profile</a>
                <a href="#"><i>⚙️</i> Settings</a>
                <a href="#"><i>💬</i> Messages</a>
                <a href="bookings_client.php"><i>📅</i> Bookings</a>
                <a href="#"><i>❤️</i> Favorites</a>
                <a href="#"><i>💰</i> Payment History</a>
                <a href="logout.php" class="logout"><i>🚪</i> Logout</a>
            </div>
        </div>
    </div>
</header>
<!-- END NAVBAR -->

<div class="client-profile-wrapper">
    <aside class="client-profile-sidebar">
        <div class="sidebar-profile-pic">
            <img src="<?= $profile_pic ?>" alt="Profile Photo">
        </div>
        <div class="sidebar-profile-name"><?= $fullname ?></div>
        <nav class="client-profile-nav">
            <a href="client_profile.php" class="active"><i>👤</i> Profile</a>
            <a href="bookings_client.php"><i>📅</i> Bookings</a>
            <a href="#"><i>⚙️</i> Settings</a>
            <a href="logout.php" class="logout"><i>🚪</i> Logout</a>
        </nav>
    </aside>
    <main class="client-profile-main">
        <div class="client-profile-card wide">
            <div class="profile-photo-section">
                <img src="<?= $profile_pic ?>" alt="Profile Photo" class="main-profile-pic" id="client-profile-pic">
                <button class="edit-photo-btn" id="edit-photo-btn">Edit Photo</button>
                <form id="profile-upload-form" action="upload_profile_photo.php" method="POST" enctype="multipart/form-data" style="display:none; flex-direction: column; gap: 8px; margin-top: 10px;">
                    <input type="file" name="profile_photo" accept="image/*" required>
                    <div style="display: flex; gap: 8px;">
                        <button type="submit">Upload Photo</button>
                        <button type="button" id="cancel-upload-btn" style="background: #ccc; color: #333;">Cancel</button>
                    </div>
                </form>
            </div>
            <div class="profile-info-section">
                <h2>Welcome, <?= $fullname ?>!</h2>
                <div class="profile-info-row"><span class="profile-label"><i>📧</i> Email:</span> <span><?= $email ?></span></div>
                <div class="profile-info-row"><span class="profile-label"><i>📞</i> Contact:</span> <span><?= $contact ? $contact : '<span class=\'missing\'>Not set</span>' ?></span></div>
                <div class="profile-info-row"><span class="profile-label"><i>📍</i> Location:</span> <span><?= $location ? $location : '<span class=\'missing\'>Not set</span>' ?></span></div>
                <div class="profile-info-row" style="align-items: flex-end;">
                    <span class="profile-label"><i>📊</i> Profile Completion:</span>
                    <div style="flex:1; max-width:220px; background:#e0e7ff; border-radius:8px; height:16px; margin-left:10px; position:relative;">
                        <?php
                        $fields = [$fullname, $email, $contact, $location, $profile_pic];
                        $filled = count(array_filter($fields));
                        $percent = round($filled / count($fields) * 100);
                        ?>
                        <div style="width:<?= $percent ?>%; background:linear-gradient(90deg,#2563eb 60%,#60a5fa 100%); height:100%; border-radius:8px;"></div>
                        <span style="position:absolute; left:50%; top:0; transform:translateX(-50%); font-size:12px; color:#2563eb; font-weight:700; line-height:16px;"> <?= $percent ?>% </span>
                    </div>
                </div>
                <div class="profile-info-row" style="gap:32px;">
                    <div><span class="profile-label"><i>📅</i> Bookings:</span> <span id="client-bookings-count">...</span></div>
                    <div><span class="profile-label"><i>❤️</i> Favorites:</span> <span id="client-favorites-count">...</span></div>
                    <div><span class="profile-label"><i>💬</i> Messages:</span> <span id="client-messages-count">...</span></div>
                </div>
                <div class="profile-quick-links">
                    <a href="bookings_client.php" class="quick-link-btn">My Bookings</a>
                    <a href="#" class="quick-link-btn">Messages</a>
                    <a href="#" class="quick-link-btn">Settings</a>
                </div>
                <button class="edit-profile-btn" id="edit-profile-btn">Edit Profile</button>
            </div>
        </div>
    </main>
</div>
<!-- Client Log Trail -->
<div class="client-log-trail" style="max-width:900px;margin:40px auto 0 auto;">
    <h2 style="color:#2563eb;">My Activity Log</h2>
    <div class="log-entries">
        <?php if (empty($client_logs)): ?>
            <p class="no-logs">No activity to display.</p>
        <?php else: ?>
            <?php foreach ($client_logs as $log): ?>
            <div class="log-entry" style="background:#f8f9fa;padding:10px 18px;border-radius:8px;margin-bottom:8px;">
                <span class="timestamp" style="color:#888;font-size:13px;"><?php echo date('M d, Y H:i', strtotime($log['timestamp'])); ?></span>
                <span class="action" style="margin-left:18px;font-size:15px;"><?php echo htmlspecialchars($log['action']); ?></span>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile dropdown logic for new navbar
    var profileMenu = document.querySelector('.navbar-profile-menu');
    if (profileMenu) {
        profileMenu.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('active');
        });
        document.addEventListener('click', function(e) {
            if (!profileMenu.contains(e.target)) {
                profileMenu.classList.remove('active');
            }
        });
    }
    // Profile photo upload logic
    const editPhotoBtn = document.getElementById('edit-photo-btn');
    const uploadForm = document.getElementById('profile-upload-form');
    const cancelUploadBtn = document.getElementById('cancel-upload-btn');
    editPhotoBtn.addEventListener('click', function() {
        uploadForm.style.display = 'flex';
        editPhotoBtn.style.display = 'none';
    });
    cancelUploadBtn.addEventListener('click', function() {
        uploadForm.style.display = 'none';
        editPhotoBtn.style.display = 'inline-block';
    });
    // Fetch client stats (simulate with random for now)
    document.getElementById('client-bookings-count').textContent = Math.floor(Math.random()*10+1);
    document.getElementById('client-favorites-count').textContent = Math.floor(Math.random()*5+1);
    document.getElementById('client-messages-count').textContent = Math.floor(Math.random()*20+1);
    // Edit profile modal (magic: not implemented, but button is ready)
    document.getElementById('edit-profile-btn').addEventListener('click', function() {
        alert('Profile editing coming soon!');
    });
    // Logo click logic for dashboard: refresh if logged in
    var logoLink = document.getElementById('dashboard-logo-link');
    if (logoLink) {
        logoLink.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.reload();
        });
    }
});
</script>
</body>
</html> 