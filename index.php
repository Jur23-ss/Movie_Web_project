<?php
session_start();
include 'includes/db_connect.php';

$cartCount = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($cartCount);
    $stmt->fetch();
    $stmt->close();
}

$sliders = $conn->query("SELECT * FROM sliders ORDER BY id ASC");
$featured = $conn->query("SELECT * FROM movies WHERE section = 'featured' ORDER BY id DESC LIMIT 10");
$comingSoon = $conn->query("SELECT * FROM movies WHERE section = 'coming_soon' ORDER BY id DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Responsive movies website</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <style>
        body {
            background: #0b0b0b;
            color: white;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }
        header.header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #0b0b0b;
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        header.header .logo {
            font-size: 1.4rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        header.header .navbar {
            list-style: none;
            display: flex;
            gap: 2rem;
            padding: 0;
            margin: 0;
        }
        header.header .navbar li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }
        header.header .btn {
            background: #ff2c1f;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
        }
        .search-bar-container {
            margin: 2rem auto;
            text-align: center;
            position: relative;
        }
        .search-bar-container input {
            padding: 0.8rem 1rem;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
        }
        #suggestions {
            position: absolute;
            background: #1c1c1c;
            width: 80%;
            max-width: 500px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
            border-radius: 0 0 10px 10px;
        }
        #suggestions div {
            padding: 0.8rem 1rem;
            cursor: pointer;
        }
        #suggestions div:hover {
            background: #333;
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .section {
            padding: 2rem;
        }
        .heading {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid red;
            padding-bottom: 0.5rem;
        }
        .movie-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        .movie-card {
            width: 170px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }
        .movie-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(255, 44, 31, 0.3);
        }
        .movie-card img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 10px;
        }
        .movie-card h4 {
            font-size: 1rem;
            margin: 0.5rem 0 0.2rem;
            color: white;
        }
        .movie-card p {
            font-size: 0.85rem;
            color: #ccc;
            margin-bottom: 0.5rem;
        }
        .movie-card .price {
            color: #ffb84d;
            font-weight: bold;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<header class="header">
    <a href="#" class="logo"><i class="ri-film-line"></i> Movies</a>
    <ul class="navbar">
        <li><a href="#home" class="home-active">Home</a></li>
        <li><a href="#movies">Movies</a></li>
        <li><a href="#coming">Coming</a></li>
        <li><a href="cart.php">View Cart (<?= $cartCount ?>)</a></li>
        <?php if (isset($_SESSION['user_id'])):
            $stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $stmt->bind_result($is_admin);
            $stmt->fetch();
            $stmt->close();
            if ($is_admin): ?>
                <li><a href="admin.php">Admin Panel</a></li>
            <?php endif; ?>
        <?php endif; ?>
    </ul>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php" class="btn">Logout</a>
    <?php else: ?>
        <a href="login.php" class="btn">Sign in</a>
    <?php endif; ?>
</header>

<div class="search-bar-container">
    <input type="text" id="searchInput" placeholder="Search for movies...">
    <div id="suggestions"></div>
</div>

<section class="home swiper" id="home">
    <div class="swiper-wrapper">
        <?php while ($row = $sliders->fetch_assoc()): ?>
        <div class="swiper-slide container">
            <img src="images.png/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['headline']) ?>">
            <div class="home-text">
                <span><?= htmlspecialchars($row['headline']) ?></span>
                <h1><?= nl2br(htmlspecialchars($row['subheadline'])) ?></h1>
                <a href="#movies" class="btn">Browse Movies</a>
                <a href="#" class="play"><i class="ri ri-play-fill"></i></a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <div class="swiper-pagination"></div>
</section>

<section class="section" id="movies">
    <h2 class="heading">Featured Movies</h2>
    <div class="movie-grid">
        <?php while ($row = $featured->fetch_assoc()): ?>
        <div class="movie-card">
            <a href="movie_details.php?id=<?= $row['id'] ?>">
                <img src="images.png/<?= htmlspecialchars($row['image_filename']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                <h4><?= htmlspecialchars($row['title']) ?></h4>
                <p><?= htmlspecialchars($row['duration']) ?> | <?= htmlspecialchars($row['genre']) ?></p>
                <div class="price">$<?= number_format($row['price'], 2) ?></div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<section class="section" id="coming">
    <h2 class="heading">Coming Soon</h2>
    <div class="movie-grid">
        <?php while ($row = $comingSoon->fetch_assoc()): ?>
        <div class="movie-card">
            <a href="movie_details.php?id=<?= $row['id'] ?>">
                <img src="images.png/<?= htmlspecialchars($row['image_filename']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                <h4><?= htmlspecialchars($row['title']) ?></h4>
                <p><?= htmlspecialchars($row['duration']) ?> | <?= htmlspecialchars($row['genre']) ?></p>
                <div class="price">$<?= number_format($row['price'], 2) ?></div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="main.js" defer></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    new Swiper(".swiper", {
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });

    const input = document.getElementById("searchInput");
    const suggestions = document.getElementById("suggestions");

    input.addEventListener("keyup", function () {
        const query = input.value.trim();
        if (query.length === 0) {
            suggestions.innerHTML = "";
            return;
        }

        fetch("search_suggestions.php?query=" + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                suggestions.innerHTML = "";
                data.forEach(movie => {
                    const div = document.createElement("div");
                    div.textContent = movie.title;
                    div.onclick = () => window.location.href = "movie_details.php?id=" + movie.id;
                    suggestions.appendChild(div);
                });
            });
    });
});
</script>

</body>
</html>
