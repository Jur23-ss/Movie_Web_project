<?php
session_start();
include 'includes/db_connect.php';
include 'admin_check.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Optional: check if movie exists first
    $stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: manage_movies.php");
exit;
