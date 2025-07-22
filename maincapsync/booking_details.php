<?php
session_start();
require_once 'includes/db.php';

// Check if user is logged in and is a photographer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'photographer') {
    header('Location: login.php');
    exit;
}

$booking_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$booking = null;
$message = '';

if ($booking_id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM bookings WHERE id = ? AND photographer_id = ?');
    $stmt->execute([$booking_id, $_SESSION['user_id']]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$booking) {
    header('Location: dashboard_photographer.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accept'])) {
        $stmt = $pdo->prepare('UPDATE bookings SET status = ? WHERE id = ?');
        $stmt->execute(['accepted', $booking_id]);
        $message = 'Booking accepted!';
    } elseif (isset($_POST['decline'])) {
        $stmt = $pdo->prepare('UPDATE bookings SET status = ? WHERE id = ?');
        $stmt->execute(['declined', $booking_id]);
        $message = 'Booking declined.';
    }
    // Refresh booking data
    $stmt = $pdo->prepare('SELECT * FROM bookings WHERE id = ?');
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Details</title>
    <link rel="stylesheet" href="dashboard_client.css">
    <style>
        .booking-details-container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 36px 32px 28px 32px;
        }
        .booking-details-container h2 {
            color: #004e8f;
            text-align: center;
            margin-bottom: 24px;
        }
        .booking-details-container p {
            margin-bottom: 12px;
            color: #333;
        }
        .booking-details-container .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
        }
        .booking-details-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .booking-details-container button.accept {
            background: #27ae60;
            color: #fff;
        }
        .booking-details-container button.decline {
            background: #e74c3c;
            color: #fff;
        }
        .booking-details-container button:hover {
            opacity: 0.9;
        }
        .message {
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
    <div class="booking-details-container">
        <h2>Booking Details</h2>
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <p><strong>Client Name:</strong> <?= htmlspecialchars($booking['client_name']) ?></p>
        <p><strong>Client Email:</strong> <?= htmlspecialchars($booking['client_email']) ?></p>
        <p><strong>Client Phone:</strong> <?= htmlspecialchars($booking['client_phone']) ?></p>
        <p><strong>Event Date:</strong> <?= htmlspecialchars($booking['event_date']) ?></p>
        <p><strong>Event Time:</strong> <?= htmlspecialchars($booking['event_time']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($booking['location']) ?></p>
        <p><strong>Message:</strong> <?= htmlspecialchars($booking['message']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars(ucfirst($booking['status'])) ?></p>
        <div class="actions">
            <form method="POST" style="display:inline;">
                <button type="submit" name="accept" class="accept">Accept</button>
            </form>
            <form method="POST" style="display:inline;">
                <button type="submit" name="decline" class="decline">Decline</button>
            </form>
        </div>
    </div>
</body>
</html> 