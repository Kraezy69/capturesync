<?php
session_start();
require_once 'includes/db.php';

// Get photographer ID from URL
$photographer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($photographer_id <= 0) {
    echo '<h2>Invalid photographer ID.</h2>';
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM photographers WHERE id = ? AND is_visible = 1');
$stmt->execute([$photographer_id]);
$photographer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$photographer) {
    echo '<h2>Photographer not found or profile is private.</h2>';
    exit;
}

// Fetch reviews for this photographer
$reviews_stmt = $pdo->prepare('SELECT r.*, u.fullname AS client_name FROM reviews r JOIN users u ON r.client_id = u.id WHERE r.photographer_id = ? ORDER BY r.created_at DESC');
$reviews_stmt->execute([$photographer_id]);
$reviews = $reviews_stmt->fetchAll();
$avg_rating = null;
if ($reviews) {
    $avg_rating = round(array_sum(array_column($reviews, 'rating')) / count($reviews), 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($photographer['fullname']) ?> - Profile | CaptureSync</title>
    <link rel="stylesheet" href="dashboard_client.css">
    <style>
        .profile-banner {
            width: 100%;
            height: 260px;
            background: url('<?= htmlspecialchars($photographer['cover_photo'] ?: 'landingpageimg/default_cover.jpg') ?>') center center/cover no-repeat;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            position: relative;
        }
        .profile-main-card {
            background: #fff;
            max-width: 600px;
            margin: -80px auto 40px auto;
            border-radius: 30px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 40px 30px 30px 30px;
            position: relative;
            z-index: 2;
        }
        .profile-photo-large {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.10);
            position: absolute;
            top: -70px;
            left: 50%;
            transform: translateX(-50%);
            background: #f8f9fa;
        }
        .profile-info {
            margin-top: 80px;
            text-align: center;
        }
        .profile-info h1 {
            font-size: 2.1rem;
            color: #004e8f;
            margin-bottom: 8px;
        }
        .profile-info .specialty {
            color: #1abc9c;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 6px;
        }
        .profile-info .experience {
            color: #2980b9;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 6px;
        }
        .profile-info .price {
            background: #fff6e0;
            color: #e67e22;
            border: 2px solid #e67e22;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 18px;
            padding: 4px 18px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .profile-info .portfolio {
            margin-top: 18px;
        }
        .profile-info .portfolio a {
            color: #007bff;
            text-decoration: underline;
            font-weight: 500;
        }
        .profile-info .portfolio a:hover {
            color: #0056b3;
        }
        .back-btn {
            display: inline-block;
            margin-left: 40px;
            margin-bottom: 18px;
            background: #007bff;
            color: #fff;
            padding: 10px 22px;
            border-radius: 22px;
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: background 0.2s, transform 0.2s;
        }
        .back-btn:hover {
            background: #0056b3;
            transform: translateY(-2px) scale(1.03);
        }
        .back-btn-wrapper {
            width: 100%;
            display: flex;
            justify-content: flex-start;
            margin-top: 30px;
            margin-bottom: 0;
        }
        .book-now-btn {
            display: block;
            margin: 32px auto 0 auto;
            background: #27ae60;
            color: #fff;
            padding: 16px 48px;
            border-radius: 30px;
            font-size: 1.2rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 2px 12px rgba(39,174,96,0.10);
            transition: background 0.2s, transform 0.2s;
        }
        .book-now-btn:hover {
            background: #219150;
            transform: translateY(-2px) scale(1.04);
        }
    </style>
</head>
<body>
    <div class="back-btn-wrapper">
        <a href="dashboard_client.php" class="back-btn">&larr; Back to Photographers</a>
    </div>
    <div class="profile-banner"></div>
    <div class="profile-main-card">
        <img src="<?= htmlspecialchars($photographer['profile_pic'] ?: 'landingpageimg/default.jpg') ?>" alt="Profile Photo" class="profile-photo-large">
        <div class="profile-info">
            <h1><?= htmlspecialchars($photographer['fullname']) ?></h1>
            <div class="specialty">Specialty: <?= htmlspecialchars($photographer['specialty']) ?></div>
            <div class="experience">Experience: <?= htmlspecialchars($photographer['experience']) ?> years</div>
            <div class="price">₱<?= htmlspecialchars($photographer['price']) ?></div>
            <div class="portfolio">Portfolio: <a href="<?= htmlspecialchars($photographer['portfolio']) ?>" target="_blank">View Portfolio</a></div>
            <?php if (!empty($photographer['about_me'])): ?>
            <div class="aboutme-public" style="margin-top:18px;color:#444;font-size:1.08rem;line-height:1.6;">
                <strong>About Photographer:</strong> <?= nl2br(htmlspecialchars($photographer['about_me'])) ?>
            </div>
            <?php endif; ?>
        </div>
        <a href="book_photographer.php?id=<?= urlencode($photographer['id']) ?>" class="book-now-btn">Book Now</a>
    </div>
    <!-- Reviews Section -->
    <div style="max-width:600px;margin:0 auto 40px auto;background:#fff;border-radius:18px;box-shadow:0 2px 12px #eee;padding:32px 28px 18px 28px;">
        <h2 style="color:#2563eb; margin-bottom:18px;">Reviews</h2>
        <?php if ($avg_rating): ?>
            <div style="font-size:1.2rem; color:#e67e22; font-weight:700; margin-bottom:10px;">
                Average Rating: <?php for ($i=1; $i<=5; $i++) echo $i <= round($avg_rating) ? '★' : '☆'; ?>
                <span style="color:#444; font-size:1rem; font-weight:400;">(<?= $avg_rating ?>/5 from <?= count($reviews) ?> review<?= count($reviews) > 1 ? 's' : '' ?>)</span>
            </div>
        <?php else: ?>
            <div style="color:#888; margin-bottom:10px;">No reviews yet.</div>
        <?php endif; ?>
        <?php foreach ($reviews as $review): ?>
            <div style="border-bottom:1px solid #eee; padding:14px 0 10px 0; margin-bottom:8px;">
                <div style="font-size:1.1rem; color:#ffd700; margin-bottom:2px;">
                    <?php for ($i=1; $i<=5; $i++) echo $i <= $review['rating'] ? '★' : '☆'; ?>
                </div>
                <div style="color:#333; font-size:1.05rem; margin-bottom:4px;">"<?= htmlspecialchars($review['comment']) ?>"</div>
                <div style="color:#2563eb; font-size:0.98rem; font-weight:600; margin-bottom:2px;">
                    <?= htmlspecialchars($review['client_name']) ?>
                </div>
                <div style="color:#888; font-size:0.92rem;">Reviewed on <?= date('M d, Y', strtotime($review['created_at'])) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html> 