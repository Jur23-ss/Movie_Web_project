<?php
session_start();
include 'includes/db_connect.php';
include 'admin_check.php';

$sliders = $conn->query("SELECT * FROM sliders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Sliders</title>
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
            margin-bottom: 1rem;
        }
        a.btn {
            background: #ff2c1f;
            color: white;
            padding: 0.6rem 1rem;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            display: inline-block;
        }
        table {
            width: 100%;
            background: #1c1c1c;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            border-bottom: 1px solid #333;
        }
        th {
            background: #2a2a2a;
        }
        a {
            color: #00ccff;
        }
        a.delete {
            color: #ff4d4d;
        }
    </style>
</head>
<body>
    <h2>Manage Slider Content</h2>
    <a class="btn" href="admin.php" style="margin-left: 1rem;">⬅ Back to Admin Panel</a>
    <a class="btn" href="add_slider.php">➕ Add Slide</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Headline</th>
            <th>Subheadline</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $sliders->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['image_filename']) ?></td>
            <td><?= htmlspecialchars($row['headline']) ?></td>
            <td><?= htmlspecialchars($row['subheadline']) ?></td>
            <td>
                <a href="delete_slider.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this slide?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
