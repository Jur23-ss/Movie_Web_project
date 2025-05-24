<?php
session_start();
include 'includes/db_connect.php';
include 'admin_check.php';

if (!isset($_GET['id'])) {
    header("Location: admin_manage_sliders.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM sliders WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_manage_sliders.php");
exit;
