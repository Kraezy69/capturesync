<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header('Location: main.php');
    exit;
}
require_once 'includes/db.php';

$msg = '';
$error = '';

// Handle Delete User
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $user_type = $_POST['user_type'];
    
    try {
        if ($user_type === 'Client') {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        } else {
            $stmt = $pdo->prepare("DELETE FROM photographers WHERE id = ?");
        }
        $stmt->execute([$user_id]);
        $msg = "User deleted successfully!";
    } catch (PDOException $e) {
        $error = "Error deleting user: " . $e->getMessage();
    }
}

// Handle Add/Edit User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_user'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $user_type = $_POST['user_type'];
    $password = trim($_POST['password']);
    
    if (empty($password)) {
        $error = "Password is required!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            if (isset($_POST['user_id'])) {
                // Edit existing user
                if ($user_type === 'Client') {
                    $stmt = $pdo->prepare("UPDATE users SET fullname = ?, email = ?, password = ? WHERE id = ?");
                } else {
                    $stmt = $pdo->prepare("UPDATE photographers SET fullname = ?, email = ?, password = ? WHERE id = ?");
                }
                $stmt->execute([$fullname, $email, $hashed_password, $_POST['user_id']]);
                $msg = "User updated successfully!";
            } else {
                // Add new user
                if ($user_type === 'Client') {
                    $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
                } else {
                    $stmt = $pdo->prepare("INSERT INTO photographers (fullname, email, password) VALUES (?, ?, ?)");
                }
                $stmt->execute([$fullname, $email, $hashed_password]);
                $msg = "User added successfully!";
            }
        } catch (PDOException $e) {
            $error = "Error saving user: " . $e->getMessage();
        }
    }
}

// Fetch all users
$clients = $pdo->query("SELECT * FROM users")->fetchAll();
$photographers = $pdo->query("SELECT * FROM photographers")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users - Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h2 { color: #004e8f; }
        .success { color: green; background: #e8f5e9; padding: 10px; border-radius: 4px; }
        .error { color: red; background: #ffebee; padding: 10px; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f5f5f5; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-edit { background: #2196f3; color: white; }
        .btn-delete { background: #f44336; color: white; }
        .btn-add { background: #4caf50; color: white; margin-bottom: 20px; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 10% auto; padding: 20px; width: 50%; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>
        
        <?php if ($msg): ?>
            <div class="success"><?= $msg ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <button class="btn btn-add" onclick="openModal()">Add New User</button>

        <h3>Clients</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= $client['id'] ?></td>
                <td><?= htmlspecialchars($client['fullname']) ?></td>
                <td><?= htmlspecialchars($client['email']) ?></td>
                <td>
                    <button class="btn btn-edit" onclick="editUser(<?= $client['id'] ?>, '<?= htmlspecialchars($client['fullname']) ?>', '<?= htmlspecialchars($client['email']) ?>', 'Client')">Edit</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="user_id" value="<?= $client['id'] ?>">
                        <input type="hidden" name="user_type" value="Client">
                        <button type="submit" name="delete_user" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h3>Photographers</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($photographers as $photographer): ?>
            <tr>
                <td><?= $photographer['id'] ?></td>
                <td><?= htmlspecialchars($photographer['fullname']) ?></td>
                <td><?= htmlspecialchars($photographer['email']) ?></td>
                <td>
                    <button class="btn btn-edit" onclick="editUser(<?= $photographer['id'] ?>, '<?= htmlspecialchars($photographer['fullname']) ?>', '<?= htmlspecialchars($photographer['email']) ?>', 'Photographer')">Edit</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="user_id" value="<?= $photographer['id'] ?>">
                        <input type="hidden" name="user_type" value="Photographer">
                        <button type="submit" name="delete_user" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- Add/Edit User Modal -->
        <div id="userModal" class="modal">
            <div class="modal-content">
                <h3 id="modalTitle">Add New User</h3>
                <form method="POST">
                    <input type="hidden" name="user_id" id="userId">
                    <div class="form-group">
                        <label>User Type:</label>
                        <select name="user_type" id="userType" required>
                            <option value="Client">Client</option>
                            <option value="Photographer">Photographer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Full Name:</label>
                        <input type="text" name="fullname" id="fullname" required>
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" name="password" id="password" required pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$" title="Password must contain at least one capital letter, one number, one special character, and be at least 8 characters long">
                    </div>
                    <button type="submit" name="save_user" class="btn btn-add">Save User</button>
                    <button type="button" class="btn btn-delete" onclick="closeModal()">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalTitle').textContent = 'Add New User';
            document.getElementById('userId').value = '';
            document.getElementById('fullname').value = '';
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
            document.getElementById('userModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('userModal').style.display = 'none';
        }

        function editUser(id, fullname, email, userType) {
            document.getElementById('modalTitle').textContent = 'Edit User';
            document.getElementById('userId').value = id;
            document.getElementById('fullname').value = fullname;
            document.getElementById('email').value = email;
            document.getElementById('userType').value = userType;
            document.getElementById('password').value = '';
            document.getElementById('userModal').style.display = 'block';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('userModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html> 