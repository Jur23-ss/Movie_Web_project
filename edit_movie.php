<?php
session_start();
include 'includes/db_connect.php';
include 'admin_check.php';

if (!isset($_GET['id'])) {
    header("Location: manage_movies.php");
    exit;
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $image = $_POST['image_filename'];
    $desc = $_POST['description'];
    $price = floatval($_POST['price']);
    $section = $_POST['section'];

    $stmt = $conn->prepare("UPDATE movies SET title=?, genre=?, duration=?, image_filename=?, description=?, price=?, section=? WHERE id=?");
    $stmt->bind_param("sssssdsi", $title, $genre, $duration, $image, $desc, $price, $section, $id);
    $stmt->execute();
    header("Location: manage_movies.php");
    exit;
}

// Load movie
$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Movie</title>
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
        <h2>Edit Movie</h2>
        <form method="POST">
            <input type="text" name="title" value="<?= htmlspecialchars($movie['title']) ?>" required placeholder="Title">
            <input type="text" name="genre" value="<?= htmlspecialchars($movie['genre']) ?>" required placeholder="Genre">
            <input type="text" name="duration" value="<?= htmlspecialchars($movie['duration']) ?>" required placeholder="Duration">
            <input type="text" name="image_filename" value="<?= htmlspecialchars($movie['image_filename']) ?>" required placeholder="image1.jpg">
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($movie['price']) ?>" required placeholder="Price">
            <select name="section" required>
                <option value="">Select Section</option>
                <option value="featured" <?= $movie['section'] === 'featured' ? 'selected' : '' ?>>Featured</option>
                <option value="coming_soon" <?= $movie['section'] === 'coming_soon' ? 'selected' : '' ?>>Coming Soon</option>
            </select>
            <textarea name="description" required placeholder="Description"><?= htmlspecialchars($movie['description']) ?></textarea>
            <button class="btn" type="submit">Update Movie</button>
        </form>
    </div>
</body>
</html>
