<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $movie_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND movie_id = ?");
    $stmt->bind_param("ii", $user_id, $movie_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, movie_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $movie_id);
        $stmt->execute();
    }

    header("Location: cart.php");
    exit;
} else {
    echo "No movie selected.";
}
