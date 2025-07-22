<?php
session_start();
require_once 'includes/db.php';
$photographer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$photographer_name = '';
if ($photographer_id > 0) {
    $stmt = $pdo->prepare('SELECT fullname FROM photographers WHERE id = ?');
    $stmt->execute([$photographer_id]);
    $row = $stmt->fetch();
    if ($row) {
        $photographer_name = $row['fullname'];
    }
}
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = trim($_POST['name']);
    $client_email = trim($_POST['email']);
    $client_phone = trim($_POST['phone']);
    $event_date = trim($_POST['date']);
    $event_time = trim($_POST['time']);
    $location = trim($_POST['location']);
    $message = trim($_POST['message']);

    $stmt = $pdo->prepare("INSERT INTO bookings 
        (client_id, photographer_id, client_name, client_email, client_phone, event_date, event_time, location, message, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_SESSION['user_id'], $photographer_id, $client_name, $client_email, $client_phone, $event_date, $event_time, $location, $message, 'pending'
    ]);
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Photographer</title>
    <link rel="stylesheet" href="dashboard_client.css">
    <style>
        .booking-container {
            max-width: 480px;
            margin: 60px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 36px 32px 28px 32px;
        }
        .booking-container h2 {
            color: #004e8f;
            text-align: center;
            margin-bottom: 24px;
        }
        .booking-form label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        .booking-form input, .booking-form textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 18px;
            font-size: 15px;
        }
        .booking-form textarea {
            min-height: 70px;
            resize: vertical;
        }
        .booking-form button {
            width: 100%;
            background: #27ae60;
            color: #fff;
            padding: 12px 0;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .booking-form button:hover {
            background: #219150;
        }
        .success-msg {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <h2>Book <?= $photographer_name ? htmlspecialchars($photographer_name) : 'this photographer' ?></h2>
        <?php if ($success): ?>
            <div class="success-msg">Your booking request has been sent! The photographer will contact you soon.</div>
            <a href="dashboard_client.php" class="btn-back-dashboard" style="display:block;text-align:center;margin:18px auto 0 auto;padding:10px 24px;background:#004e8f;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;width:max-content;">Back to Dashboard</a>
        <?php else: ?>
        <form class="booking-form" method="POST">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" required>
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" required>
            <label for="date">Date</label>
            <input type="date" id="date" name="date" required>
            <label for="time">Time</label>
            <input type="time" id="time" name="time" required step="900">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" required>
            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="Describe your event or request..." required></textarea>
            <button type="submit">Send Booking Request</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html> 