<?php
session_start();
include 'includes/db_connect.php';

// Fetch cart count from DB for logged-in user
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
// Fetch movie data
$movies = $conn->query("SELECT * FROM movies LIMIT 10");
$comingSoon = $conn->query("SELECT * FROM movies ORDER BY id DESC LIMIT 10");
$sliders = $conn->query("SELECT * FROM sliders ORDER BY id ASC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="'IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive movies website</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <style>
    .search-section {
        padding: 2rem 1rem;
        background: #0b0b0b;
        text-align: center;
    }

    .search-container {
        position: relative;
        max-width: 320px;
        margin: auto;
    }

    #searchBox {
        width: 100%;
        padding: 0.8rem;
        border-radius: 6px;
        border: none;
        background: #2a2a2a;
        color: white;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
    }

    .suggestions {
        position: absolute;
        top: 105%;
        width: 100%;
        background: #1c1c1c;
        border-radius: 5px;
        z-index: 999;
        display: none;
        text-align: left;
    }

    .suggestions div {
        padding: 10px;
        color: white;
        cursor: pointer;
        border-bottom: 1px solid #333;
    }

    .suggestions div:hover {
        background-color: #ff2c1f;
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
        <li><a href="cart.php">🛒 View Cart (<?= $cartCount ?>)</a></li>
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

<!-- Home Slider -->
<section class="home swiper" id="home">
    <div class="swiper-wrapper">
        <?php while ($row = $sliders->fetch_assoc()): ?>
        <div class="swiper-slide container">
            <img src="images.png/<?= htmlspecialchars($row['image_filename']) ?>" alt="">
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


<!-- Search Section -->
<section class="search-section" id="search">
    <div class="search-container">
        <input type="text" id="searchBox" placeholder="Search for a movie..." autocomplete="off">
        <div id="homeSuggestions" class="suggestions"></div>
    </div>
</section>

<!-- Opening This Week -->
<section class="movies" id="movies">
    <h2 class="heading">Opening This Week</h2>
    <div class="movies-container">
        <?php while ($row = $movies->fetch_assoc()): ?>
            <a href="movie_details.php?id=<?= $row['id'] ?>" class="box">
                <div class="box-img">
                    <img src="images.png/<?= htmlspecialchars($row['image_filename']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                </div>
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <span><?= htmlspecialchars($row['duration']) ?> | <?= htmlspecialchars($row['genre']) ?></span>
            </a>
        <?php endwhile; ?>
    </div>
</section>

<!-- Coming Soon -->
<section class="coming" id="coming">
    <h2 class="heading">Coming Soon</h2>
    <div class="coming-container">
        <div class="swiper-wrapper">
            <?php while ($row = $comingSoon->fetch_assoc()): ?>
                <a href="movie_details.php?id=<?= $row['id'] ?>" class="swiper-slide box">
                    <div class="box-img">
                        <img src="images.png/<?= htmlspecialchars($row['image_filename']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                    </div>
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <span><?= htmlspecialchars($row['duration']) ?> | <?= htmlspecialchars($row['genre']) ?></span>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Footer -->
<section class="footer">
    <a href="#" class="logo"><i class="ri ri-film-line"></i> Movies</a>
    <div class="social">
        <a href="#"><i class="ri ri-facebook-fill"></i></a>
        <a href="#"><i class="ri ri-instagram-fill"></i></a>
        <a href="#"><i class="ri ri-tiktok-fill"></i></a>
        <a href="#"><i class="ri ri-twitter-fill"></i></a>
    </div>
</section>

<div class="copyright">
    <p>&#169; Example All Rights Reserved</p>
</div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="main.js" defer></script>

<!-- Live Search JS -->
<script>
const searchBox = document.getElementById('searchBox');
const suggestions = document.getElementById('homeSuggestions');

searchBox.addEventListener("input", () => {
    const query = searchBox.value.trim();
    if (query.length < 1) {
        suggestions.style.display = 'none';
        return;
    }

    fetch(`search_suggestions.php?query=${encodeURIComponent(query)}`)
    .then(res => res.json())
    .then(data => {
        suggestions.innerHTML = '';
        if (data.length > 0) {
            data.forEach(item => {
                const div = document.createElement('div');
                div.textContent = item.title;
                div.onclick = () => {
                    window.location.href = `movie_details.php?id=${item.id}`;
                };
                suggestions.appendChild(div);
            });
            suggestions.style.display = 'block';
        } else {
            suggestions.style.display = 'none';
        }
    });

});

document.addEventListener("click", e => {
    if (!searchBox.contains(e.target) && !suggestions.contains(e.target)) {
        suggestions.style.display = 'none';
    }
});
</script>

</body>
</html>
