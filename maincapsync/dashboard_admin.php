<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug: Print session data
error_log("Session data in dashboard_admin.php: " . print_r($_SESSION, true));

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    error_log("Access denied: user_id set: " . isset($_SESSION['user_id']) . ", user_type: " . ($_SESSION['user_type'] ?? 'not set'));
    header('Location: main.php');
    exit;
}

require_once 'includes/db.php';

// Fetch photographers
$stmt = $pdo->query("SELECT * FROM photographers ORDER BY created_at DESC");
$photographers = $stmt->fetchAll();

// Get total users count (photographers + regular users)
$stmt = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM photographers) +
        (SELECT COUNT(*) FROM users WHERE user_type = 'User') as total_users
");
$totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

// Fetch recent activity logs (last 10 entries)
$stmt = $pdo->query("SELECT * FROM activity_log ORDER BY timestamp DESC LIMIT 10");
$logs = $stmt->fetchAll();

// Fetch admin log trail (all login events by this admin)
$admin_log_stmt = $pdo->prepare("SELECT * FROM activity_log WHERE user = ? AND action = 'Logged in' ORDER BY timestamp DESC LIMIT 20");
$admin_log_stmt->execute([$_SESSION['fullname']]);
$admin_logs = $admin_log_stmt->fetchAll();

// Fetch all client log trails (last 20 login events by any client)
$client_log_stmt = $pdo->query("SELECT * FROM activity_log WHERE user IN (SELECT fullname FROM users WHERE user_type = 'Client') AND action = 'Logged in' ORDER BY timestamp DESC LIMIT 20");
$client_logs = $client_log_stmt->fetchAll();

// Fetch all photographer log trails (last 20 login events by any photographer)
$photographer_log_stmt = $pdo->query("SELECT * FROM activity_log WHERE user IN (SELECT fullname FROM photographers) AND action = 'Logged in' ORDER BY timestamp DESC LIMIT 20");
$photographer_logs = $photographer_log_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CaptureSync</title>
    <link rel="stylesheet" href="dashboard_admin.css">
