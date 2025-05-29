<?php
include 'includes/db_connect.php';

$query = $_GET['query'] ?? '';
if (!$query) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT id, title FROM movies WHERE title LIKE ? LIMIT 5");
$like = "%$query%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = [
        'id' => $row['id'],
        'title' => $row['title']
    ];
}

header('Content-Type: application/json');
echo json_encode($suggestions);
?>
