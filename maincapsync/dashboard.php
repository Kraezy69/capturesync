<?php
// dashboard.php

// Start session to check login
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: main.php');
    exit;
}

// Redirect to specific dashboard based on user type
if ($_SESSION['user_type'] === 'Client') {
    header('Location: dashboard_client.php');
    exit;
} else if ($_SESSION['user_type'] === 'Photographer') {
    header('Location: dashboard_photographer.php');
    exit;
}

echo "Welcome, " . $_SESSION['fullname'] . "! You are logged in as " . $_SESSION['user_type'] . ".";
?>
