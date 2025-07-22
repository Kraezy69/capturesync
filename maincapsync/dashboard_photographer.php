<?php
session_start();

// Check if user is logged in and is a photographer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Photographer') {
    header('Location: main.php');
    exit;
}

require_once 'includes/db.php';

// Fetch bookings for this photographer
$bookings = [];
$photographer_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM bookings WHERE photographer_id = ? ORDER BY created_at DESC');
$stmt->execute([$photographer_id]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $bookings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Dashboard - CaptureSync</title>
    <link rel="stylesheet" href="dashboard_photographer.css">
</head>
<body>
    <!-- Top Navbar -->
    <header class="navbar">
        <div class="logo">
            <img src="logo/c (7).png" alt="CaptureSync Logo">
            <span>CAPTURESYNC</span>
        </div>  

        <div class="nav-section">
            <a href="#">Messages</a>
            <div class="icons">
                <i class="globe">🌐</i>
                <i class="bell">🔔</i>
                <div class="menu-container">
                    <i class="menu-icon">☰</i>
                    <div class="dropdown-menu">
                        <div class="user-info">
                            <div class="name"><?php echo htmlspecialchars($_SESSION['fullname']); ?></div>
                            <div class="email"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                            <div class="specialty"><?php echo htmlspecialchars($_SESSION['specialty']); ?></div>
                        </div>
                        <a href="#"><i>👤</i> Profile</a>
                        <a href="#"><i>⚙️</i> Settings</a>
                        <a href="#"><i>💼</i> Portfolio</a>
                        <a href="#bookings-section"><i>📅</i> Bookings</a>
                        <a href="#"><i>💬</i> Messages</a>
                        <a href="#"><i>💰</i> Earnings</a>
                        <a href="logout.php" class="logout"><i>🚪</i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="dashboard-content">
        <?php if (isset($_GET['success'])): ?>
            <div class="notification success">
                <?php
                $success = $_GET['success'];
                switch ($success) {
                    case 'file_uploaded':
                        echo 'File uploaded successfully!';
                        break;
                    default:
                        echo 'Operation completed successfully!';
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="notification error">
                <?php
                $error = $_GET['error'];
                switch ($error) {
                    case 'unauthorized':
                        echo 'You are not authorized to perform this action.';
                        break;
                    case 'database':
                        echo 'A database error occurred. Please try again.';
                        break;
                    case 'invalid_file_type':
                        echo 'Invalid file type. Please upload only images (JPEG, PNG, GIF), PDF, or ZIP files.';
                        break;
                    case 'upload_failed':
                        echo 'Failed to upload file. Please try again.';
                        break;
                    default:
                        echo 'An error occurred. Please try again.';
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['booking'])): ?>
            <div class="notification <?= $_GET['booking'] === 'accepted' ? 'success' : 'info' ?>">
                <?php if ($_GET['booking'] === 'accepted'): ?>
                    Booking has been accepted successfully!
                <?php else: ?>
                    Booking has been declined.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Welcome Section -->
        <section class="welcome-section" style="position: relative; text-align: center; background: none;">
            <div style="width: 100%; height: 220px; background: url('<?php echo !empty($_SESSION['cover_photo']) ? htmlspecialchars($_SESSION['cover_photo']) : 'landingpageimg/default_cover.jpg'; ?>') center center/cover no-repeat; border-top-left-radius: 18px; border-top-right-radius: 18px; position: relative;">
                <form id="cover-upload-form" action="upload_cover_photo.php" method="POST" enctype="multipart/form-data" style="position: absolute; bottom: -28px; right: 32px; display: flex; align-items: center; gap: 8px; z-index: 10;">
                    <input type="file" id="cover-photo-input" name="cover_photo" accept="image/*" style="display: none;">
                    <button type="button" id="cover-plus-btn" class="plus-btn" title="Add/Change Cover Photo">+</button>
                    <button type="submit" id="cover-upload-btn" style="display: none; background: #27ae60; color: #fff; border: none; border-radius: 4px; padding: 5px 14px; font-size: 15px; cursor: pointer;">Upload Cover</button>
                </form>
            </div>
            <div style="background: #fff; border-bottom-left-radius: 18px; border-bottom-right-radius: 18px; margin-top: -40px; padding-top: 0; position: relative; z-index: 1; width: 100%; display: flex; flex-direction: column; align-items: center;">
                <div class="profile-photo-container" style="margin-top: -70px;">
                    <div class="profile-photo-wrapper">
                        <img src="<?php echo !empty($_SESSION['profile_pic']) ? htmlspecialchars($_SESSION['profile_pic']) : 'landingpageimg/default.jpg'; ?>" alt="Profile Photo" class="profile-photo">
                    </div>
                    <button type="button" id="show-upload-btn" class="plus-btn" title="Add/Change Photo">+</button>
                    <form id="profile-upload-form" action="upload_profile_photo.php" method="POST" enctype="multipart/form-data" style="display: none; flex-direction: column; gap: 8px;">
                        <input type="file" name="profile_photo" accept="image/*" required>
                        <div style="display: flex; gap: 8px;">
                            <button type="submit">Upload Photo</button>
                            <button type="button" id="cancel-upload-btn" style="background: #ccc; color: #333;">Cancel</button>
                        </div>
                    </form>
                </div>
                <h1 style="text-align: center; width: 100%; margin-top: 18px;">Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
                <div class="profile-fields" style="justify-content: center; align-items: center;">
                    <div class="profile-field">
                        <span id="specialty-value">Specialty: <?php echo htmlspecialchars($_SESSION['specialty']); ?></span>
                        <button class="edit-field-btn" id="edit-specialty-btn" title="Edit Specialty">+</button>
                        <form id="edit-specialty-form" class="edit-inline-form" action="edit_profile_field.php" method="POST" style="display:none;">
                            <input type="hidden" name="field" value="specialty">
                            <input type="text" name="value" value="<?php echo htmlspecialchars($_SESSION['specialty']); ?>" required>
                            <button type="submit">Save</button>
                            <button type="button" class="cancel-edit-btn">Cancel</button>
                        </form>
                    </div>
                    <div class="profile-field">
                        <span id="experience-value">Experience: <?php echo htmlspecialchars($_SESSION['experience']); ?> years</span>
                        <button class="edit-field-btn" id="edit-experience-btn" title="Edit Experience">+</button>
                        <form id="edit-experience-form" class="edit-inline-form" action="edit_profile_field.php" method="POST" style="display:none;">
                            <input type="hidden" name="field" value="experience">
                            <input type="number" name="value" value="<?php echo htmlspecialchars($_SESSION['experience']); ?>" min="0" required>
                            <button type="submit">Save</button>
                            <button type="button" class="cancel-edit-btn">Cancel</button>
                        </form>
                    </div>
                    <div class="profile-field">
                        <span id="price-value">Price: <button class="price-btn" disabled>₱<?php echo isset($_SESSION['price']) ? htmlspecialchars($_SESSION['price']) : '1500'; ?></button></span>
                        <button class="edit-field-btn" id="edit-price-btn" title="Edit Price">+</button>
                        <form id="edit-price-form" class="edit-inline-form" action="edit_profile_field.php" method="POST" style="display:none;">
                            <input type="hidden" name="field" value="price">
                            <input type="number" name="value" value="<?php echo isset($_SESSION['price']) ? htmlspecialchars($_SESSION['price']) : '1500'; ?>" min="0" required>
                            <button type="submit">Save</button>
                            <button type="button" class="cancel-edit-btn">Cancel</button>
                        </form>
                    </div>
                    <div class="profile-field">
                        <span id="portfolio-value">Portfolio: <a href="<?php echo htmlspecialchars($_SESSION['portfolio']); ?>" target="_blank"><?php echo htmlspecialchars($_SESSION['portfolio']); ?></a></span>
                        <button class="edit-field-btn" id="edit-portfolio-btn" title="Edit Portfolio">+</button>
                        <form id="edit-portfolio-form" class="edit-inline-form" action="edit_profile_field.php" method="POST" style="display:none;">
                            <input type="hidden" name="field" value="portfolio">
                            <input type="url" name="value" value="<?php echo htmlspecialchars($_SESSION['portfolio']); ?>" required placeholder="Portfolio URL">
                            <button type="submit">Save</button>
                            <button type="button" class="cancel-edit-btn">Cancel</button>
                        </form>
                    </div>
                    <div class="profile-field">
                        <span id="aboutme-value">
                        <?php if (!empty($_SESSION['about_me'])): ?>
                            <?php echo nl2br(htmlspecialchars($_SESSION['about_me'])); ?>
                        <?php else: ?>
                            <span style="color:#bbb;font-style:italic;">Tell clients about yourself!</span>
                        <?php endif; ?>
                        </span>
                        <button class="edit-field-btn" id="edit-aboutme-btn" title="Edit About Me">+</button>
                        <form id="edit-aboutme-form" class="edit-inline-form" action="edit_profile_field.php" method="POST" style="display:none; width: 100%;">
                            <input type="hidden" name="field" value="about_me">
                            <textarea name="value" rows="3" style="width: 260px; border-radius: 6px; border: 1px solid #ccc; padding: 6px; font-size: 15px;" required><?php echo isset($_SESSION['about_me']) ? htmlspecialchars($_SESSION['about_me']) : ''; ?></textarea>
                            <button type="submit">Save</button>
                            <button type="button" class="cancel-edit-btn">Cancel</button>
                        </form>
                    </div>
                    <div class="profile-field" style="margin-top: 20px;">
                        <div class="visibility-buttons">
                            <button id="toggle-visibility-btn" class="post-profile-btn <?php echo isset($_SESSION['is_visible']) && $_SESSION['is_visible'] ? 'active' : ''; ?>">
                                <?php echo isset($_SESSION['is_visible']) && $_SESSION['is_visible'] ? 'Profile is Public' : 'Post Profile'; ?>
                            </button>
                            <?php if (isset($_SESSION['is_visible']) && $_SESSION['is_visible']): ?>
                            <button id="cancel-post-btn" class="cancel-post-btn">Cancel Post</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <p class="stat-number">0</p>
            </div>
            <div class="stat-card">
                <h3>Active Projects</h3>
                <p class="stat-number">0</p>
            </div>
            <div class="stat-card">
                <h3>Total Earnings</h3>
                <p class="stat-number">₱0</p>
            </div>
            <div class="stat-card">
                <h3>Profile Views</h3>
                <p class="stat-number">0</p>
            </div>
        </section>

        <!-- Recent Activity -->
        <section class="recent-activity">
            <h2>Recent Activity</h2>
            <div class="activity-list">
                <p class="no-activity">No recent activity to display.</p>
            </div>
        </section>

        <!-- Upcoming Bookings -->
        <section class="upcoming-bookings">
            <h2>Upcoming Bookings</h2>
            <div class="bookings-list">
                <p class="no-bookings">No upcoming bookings.</p>
            </div>
        </section>

        <!-- Bookings Section -->
        <section id="bookings-section" class="bookings-section">
            <h2>Booking Requests</h2>
            <div class="bookings-container">
                <?php if (empty($bookings)): ?>
                    <p class="no-bookings">No booking requests yet.</p>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                        <div class="booking-card">
                            <div class="booking-header">
                                <h3>Booking from <?= htmlspecialchars($booking['client_name']) ?></h3>
                                <span class="booking-status <?= strtolower($booking['status']) ?>">
                                    <?= ucfirst(htmlspecialchars($booking['status'])) ?>
                                </span>
                            </div>
                            <div class="booking-details">
                                <p><strong>Event Date:</strong> <?= htmlspecialchars($booking['event_date']) ?></p>
                                <p><strong>Event Time:</strong> <?= htmlspecialchars($booking['event_time']) ?></p>
                                <p><strong>Location:</strong> <?= htmlspecialchars($booking['location']) ?></p>
                                <p><strong>Client Email:</strong> <?= htmlspecialchars($booking['client_email']) ?></p>
                                <p><strong>Client Phone:</strong> <?= htmlspecialchars($booking['client_phone']) ?></p>
                                <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($booking['message'])) ?></p>
                                
                                <?php if ($booking['status'] === 'accepted'): ?>
                                    <div class="upload-section">
                                        <h4>Upload Final Output</h4>
                                        <form action="upload_booking_file.php" method="POST" enctype="multipart/form-data" class="upload-form">
                                            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                            <div class="file-input-wrapper">
                                                <input type="file" name="output_file" id="output_file_<?= $booking['id'] ?>" required>
                                                <label for="output_file_<?= $booking['id'] ?>" class="file-input-label">
                                                    Choose File
                                                </label>
                                                <span class="file-name">No file chosen</span>
                                            </div>
                                            <button type="submit" class="upload-btn">Upload Output</button>
                                        </form>
                                    </div>
                                <?php endif; ?>

                                <?php if ($booking['status'] === 'completed'): ?>
                                    <?php
                                    // Fetch uploaded files for this booking
                                    $stmt = $pdo->prepare("SELECT * FROM booking_files WHERE booking_id = ? ORDER BY uploaded_at DESC");
                                    $stmt->execute([$booking['id']]);
                                    $files = $stmt->fetchAll();
                                    if (!empty($files)): ?>
                                        <div class="uploaded-files">
                                            <h4>Uploaded Files</h4>
                                            <ul>
                                                <?php foreach ($files as $file): ?>
                                                    <li>
                                                        <a href="<?= htmlspecialchars($file['file_path']) ?>" target="_blank">
                                                            <?= htmlspecialchars($file['file_name']) ?>
                                                        </a>
                                                        <span class="upload-date">
                                                            Uploaded: <?= date('M d, Y', strtotime($file['uploaded_at'])) ?>
                                                        </span>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php if ($booking['status'] === 'pending'): ?>
                                <div class="booking-actions">
                                    <form action="update_booking.php" method="POST" class="inline-form">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <button type="submit" name="action" value="accept" class="accept-btn">Accept</button>
                                        <button type="submit" name="action" value="decline" class="decline-btn">Decline</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <style>
            .bookings-section {
                background: #fff;
                border-radius: 18px;
                padding: 24px;
                margin-top: 24px;
                box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            }

            .bookings-section h2 {
                color: #004e8f;
                margin-bottom: 20px;
            }

            .bookings-container {
                display: grid;
                gap: 20px;
            }

            .booking-card {
                background: #f8f9fa;
                border-radius: 12px;
                padding: 20px;
                border: 1px solid #e9ecef;
            }

            .booking-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 16px;
            }

            .booking-header h3 {
                color: #333;
                margin: 0;
            }

            .booking-status {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 14px;
                font-weight: 500;
            }

            .booking-status.pending {
                background: #fff3cd;
                color: #856404;
            }

            .booking-status.accepted {
                background: #d4edda;
                color: #155724;
            }

            .booking-status.declined {
                background: #f8d7da;
                color: #721c24;
            }

            .booking-details {
                margin-bottom: 16px;
            }

            .booking-details p {
                margin: 8px 0;
                color: #495057;
            }

            .booking-actions {
                display: flex;
                gap: 12px;
            }

            .inline-form {
                display: flex;
                gap: 12px;
            }

            .accept-btn, .decline-btn {
                padding: 8px 20px;
                border: none;
                border-radius: 6px;
                font-weight: 500;
                cursor: pointer;
                transition: background-color 0.2s;
            }

            .accept-btn {
                background: #27ae60;
                color: white;
            }

            .accept-btn:hover {
                background: #219150;
            }

            .decline-btn {
                background: #e74c3c;
                color: white;
            }

            .decline-btn:hover {
                background: #c0392b;
            }

            .no-bookings {
                text-align: center;
                color: #6c757d;
                padding: 40px;
                background: #f8f9fa;
                border-radius: 12px;
                border: 1px dashed #dee2e6;
            }

            .notification {
                padding: 12px 20px;
                border-radius: 8px;
                margin-bottom: 20px;
                font-weight: 500;
                animation: slideIn 0.3s ease-out;
            }

            .notification.success {
                background: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .notification.info {
                background: #cce5ff;
                color: #004085;
                border: 1px solid #b8daff;
            }

            .notification.error {
                background: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }

            @keyframes slideIn {
                from {
                    transform: translateY(-10px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .upload-section {
                margin-top: 20px;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 8px;
                border: 1px solid #e9ecef;
            }

            .upload-section h4 {
                margin: 0 0 15px 0;
                color: #333;
            }

            .upload-form {
                display: flex;
                gap: 10px;
                align-items: center;
            }

            .file-input-wrapper {
                position: relative;
                flex-grow: 1;
            }

            .file-input-wrapper input[type="file"] {
                position: absolute;
                left: 0;
                top: 0;
                opacity: 0;
                width: 100%;
                height: 100%;
                cursor: pointer;
            }

            .file-input-label {
                display: inline-block;
                padding: 8px 16px;
                background: #e9ecef;
                border-radius: 4px;
                cursor: pointer;
                color: #495057;
                font-weight: 500;
            }

            .file-name {
                margin-left: 10px;
                color: #6c757d;
            }

            .upload-btn {
                padding: 8px 20px;
                background: #27ae60;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: 500;
                transition: background-color 0.2s;
            }

            .upload-btn:hover {
                background: #219150;
            }

            .uploaded-files {
                margin-top: 20px;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 8px;
                border: 1px solid #e9ecef;
            }

            .uploaded-files h4 {
                margin: 0 0 15px 0;
                color: #333;
            }

            .uploaded-files ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .uploaded-files li {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 0;
                border-bottom: 1px solid #e9ecef;
            }

            .uploaded-files li:last-child {
                border-bottom: none;
            }

            .uploaded-files a {
                color: #2563eb;
                text-decoration: none;
                font-weight: 500;
            }

            .uploaded-files a:hover {
                text-decoration: underline;
            }

            .upload-date {
                color: #6c757d;
                font-size: 0.9em;
            }
        </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuIcon = document.querySelector('.menu-icon');
        const dropdownMenu = document.querySelector('.dropdown-menu');
                const showUploadBtn = document.getElementById('show-upload-btn');
                const uploadForm = document.getElementById('profile-upload-form');
                const cancelUploadBtn = document.getElementById('cancel-upload-btn');

        menuIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('active');
            }
        });

                // Show upload form when plus button is clicked
                showUploadBtn.addEventListener('click', function(e) {
                    showUploadBtn.style.display = 'none';
                    uploadForm.style.display = 'flex';
                });

                // Hide upload form when cancel is clicked
                cancelUploadBtn.addEventListener('click', function(e) {
                    uploadForm.style.display = 'none';
                    showUploadBtn.style.display = 'block';
                });

                // Auto-hide upload notification
                const uploadMsg = document.querySelector('.upload-message');
                if (uploadMsg) {
                    setTimeout(() => {
                        uploadMsg.style.display = 'none';
                    }, 3000);
                }

                // Inline edit for specialty
                document.getElementById('edit-specialty-btn').onclick = function() {
                    document.getElementById('specialty-value').style.display = 'none';
                    this.style.display = 'none';
                    document.getElementById('edit-specialty-form').style.display = 'inline-flex';
                };
                // Inline edit for experience
                document.getElementById('edit-experience-btn').onclick = function() {
                    document.getElementById('experience-value').style.display = 'none';
                    this.style.display = 'none';
                    document.getElementById('edit-experience-form').style.display = 'inline-flex';
                };
                // Inline edit for price
                document.getElementById('edit-price-btn').onclick = function() {
                    document.getElementById('price-value').style.display = 'none';
                    this.style.display = 'none';
                    document.getElementById('edit-price-form').style.display = 'inline-flex';
                };
                // Inline edit for portfolio
                document.getElementById('edit-portfolio-btn').onclick = function() {
                    document.getElementById('portfolio-value').style.display = 'none';
                    this.style.display = 'none';
                    document.getElementById('edit-portfolio-form').style.display = 'inline-flex';
                };
                // Inline edit for about me
                document.getElementById('edit-aboutme-btn').onclick = function() {
                    document.getElementById('aboutme-value').style.display = 'none';
                    this.style.display = 'none';
                    document.getElementById('edit-aboutme-form').style.display = 'inline-flex';
                };
                // Cancel buttons
                document.querySelectorAll('.cancel-edit-btn').forEach(function(btn) {
                    btn.onclick = function() {
                        const form = this.closest('form');
                        form.style.display = 'none';
                        const fieldDiv = form.closest('.profile-field');
                        fieldDiv.querySelector('span').style.display = 'inline';
                        fieldDiv.querySelector('.edit-field-btn').style.display = 'inline-block';
                    };
                });

                // Cover photo plus button logic
                const coverPlusBtn = document.getElementById('cover-plus-btn');
                const coverPhotoInput = document.getElementById('cover-photo-input');
                const coverUploadBtn = document.getElementById('cover-upload-btn');
                if (coverPlusBtn && coverPhotoInput && coverUploadBtn) {
                    coverPlusBtn.onclick = function() {
                        coverPhotoInput.click();
                    };
                    coverPhotoInput.onchange = function() {
                        if (coverPhotoInput.files.length > 0) {
                            coverUploadBtn.style.display = 'inline-block';
                        } else {
                            coverUploadBtn.style.display = 'none';
                        }
                    };
                }

                // Handle visibility toggle
                const toggleVisibilityBtn = document.getElementById('toggle-visibility-btn');
                if (toggleVisibilityBtn) {
                    toggleVisibilityBtn.addEventListener('click', function() {
                        window.location.href = 'toggle_visibility.php';
                    });
                }

                // Handle cancel post
                const cancelPostBtn = document.getElementById('cancel-post-btn');
                if (cancelPostBtn) {
                    cancelPostBtn.addEventListener('click', function() {
                        if (confirm('Are you sure you want to make your profile private? This will hide it from clients.')) {
                            window.location.href = 'toggle_visibility.php';
                        }
                    });
                }

                // Show visibility status message
                const urlParams = new URLSearchParams(window.location.search);
                const visibilityStatus = urlParams.get('visibility');
                if (visibilityStatus) {
                    const message = visibilityStatus === 'public' ? 
                        'Your profile is now visible to clients!' : 
                        'Your profile is now private.';
                    alert(message);
                }

                // Auto-hide notifications after 5 seconds
                const notifications = document.querySelectorAll('.notification');
                notifications.forEach(notification => {
                    setTimeout(() => {
                        notification.style.opacity = '0';
                        notification.style.transform = 'translateY(-10px)';
                        setTimeout(() => notification.remove(), 300);
                    }, 5000);
                });

                // Smooth scroll to bookings section
                document.querySelector('a[href="#bookings-section"]').addEventListener('click', function(e) {
                    e.preventDefault();
                    const bookingsSection = document.getElementById('bookings-section');
                    bookingsSection.scrollIntoView({ behavior: 'smooth' });
                    // Close the dropdown menu after clicking
                    dropdownMenu.classList.remove('active');
                });

                // File input handling
                document.querySelectorAll('input[type="file"]').forEach(input => {
                    input.addEventListener('change', function(e) {
                        const fileName = this.files[0]?.name || 'No file chosen';
                        this.parentElement.querySelector('.file-name').textContent = fileName;
                    });
        });
    });
    </script>
    </main>
</body>
</html> 