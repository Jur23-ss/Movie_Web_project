<?php
session_start();
include 'includes/db_connect.php';
include 'admin_check.php';

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $hashed, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
    }

    $stmt->execute();
    header("Location: manage_users.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #0b0b0b;
            color: white;
            font-family: 'Poppins', sans-serif;
            padding: 2rem;
        }
        form {
            max-width: 500px;
            margin: auto;
            background: #1c1c1c;
            padding: 2rem;
            border-radius: 10px;
        }
        h2 {
            color: #ff2c1f;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        input {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            background: #2a2a2a;
            color: white;
            border: none;
        }
        .btn {
            width: 100%;
            padding: 0.8rem;
            background: #ff2c1f;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #0c4090;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Edit User</h2>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        <input type="text" value="<?= htmlspecialchars($user['email']) ?>" disabled>
        <input type="password" name="password" placeholder="New Password (leave blank to keep)">
        <button class="btn" type="submit">Update User</button>
    </form>
</body>
</html>
