<?php
session_start();
include 'includes/db_connect.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Clear the remember_token in the database
    $stmt = $conn->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

// Destroy session
session_destroy();

// Clear the remember_token cookie
setcookie('remember_token', '', time() - 3600, '/');

header("Location: index.php");
exit;
