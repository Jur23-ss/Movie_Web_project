<?php
session_start();
include 'includes/db_connect.php';
include 'admin_check.php';

$orders = $conn->query("
    SELECT o.id AS order_id, o.created_at, u.name, u.email, m.title
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN order_items oi ON o.id = oi.order_id
    JOIN movies m ON oi.movie_id = m.id
    ORDER BY o.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>View Orders</title>
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
    </style>
</head>
<body>

<h2>All Orders</h2>
<a class="btn" href="admin.php">⬅ Back to Admin Panel</a>

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>User</th>
            <th>Email</th>
            <th>Movie Title</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $orders->fetch_assoc()): ?>
            <tr>
                <td><?= $row['order_id'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
