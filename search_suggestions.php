<?php
include 'db.php';

$term = $_GET['term'] ?? '';
if (!$term) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
    SELECT id, title, image, 'anime' AS type FROM anime WHERE title LIKE CONCAT('%', ?, '%')
    UNION
    SELECT id, title, image, 'manga' AS type FROM manga WHERE title LIKE CONCAT('%', ?, '%')
    LIMIT 10
");
$stmt->bind_param("ss", $term, $term);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row;
}

header('Content-Type: application/json');
echo json_encode($suggestions);
?>
