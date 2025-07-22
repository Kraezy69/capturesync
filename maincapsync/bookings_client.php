<?php
session_start();

// Check if user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Client') {
    header('Location: main.php');
    exit;
}

require_once 'includes/db.php';

$client_id = $_SESSION['user_id'];

// Fetch bookings for this client
$stmt = $pdo->prepare('SELECT b.*, p.fullname AS photographer_name, p.profile_pic, p.specialty FROM bookings b JOIN photographers p ON b.photographer_id = p.id WHERE b.client_id = ? ORDER BY b.created_at DESC');
$stmt->execute([$client_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - CaptureSync</title>
    <link rel="stylesheet" href="dashboard_photographer.css">
    <style>
        .client-bookings-section {
            background: #fff;
            border-radius: 18px;
            padding: 24px;
            margin: 32px auto;
            max-width: 900px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .booking-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e9ecef;
            margin-bottom: 24px;
        }
        .booking-header {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 12px;
        }
        .photographer-pic {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #27ae60;
        }
        .photographer-info {
            flex: 1;
        }
        .booking-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        .booking-status.pending { background: #fff3cd; color: #856404; }
        .booking-status.accepted { background: #d4edda; color: #155724; }
        .booking-status.declined { background: #f8d7da; color: #721c24; }
        .booking-status.completed { background: #cce5ff; color: #004085; }
        .booking-details p { margin: 6px 0; color: #495057; }
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
            margin: 12px 0 18px 0;
            overflow: hidden;
        }
        .progress {
            height: 100%;
            border-radius: 4px;
            transition: width 0.4s;
        }
        .progress.pending { width: 25%; background: #ffc107; }
        .progress.accepted { width: 60%; background: #27ae60; }
        .progress.completed { width: 100%; background: #2563eb; }
        .progress.declined { width: 100%; background: #e74c3c; }
        .output-files {
            margin-top: 18px;
            background: #f1f8ff;
            border-radius: 8px;
            padding: 12px 16px;
        }
        .output-files h4 { margin: 0 0 10px 0; color: #2563eb; }
        .output-files ul { list-style: none; padding: 0; margin: 0; }
        .output-files li { margin-bottom: 8px; }
        .output-files a { color: #2563eb; text-decoration: none; font-weight: 500; }
        .output-files a:hover { text-decoration: underline; }
        .no-bookings { text-align: center; color: #6c757d; padding: 40px; background: #f8f9fa; border-radius: 12px; border: 1px dashed #dee2e6; }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <img src="logo/c (7).png" alt="CaptureSync Logo">
            <span>CAPTURESYNC</span>
        </div>
        <div class="nav-section">
            <a href="dashboard_client.php">Dashboard</a>
            <a href="#">Messages</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </header>
    <main>
        <section class="client-bookings-section">
            <h2>My Bookings</h2>
            <?php if (empty($bookings)): ?>
                <p class="no-bookings">You have no bookings yet.</p>
            <?php else: ?>
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-card">
                        <div class="booking-header">
                            <img src="<?= htmlspecialchars($booking['profile_pic'] ?? 'landingpageimg/default.jpg') ?>" class="photographer-pic" alt="Photographer">
                            <div class="photographer-info">
                                <div style="font-weight:600; font-size:18px; color:#004e8f;">
                                    <?= htmlspecialchars($booking['photographer_name']) ?>
                                </div>
                                <div style="font-size:14px; color:#888;">
                                    <?= htmlspecialchars($booking['specialty']) ?>
                                </div>
                            </div>
                            <span class="booking-status <?= strtolower($booking['status']) ?>">
                                <?= ucfirst(htmlspecialchars($booking['status'])) ?>
                            </span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress <?= strtolower($booking['status']) ?>"></div>
                        </div>
                        <div class="booking-details">
                            <p><strong>Event Date:</strong> <?= htmlspecialchars($booking['event_date']) ?></p>
                            <p><strong>Event Time:</strong> <?= htmlspecialchars($booking['event_time']) ?></p>
                            <p><strong>Location:</strong> <?= htmlspecialchars($booking['location']) ?></p>
                            <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($booking['message'])) ?></p>
                        </div>
                        <?php if ($booking['status'] === 'completed'): ?>
                            <?php
                            // Fetch uploaded files for this booking
                            $stmt2 = $pdo->prepare("SELECT * FROM booking_files WHERE booking_id = ? ORDER BY uploaded_at DESC");
                            $stmt2->execute([$booking['id']]);
                            $files = $stmt2->fetchAll();
                            if (!empty($files)):
                            ?>
                            <div class="output-files">
                                <h4>Final Output Files</h4>
                                <ul>
                                    <?php foreach ($files as $file): ?>
                                        <li>
                                            <a href="<?= htmlspecialchars($file['file_path']) ?>" target="_blank">
                                                <?= htmlspecialchars($file['file_name']) ?>
                                            </a>
                                            <span style="color:#888; font-size:12px; margin-left:8px;">
                                                (Uploaded: <?= date('M d, Y', strtotime($file['uploaded_at'])) ?>)
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html> 