<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Current Session Data:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['user_type'])) {
    echo "<p>User Type: " . $_SESSION['user_type'] . "</p>";
    if ($_SESSION['user_type'] === 'Admin') {
        echo "<p style='color: green;'>You are logged in as an Admin!</p>";
    } else {
        echo "<p style='color: red;'>You are not logged in as an Admin.</p>";
    }
} else {
    echo "<p style='color: red;'>No user type set in session.</p>";
}

echo "<h3>Test Links:</h3>";
echo "<ul>";
echo "<li><a href='main.php'>Go to Login Page</a></li>";
echo "<li><a href='dashboard_admin.php'>Go to Admin Dashboard</a></li>";
echo "<li><a href='logout.php'>Logout</a></li>";
echo "</ul>"; 