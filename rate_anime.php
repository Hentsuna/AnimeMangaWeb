<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['user_id'])) {
    $anime_id = $_POST['anime_id'];
    $user_id = $_SESSION['user_id'];
    $score_given = $_POST['score_given'];

    // Kiểm tra xem user đã chấm chưa
    $check = $conn->prepare("SELECT * FROM user_anime WHERE user_id = ? AND anime_id = ?");
    $check->bind_param("ii", $user_id, $anime_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        // Cập nhật điểm
        $update = $conn->prepare("UPDATE user_anime SET score_given = ? WHERE user_id = ? AND anime_id = ?");
        $update->bind_param("dii", $score_given, $user_id, $anime_id);
        $update->execute();
    } else {
        // Chèn mới
        $insert = $conn->prepare("INSERT INTO user_anime (anime_id, user_id, score_given) VALUES (?, ?, ?)");
        $insert->bind_param("iid", $anime_id, $user_id, $score_given);
        $insert->execute();
    }

    // Tính lại điểm trung bình
    $avg_stmt = $conn->prepare("SELECT AVG(score_given) AS avg_score FROM user_anime WHERE anime_id = ?");
    $avg_stmt->bind_param("i", $anime_id);
    $avg_stmt->execute();
    $avg_result = $avg_stmt->get_result();
    $avg = $avg_result->fetch_assoc()['avg_score'];

    if ($avg !== null) {
        $avg = round($avg, 2);
        $update_score = $conn->prepare("UPDATE anime SET score = ? WHERE id = ?");
        $update_score->bind_param("di", $avg, $anime_id);
        $update_score->execute();
    }
}

header("Location: anime_detail.php?id=" . $anime_id);
exit;
