<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$content = trim($_POST['content'] ?? '');
$type = $_POST['type'] ?? 'anime'; // 'anime' hoặc 'manga'

// Lấy ID đúng theo loại
if ($type === 'manga') {
    $item_id = isset($_POST['manga_id']) ? (int)$_POST['manga_id'] : 0;
} else {
    $item_id = isset($_POST['anime_id']) ? (int)$_POST['anime_id'] : 0;
}

$parent_id = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;

if ($content && $item_id > 0) {
    if ($type === 'manga') {
        $stmt = $conn->prepare("INSERT INTO manga_comments (user_id, manga_id, parent_id, content, created_at) VALUES (?, ?, ?, ?, NOW())");
    } else {
        $stmt = $conn->prepare("INSERT INTO comments (user_id, anime_id, parent_id, content, created_at) VALUES (?, ?, ?, ?, NOW())");
    }
    $stmt->bind_param("iiis", $user_id, $item_id, $parent_id, $content);
    $stmt->execute();
}

// Redirect về trang chi tiết phù hợp
if ($type === 'manga') {
    header("Location: manga_detail.php?id=$item_id");
} else {
    header("Location: anime_detail.php?id=$item_id");
}
exit;
