<?php
session_start();
include 'includes/db_connect.php';
include 'admin_check.php';

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$searchSql = '';
$sortSql = 'ORDER BY id DESC';

if ($search) {
    $searchSql = "WHERE title LIKE ? OR genre LIKE ?";
}
if ($sort === 'title') {
    $sortSql = 'ORDER BY title ASC';
} elseif ($sort === 'genre') {
    $sortSql = 'ORDER BY genre ASC';
}

// Build query
$sql = "SELECT * FROM movies $searchSql $sortSql";
$stmt = $conn->prepare($sql);

if ($search) {
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
}

$stmt->execute();
$movies = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Movies</title>
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
        form.search-sort {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
        }
        input[type="text"], select {
            padding: 0.6rem;
            border-radius: 5px;
            border: none;
            background: #2a2a2a;
            color: white;
        }
        button {
            background: #ff2c1f;
            color: white;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #0c4090;
        }
        .suggestions {
            background: #2a2a2a;
            border-radius: 5px;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            top: 55px;
            width: 250px;
            z-index: 100;
            display: none;
        }
        .suggestions div {
            padding: 0.5rem;
            cursor: pointer;
            color: white;
        }
        .suggestions div:hover {
            background: #ff2c1f;
        }
        table {
            width: 100%;
            background: #1c1c1c;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            border-bottom: 1px solid #333;
            text-align: left;
        }
        th {
            background: #2a2a2a;
        }
        a {
            color: #00ccff;
            text-decoration: none;
        }
        a.delete {
            color: #ff4d4d;
        }
    </style>
</head>
<body>
    <h2>Manage Movies</h2>

    <form method="GET" class="search-sort">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by title or genre" autocomplete="off" id="searchInput">
        <div id="suggestions" class="suggestions"></div>
        <select name="sort">
            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
            <option value="title" <?= $sort === 'title' ? 'selected' : '' ?>>Title A-Z</option>
            <option value="genre" <?= $sort === 'genre' ? 'selected' : '' ?>>Genre</option>
        </select>
        <button type="submit">Apply</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Genre</th>
            <th>Duration</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $movies->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['genre']) ?></td>
                <td><?= htmlspecialchars($row['duration']) ?></td>
                <td>
                    <a href="edit_movie.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a class="delete" href="delete_movie.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
    const searchInput = document.getElementById("searchInput");
    const suggestionsBox = document.getElementById("suggestions");

    searchInput.addEventListener("input", () => {
        const query = searchInput.value.trim();
        if (query.length < 1) {
            suggestionsBox.style.display = 'none';
            return;
        }

        fetch(`search_suggestions.php?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                suggestionsBox.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.textContent = item;
                        div.onclick = () => {
                            searchInput.value = item;
                            suggestionsBox.style.display = 'none';
                        };
                        suggestionsBox.appendChild(div);
                    });
                    suggestionsBox.style.display = 'block';
                } else {
                    suggestionsBox.style.display = 'none';
                }
            });
    });

    document.addEventListener("click", e => {
        if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });
    </script>
</body>
</html>
