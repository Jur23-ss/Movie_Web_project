<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$movies = [];

$stmt = $conn->prepare("
    SELECT m.*
    FROM cart c
    JOIN movies m ON c.movie_id = m.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $movies[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #0b0b0b;
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }
        .cart-container {
            max-width: 800px;
            margin: 120px auto;
            background: #1c1c1c;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px #ff2c1f44;
        }
        .cart-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #ff2c1f;
        }
        .movie-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            background: #2a2a2a;
            border-radius: 8px;
            padding: 1rem;
            align-items: center;
        }
        .movie-item img {
            width: 120px;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
        }
        .movie-info {
            flex-grow: 1;
        }
        .movie-info h3 {
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .movie-info p {
            color: #ccc;
            font-size: 0.95rem;
        }
        .remove-btn {
            display: inline-block;
            margin-top: 0.5rem;
            color: #ff4d4d;
            font-weight: bold;
            text-decoration: none;
        }
        .remove-btn:hover {
            text-decoration: underline;
        }
        .empty {
            text-align: center;
            color: #aaa;
            margin-top: 2rem;
            font-size: 1.2rem;
        }
        .btn {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.7rem 1.5rem;
            background: #ff2c1f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
        .btn:hover {
            background: #0c4090;
        }
        .actions {
            text-align: center;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
<div class="cart-container">
    <h2>Your Cart</h2>

    <?php if (count($movies) > 0): ?>
        <?php foreach ($movies as $movie): ?>
            <div class="movie-item">
                <img src="images.png/<?= htmlspecialchars($movie['image_filename']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                <div class="movie-info">
                    <h3><?= htmlspecialchars($movie['title']) ?></h3>
                    <p><?= htmlspecialchars($movie['duration']) ?> | <?= htmlspecialchars($movie['genre']) ?></p>
                    <a class="remove-btn" href="remove_from_cart.php?id=<?= $movie['id'] ?>">Remove</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="actions">
            <a href="index.php" class="btn">⬅ Continue Shopping</a>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        </div>
    <?php else: ?>
        <p class="empty">Your cart is empty.</p>
        <div class="actions">
            <a href="index.php" class="btn">⬅ Back to Home</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
