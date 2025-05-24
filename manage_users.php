<?php
session_start();
include 'includes/db_connect.php';
include 'admin_check.php';

$users = $conn->query("SELECT id, name, email, is_admin FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #0b0b0b;
            color: white;
            font-family: 'Poppins', sans-serif;
            padding: 2rem;
        }
        h2 {
            color: #ff2c1f;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background: #ff2c1f;
            color: white;
            padding: 0.7rem 1.5rem;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 2rem;
        }
        .btn:hover {
            background: #0c4090;
        }
        table {
            width: 100%;
            background: #1c1c1c;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            border-bottom: 1px solid #333;
            text-align: left;
        }
        th {
            background: #2a2a2a;
            color: #ff2c1f;
        }
        tr:nth-child(even) {
            background: #252525;
        }
        .actions a {
            margin-right: 1rem;
            color: #00ccff;
        }
        .actions a.delete {
            color: #ff4d4d;
        }
    </style>
</head>
<body>

<h2>Manage Users</h2>
<a class="btn" href="admin.php">⬅ Back to Admin Panel</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['is_admin'] ? 'Admin' : 'User' ?></td>
                <td class="actions">
                    <?php if (!$user['is_admin']): ?>
                        <a href="make_admin.php?id=<?= $user['id'] ?>">Promote</a>
                    <?php else: ?>
                        <a href="remove_admin.php?id=<?= $user['id'] ?>">Demote</a>
                    <?php endif; ?>
                    <a href="edit_user.php?id=<?= $user['id'] ?>">Edit</a>
                    <a href="delete_user.php?id=<?= $user['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
