<?php
// session_start(); // Uncomment if you are using session to track logged in user
session_start();

// Check if user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Client') {
    header('Location: main.php');
    exit;
}

require_once 'includes/db.php';

// Fetch real photographers from the database
$photographers = [];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM photographers WHERE is_visible = TRUE AND (fullname LIKE ? OR specialty LIKE ? OR location LIKE ?) ORDER BY created_at DESC");
    $like = "%$search%";
    $stmt->execute([$like, $like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM photographers WHERE is_visible = TRUE ORDER BY created_at DESC");
}
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $photographers[] = [
        'id' => $row['id'],
        'name' => $row['fullname'],
        'description' => $row['specialty'],
        'rating' => '5.0 ⭐⭐⭐⭐⭐', // You can update this if you have ratings
        'price' => '₱' . $row['price'], // Use the actual price from database
        'badge' => 'Professional',
        'image' => !empty($row['cover_photo']) ? $row['cover_photo'] : (!empty($row['portfolio']) ? $row['portfolio'] : 'landingpageimg/default.jpg'),
        'profile_pic' => !empty($row['profile_pic']) ? $row['profile_pic'] : 'landingpageimg/default.jpg'
];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard - CaptureSync</title>
    <link rel="stylesheet" href="dashboard_client.css">
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
        <form method="GET" action="dashboard_client.php" class="navbar-search-form">
            <input type="text" name="search" placeholder="Search photographers..." class="navbar-search-bar" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit" class="navbar-search-btn">🔍</button>
        </form>
                    </div>
    <div class="navbar-right">
        <a href="#" class="navbar-icon-link" title="Messages"><span class="navbar-icon">💬</span></a>
        <a href="#" class="navbar-icon-link" title="Notifications"><span class="navbar-icon">🔔</span></a>
        <div class="navbar-profile-menu" tabindex="0">
            <img src="<?= !empty($_SESSION['profile_pic']) ? htmlspecialchars($_SESSION['profile_pic']) : 'profile_photos/photographer_2_1747652661.png' ?>" alt="Profile" class="navbar-profile-avatar">
            <span class="navbar-profile-name"><?php echo htmlspecialchars($_SESSION['fullname']); ?></span>
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

<!-- Modern Filters -->
<section class="modern-filters">
    <select>
        <option value="">Specialization</option>
        <option value="portrait">Portrait</option>
        <option value="wedding">Wedding</option>
        <option value="event">Event</option>
    </select>
    <select>
        <option value="">Price Range</option>
        <option value="budget">Budget (Below ₱1000)</option>
        <option value="mid">Mid-Range (₱1000-₱2000)</option>
        <option value="premium">Premium (Above ₱2000)</option>
    </select>
    <select>
        <option value="">Rating</option>
        <option value="5">5 Stars</option>
        <option value="4">4+ Stars</option>
        <option value="3">3+ Stars</option>
    </select>
</section>

<!-- Main Content -->
<main class="modern-featured">
    <h2>Featured Photographers</h2>
    <div class="modern-photographer-grid">
        <?php foreach ($photographers as $photographer): ?>
        <a href="photographer_profile.php?id=<?= urlencode($photographer['id']) ?>" class="modern-photographer-card-link">
        <div class="modern-photographer-card">
            <img src="<?= htmlspecialchars($photographer['image']) ?>" alt="<?= htmlspecialchars($photographer['name']) ?>'s Work" class="modern-service-image">
            <div class="modern-card-info">
                <img class="modern-profile-pic" src="<?= htmlspecialchars($photographer['profile_pic']) ?>" alt="Profile of <?= htmlspecialchars($photographer['name']) ?>">
                <div class="modern-text-info">
                    <h3><?= htmlspecialchars($photographer['name']) ?></h3>
                    <p class="description"><?= htmlspecialchars($photographer['description']) ?></p>
                    <p class="rating"><?= htmlspecialchars($photographer['rating']) ?></p>
                    <p class="price"><?= htmlspecialchars($photographer['price']) ?></p>
                </div>
                <span class="modern-badge"><?= htmlspecialchars($photographer['badge']) ?></span>
            </div>
        </div>
        </a>
        <?php endforeach; ?>
    </div>
</main>

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
