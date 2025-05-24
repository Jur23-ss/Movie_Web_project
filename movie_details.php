<?php
include 'includes/db_connect.php';

if (!isset($_GET['id'])) {
    echo "No movie selected.";
    exit;
}

$movie_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Movie not found.";
    exit;
}

$movie = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($movie['title']) ?> - Movie Details</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #0b0b0b;
            color: white;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 2rem;
        }

        .movie-details {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            max-width: 1000px;
            margin: auto;
            background: #1c1c1c;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 10px #ff2c1f44;
        }

        .movie-details img {
            width: 100%;
            max-width: 300px;
            border-radius: 8px;
            object-fit: cover;
        }

        .info {
            flex: 1;
        }

        .info h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #ff2c1f;
        }

        .meta {
            font-weight: 500;
            margin-bottom: 1rem;
            color: #ccc;
        }

        .description {
            line-height: 1.6;
        }

        .btn-cart {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.8rem 1.5rem;
            background: #ff2c1f;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-cart:hover {
            background: #0c4090;
        }
    </style>
</head>
<body>

<div class="movie-details">
    <img src="images.png/<?= htmlspecialchars($movie['image_filename']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
    
    <div class="info">
        <h1><?= htmlspecialchars($movie['title']) ?></h1>
        <div class="meta">
            Genre: <?= htmlspecialchars($movie['genre']) ?> <br>
            Duration: <?= htmlspecialchars($movie['duration']) ?>
        </div>
        <div class="description">
            <?= nl2br(htmlspecialchars($movie['description'])) ?>
        </div>

        <a class="btn-cart" href="add_to_cart.php?id=<?= $movie['id'] ?>">Add to Cart</a>
        <a class="btn-cart" href="index.php" style="margin-left: 1rem; background: #2a2a2a;">⬅ Back to Home</a>

    </div>
</div>

</body>
</html>
