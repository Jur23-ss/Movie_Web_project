<?php
session_start();
include 'includes/db_connect.php';
include 'admin_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $desc = $_POST['description'];
    $price = floatval($_POST['price']);
    $section = $_POST['section'] ?? 'featured'; // 'featured' or 'coming'
    $image = null;

    // Handle image upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $filename = basename($_FILES['image_file']['name']);
        $target = "images.png/" . $filename;
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target)) {
            $image = $filename;
        } else {
            die("❌ Failed to upload image.");
        }
    } else {
        die("❌ Image file is required.");
    }

    // Save movie
    $stmt = $conn->prepare("INSERT INTO movies (title, genre, duration, image_filename, description, price, section) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $title, $genre, $duration, $image, $desc, $price, $section);
    $stmt->execute();
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Movie</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #0b0b0b;
            color: white;
            font-family: 'Poppins', sans-serif;
            padding: 2rem;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: #1c1c1c;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px #ff2c1f44;
        }
        h2 {
            color: #ff2c1f;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        input, textarea, select {
            width: 100%;
            padding: 0.8rem;
            margin: 0.6rem 0 1.2rem;
            border: none;
            border-radius: 5px;
            background: #2a2a2a;
            color: white;
        }
        textarea {
            min-height: 100px;
        }
        .btn {
            width: 100%;
            padding: 0.8rem;
            background: #ff2c1f;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn:hover {
            background: #0c4090;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <a href="admin.php" style="display:inline-block; margin-bottom: 1.5rem; color:white; background:#2a2a2a; padding: 0.6rem 1rem; border-radius: 6px; text-decoration:none; font-weight:bold;">⬅ Back to Admin Panel</a>
        <h2>Add New Movie</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title" required>
            <input type="text" name="genre" placeholder="Genre" required>
            <input type="text" name="duration" placeholder="Duration" required>
            <input type="number" name="price" step="0.01" placeholder="Price (e.g., 9.99)" required>
            <select name="section" required>
                <option value="featured">Featured Movies</option>
                <option value="coming_soon">Coming Soon</option>
            </select>
            <input type="file" name="image_file" accept="image/*" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <button class="btn" type="submit">Add Movie</button>
        </form>
    </div>
</body>
</html>
