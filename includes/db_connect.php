<?php
$host = 'localhost';
$dbname = 'movies_project';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Start session only if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-login using remember_token if not already logged in
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $conn->prepare("SELECT id FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($userId);
    if ($stmt->fetch()) {
        $_SESSION['user_id'] = $userId;
    }
    $stmt->close();
}
?>
