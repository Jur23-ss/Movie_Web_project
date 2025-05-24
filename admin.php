<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($is_admin);
$stmt->fetch();
$stmt->close();

if (!$is_admin) {
    die("Access denied. Admins only.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Poppins', sans-serif;
        }

        .video-bg {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: -1;
            filter: brightness(0.4);
            transition: opacity 1s ease;
        }

        .fade-out {
            opacity: 0;
        }

        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 100px;
            z-index: 10;
        }

        .logo {
            font-size: 1.3rem;
            color: white;
            font-weight: bold;
        }

        .navbar {
            display: flex;
            gap: 2rem;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            position: relative;
        }

        .navbar a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background: #ff2c1f;
            transition: 0.3s;
        }

        .navbar a:hover::after {
            width: 100%;
        }

        #soundToggle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 15;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid #ff2c1f;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 1rem;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }

        #soundToggle:hover {
            background: #ff2c1f;
        }
    </style>
</head>
<body>

    <!-- 🎬 Rotating Background Video -->
    <video autoplay muted id="bgVideo" class="video-bg">
        <source src="media/clip1.mp4" type="video/mp4">
    </video>

    <!-- 🔊 Mute Button -->
    <button id="soundToggle" onclick="toggleSound()">🔇 Unmute</button>

    <!-- 🧭 Admin Navbar -->
    <header class="header">
        <div class="logo">🎬 Admin Panel</div>
        <nav class="navbar">
            <a class="btn" href="add_movie.php">Add Movie</a>
            <a class="btn" href="view_orders.php">View Orders</a>
            <a class="btn" href="manage_movies.php">Manage Movies</a>
            <a class="btn" href="admin_manage_sliders.php">Manage Sliders</> 
            <a class="btn" href="manage_users.php">Manage Users</a>
            <a class="btn" href="index.php">Homepage</a>
            <a class="btn" href="logout.php">Logout</a>
        </nav>
    </header>

    <script>
        const videos = [
            "media/clip1.mp4",
            "media/clip2.mp4",
            "media/clip3.mp4",
            "media/clip4.mp4"
        ];

        let current = 0;
        const video = document.getElementById("bgVideo");
        const source = video.querySelector("source");

        video.addEventListener("ended", () => {
            current = (current + 1) % videos.length;
            video.classList.add("fade-out");

            setTimeout(() => {
                source.src = videos[current];
                video.load();
                video.play().catch(err => console.error("Playback error:", err));
                video.classList.remove("fade-out");
            }, 800);
        });

        function toggleSound() {
            const button = document.getElementById('soundToggle');
            if (video.muted) {
                video.muted = false;
                button.innerText = "🔊 Mute";
            } else {
                video.muted = true;
                button.innerText = "🔇 Unmute";
            }
        }
    </script>

</body>
</html>
