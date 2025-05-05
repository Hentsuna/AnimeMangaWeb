<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || empty($_POST['content']) || empty($_POST['anime_id'])) {
    header("Location: anime_detail.php?id=" . $_POST['anime_id']);
    exit;
}

$user_id = $_SESSION['user_id'];
$anime_id = (int)$_POST['anime_id'];
$parent_id = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;
$content = trim($_POST['content']);

$stmt = $conn->prepare("INSERT INTO comments (anime_id, user_id, parent_id, content) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $anime_id, $user_id, $parent_id, $content);
$stmt->execute();

header("Location: anime_detail.php?id=" . $anime_id);
exit;
