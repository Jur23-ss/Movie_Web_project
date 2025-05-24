<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$movies = [];

// Load cart items
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

// Handle order submission
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && count($movies) > 0) {
    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id) VALUES (?)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, movie_id) VALUES (?, ?)");
    foreach ($movies as $movie) {
        $stmt->bind_param("ii", $order_id, $movie['id']);
        $stmt->execute();
    }

    // Clear cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $success = true;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #0b0b0b;
            font-family: 'Poppins', sans-serif;
            color: white;
        }
        .checkout-container {
            max-width: 800px;
            margin: 120px auto;
            padding: 2rem;
            background: #1c1c1c;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #ff2c1f;
            margin-bottom: 2rem;
        }
        .movie-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            background: #2a2a2a;
            padding: 1rem;
            border-radius: 6px;
        }
        .movie-item img {
            width: 100px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }
        .movie-info {
            flex-grow: 1;
        }
        .btn {
            padding: 0.7rem 1.5rem;
            background: #ff2c1f;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
        }
        .btn:hover {
            background: #0c4090;
        }
        .center {
            text-align: center;
            margin-top: 2rem;
        }
        .success {
            color: #90ee90;
            text-align: center;
            font-size: 1.2rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <?php if ($success): ?>
            <div class="success">✅ Your order has been placed successfully!</div>
            <div class="center"><a class="btn" href="index.php">Back to Home</a></div>
        <?php elseif (count($movies) > 0): ?>
            <h2>Confirm Your Order</h2>
            <?php foreach ($movies as $movie): ?>
                <div class="movie-item">
                    <img src="images.png/<?= htmlspecialchars($movie['image_filename']) ?>" alt="">
                    <div class="movie-info">
                        <h3><?= htmlspecialchars($movie['title']) ?></h3>
                        <p><?= htmlspecialchars($movie['duration']) ?> | <?= htmlspecialchars($movie['genre']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <form method="POST" class="center">
                <button class="btn" type="submit">Confirm Order</button>
            </form>
        <?php else: ?>
            <div class="success">Your cart is empty.</div>
            <div class="center"><a class="btn" href="index.php">Back to Home</a></div>
        <?php endif; ?>
    </div>
</body>
</html>