</head>
<body>
    <!-- Top Navbar -->
    <header class="navbar">
        <div class="logo" style="display: flex; align-items: center;">
            <i class="hamburger" id="sidebar-toggle" style="font-size: 2rem; margin-right: 18px; cursor: pointer; display: block;">☰</i>
            <span>CAPTURESYNC ADMIN</span>
        </div>
        <div class="nav-section">
            <div class="icons">
                <i class="bell">🔔</i>
                <div class="menu-container">
                    <i class="menu-icon">☰</i>
                    <div class="dropdown-menu">
                        <div class="user-info">
                            <div class="name"><?php echo htmlspecialchars($_SESSION['fullname']); ?></div>
                            <div class="role">Administrator</div>
                        </div>
                        <a href="#"><i>👤</i> Profile</a>
                        <a href="#"><i>⚙️</i> Settings</a>
                        <a href="logout.php" class="logout"><i>🚪</i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="admin-dashboard-wrapper">
        <nav class="sidebar" id="admin-sidebar" style="transform: translateX(-260px); transition: transform 0.3s;">
            <ul>
                <li><button class="sidebar-btn active" data-section="photographers-section">Photographer Management</button></li>
                <li><button class="sidebar-btn" data-section="users-section">User Management</button></li>
                <li><button class="sidebar-btn" data-section="activity-log-section">Activity Log</button></li>
                <li><button class="sidebar-btn" data-section="client-log-section">Client Log Trail</button></li>
                <li><button class="sidebar-btn" data-section="photographer-log-section">Photographer Log Trail</button></li>
            </ul>
        </nav>
    <main class="dashboard-content">
            <!-- Welcome Section and Stats Section remain always visible -->
        <section class="welcome-section">
            <h1>Welcome, Administrator!</h1>
            <p>Manage photographers and monitor system activity</p>
        </section>
        <section class="stats-section">
            <div class="stat-card">
                <h3>Total Photographers</h3>
                    <p id="total-photographers" class="stat-number">0</p>
            </div>
            <div class="stat-card">
                <h3>Total Users</h3>
                <p id="total-users" class="stat-number"><?php echo $totalUsers; ?></p>
            </div>
            <div class="stat-card">
                    <h3>Total Bookings</h3>
                    <p id="total-bookings" class="stat-number">0</p>
                </div>
                <div class="stat-card">
                    <h3>Total Sales</h3>
                    <p id="total-sales" class="stat-number">0</p>
            </div>
        </section>
            <div id="photographers-section" class="sidebar-section">
        <!-- Photographers Management -->
        <section class="photographers-section">
            <div class="section-header">
                <h2>Photographers Management</h2>
                <button class="add-btn" onclick="openAddModal()">Add Photographer</button>
            </div>
            <div class="photographers-table">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Specialty</th>
                            <th>Experience</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($photographers as $photographer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($photographer['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($photographer['email']); ?></td>
                            <td><?php echo htmlspecialchars($photographer['specialty']); ?></td>
                            <td><?php echo htmlspecialchars($photographer['experience']); ?> years</td>
                            <td><?php echo htmlspecialchars($photographer['location']); ?></td>
                            <td class="actions">
                                <button onclick="editPhotographer(<?php echo $photographer['id']; ?>)" class="edit-btn">Edit</button>
                                <button onclick="deletePhotographer(<?php echo $photographer['id']; ?>)" class="delete-btn">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
            </div>
            <div id="users-section" class="sidebar-section" style="display:none;">
                <!-- User Management -->
                <section class="users-section">
                    <div class="section-header">
                        <h2>User Management</h2>
                        <button class="add-btn" onclick="openAddUserModal()">Add User</button>
                    </div>
                    <div class="users-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Fetch all users
                                $stmt = $pdo->query("SELECT * FROM users WHERE user_type != 'Admin'");
                                $users = $stmt->fetchAll();
                                foreach ($users as $user): 
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                                    <td class="actions">
                                        <button onclick="editUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['fullname']); ?>', '<?php echo htmlspecialchars($user['email']); ?>', '<?php echo htmlspecialchars($user['user_type']); ?>')" class="edit-btn">Edit</button>
                                        <button onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['user_type']); ?>')" class="delete-btn">Delete</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div id="activity-log-section" class="sidebar-section" style="display:none;">
        <!-- Activity Log -->
        <section class="activity-log">
            <h2>Activity Log</h2>
            <div class="log-entries">
                <?php if (empty($logs)): ?>
                    <p class="no-logs">No recent activity to display.</p>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                    <div class="log-entry">
                        <span class="timestamp"><?php echo date('M d, Y H:i', strtotime($log['timestamp'])); ?></span>
                        <span class="action"><?php echo htmlspecialchars($log['action']); ?></span>
                        <span class="user"><?php echo htmlspecialchars($log['user']); ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <!-- Admin Log Trail -->
        <section class="admin-log-trail">
            <h2>My Admin Log Trail</h2>
            <div class="log-entries">
                <?php if (empty($admin_logs)): ?>
                    <p class="no-logs">No admin activity to display.</p>
                <?php else: ?>
                    <?php foreach ($admin_logs as $log): ?>
                    <div class="log-entry">
                        <span class="timestamp"><?php echo date('M d, Y H:i', strtotime($log['timestamp'])); ?></span>
                        <span class="action"><?php echo htmlspecialchars($log['action']); ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
            </div>
            <div id="client-log-section" class="sidebar-section" style="display:none;">
                <!-- Client Log Trail -->
                <section class="client-log-trail">
                    <h2>All Clients Log Trail</h2>
                    <div class="log-entries">
                        <?php if (empty($client_logs)): ?>
                            <p class="no-logs">No client activity to display.</p>
                        <?php else: ?>
                            <?php foreach ($client_logs as $log): ?>
                            <div class="log-entry">
                                <span class="timestamp"><?php echo date('M d, Y H:i', strtotime($log['timestamp'])); ?></span>
                                <span class="user" style="color:#2563eb;font-weight:600;">[<?php echo htmlspecialchars($log['user']); ?>]</span>
                                <span class="action"><?php echo htmlspecialchars($log['action']); ?></span>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
            <div id="photographer-log-section" class="sidebar-section" style="display:none;">
                <!-- Photographer Log Trail -->
                <section class="photographer-log-trail">
                    <h2>All Photographers Log Trail</h2>
                    <div class="log-entries">
                        <?php if (empty($photographer_logs)): ?>
                            <p class="no-logs">No photographer activity to display.</p>
                        <?php else: ?>
                            <?php foreach ($photographer_logs as $log): ?>
                            <div class="log-entry">
                                <span class="timestamp"><?php echo date('M d, Y H:i', strtotime($log['timestamp'])); ?></span>
                                <span class="user" style="color:#60a5fa;font-weight:600;">[<?php echo htmlspecialchars($log['user']); ?>]</span>
                                <span class="action"><?php echo htmlspecialchars($log['action']); ?></span>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
    </main>
    </div>
    <!-- Add Photographer Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="modal-title">Add New Photographer</h2>
            <form id="addPhotographerForm" action="add_photographer.php" method="POST">
                <input type="hidden" name="photographer_id" value="">
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <small>Leave blank to keep existing password when editing</small>
                </div>
                <div class="form-group">
                    <label for="specialty">Specialty</label>
                    <input type="text" id="specialty" name="specialty" required>
                </div>
                <div class="form-group">
                    <label for="experience">Experience (years)</label>
                    <input type="number" id="experience" name="experience" required>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" required>
                </div>
                <div class="form-group">
                    <label for="portfolio">Portfolio URL</label>
                    <input type="url" id="portfolio" name="portfolio" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <button type="submit" class="submit-btn">Add Photographer</button>
            </form>
        </div>
    </div>

    <!-- Edit Photographer Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="modal-title">Edit Photographer</h2>
            <form id="editPhotographerForm" action="edit_photographer.php" method="POST">
                <input type="hidden" name="photographer_id" value="">
                <div class="form-group">
                    <label for="edit_fullname">Full Name</label>
                    <input type="text" id="edit_fullname" name="fullname" required>
                </div>
                <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="edit_password">Password</label>
                    <input type="password" id="edit_password" name="password">
                    <small>Leave blank to keep existing password</small>
                </div>
                <div class="form-group">
                    <label for="edit_specialty">Specialty</label>
                    <input type="text" id="edit_specialty" name="specialty" required>
                </div>
                <div class="form-group">
                    <label for="edit_experience">Experience (years)</label>
                    <input type="number" id="edit_experience" name="experience" required>
                </div>
                <div class="form-group">
                    <label for="edit_location">Location</label>
                    <input type="text" id="edit_location" name="location" required>
                </div>
                <div class="form-group">
                    <label for="edit_portfolio">Portfolio URL</label>
                    <input type="url" id="edit_portfolio" name="portfolio" required>
                </div>
                <div class="form-group">
                    <label for="edit_status">Status</label>
                    <select id="edit_status" name="status" required>
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <button type="submit" class="submit-btn">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="modal-title">Add New User</h2>
            <form id="userForm" action="manage_user.php" method="POST">
                <input type="hidden" name="user_id" id="userId">
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="user_type">User Type</label>
                    <select id="user_type" name="user_type" required>
                        <option value="Client">Client</option>
                        <option value="Photographer">Photographer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$" title="Password must contain at least one capital letter, one number, one special character, and be at least 8 characters long">
                    <small>Leave blank to keep existing password when editing</small>
                </div>
                <button type="submit" class="submit-btn">Save User</button>
            </form>
        </div>
    </div>

    <script src="dashboard_admin.js"></script>
    <script>
        // User Management Functions
        function openAddUserModal() {
            document.getElementById('userModal').style.display = 'block';
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            document.querySelector('#userModal .modal-title').textContent = 'Add New User';
        }

        function editUser(id, fullname, email, userType) {
            document.getElementById('userModal').style.display = 'block';
            document.getElementById('userId').value = id;
            document.getElementById('fullname').value = fullname;
            document.getElementById('email').value = email;
            document.getElementById('user_type').value = userType;
            document.getElementById('password').value = '';
            document.querySelector('#userModal .modal-title').textContent = 'Edit User';
        }

        function deleteUser(id, userType) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = `manage_user.php?action=delete&id=${id}&type=${userType}`;
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle logic
    var sidebar = document.getElementById('admin-sidebar');
    var toggleBtn = document.getElementById('sidebar-toggle');
    var sidebarOpen = false;
    toggleBtn.addEventListener('click', function() {
        sidebarOpen = !sidebarOpen;
        if (sidebarOpen) {
            sidebar.style.transform = 'translateX(0)';
        } else {
            sidebar.style.transform = 'translateX(-260px)';
        }
    });
    // Optional: close sidebar when clicking outside (for mobile UX)
    document.addEventListener('click', function(e) {
        if (sidebarOpen && !sidebar.contains(e.target) && e.target !== toggleBtn) {
            sidebar.style.transform = 'translateX(-260px)';
            sidebarOpen = false;
        }
    });
});
</script>
</body>
</html> 