<?php
session_start();
include 'includes/db_connect.php';
include 'admin_check.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header("Location: manage_users.php");
exit;
