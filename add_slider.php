<?php
session_start();
include 'includes/db_connect.php';
include 'admin_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $headline = $_POST['headline'];
    $subheadline = $_POST['subheadline'];
    $filename = null;

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $filename = basename($_FILES['image_file']['name']);
        $target = "images.png/" . $filename;
        if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $target)) {
            die("Image upload failed.");
        }
    } else {
        die("Image is required.");
    }

    $stmt = $conn->prepare("INSERT INTO sliders (image_filename, headline, subheadline) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $filename, $headline, $subheadline);
    $stmt->execute();
    header("Location: admin_manage_sliders.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Slide</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #0b0b0b;
            color: white;
            font-family: 'Poppins', sans-serif;
            padding: 2rem;
        }
        form {
            max-width: 600px;
            margin: auto;
            background: #1c1c1c;
            padding: 2rem;
            border-radius: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            background: #2a2a2a;
            color: white;
            border: none;
        }
        .btn {
            padding: 0.8rem 1.5rem;
            background: #ff2c1f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn:hover {
            background: #0c4090;
        }
    </style>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <h2 style="text-align:center; color:#ff2c1f;">Add New Slide</h2>
        <input type="text" name="headline" placeholder="Headline" required>
        <input type="text" name="subheadline" placeholder="Subheadline" required>
        <input type="file" name="image_file" accept="image/*" required>
        <button class="btn" type="submit">Add Slide</button>
    </form>
</body>
</html>
