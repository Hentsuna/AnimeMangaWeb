<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['user_id'])) {
    $manga_id = (int)$_POST['manga_id'];
    $user_id = $_SESSION['user_id'];
    $score_given = (float)$_POST['score_given'];

    // Kiểm tra xem user đã chấm chưa
    $check = $conn->prepare("SELECT * FROM user_manga WHERE user_id = ? AND manga_id = ?");
    $check->bind_param("ii", $user_id, $manga_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        // Cập nhật điểm
        $update = $conn->prepare("UPDATE user_manga SET score_given = ? WHERE user_id = ? AND manga_id = ?");
        $update->bind_param("dii", $score_given, $user_id, $manga_id);
        $update->execute();
    } else {
        // Chèn mới
        $insert = $conn->prepare("INSERT INTO user_manga (manga_id, user_id, score_given) VALUES (?, ?, ?)");
        $insert->bind_param("iid", $manga_id, $user_id, $score_given);
        $insert->execute();
    }

    // Tính lại điểm trung bình
    $avg_stmt = $conn->prepare("SELECT AVG(score_given) AS avg_score FROM user_manga WHERE manga_id = ?");
    $avg_stmt->bind_param("i", $manga_id);
    $avg_stmt->execute();
    $avg_result = $avg_stmt->get_result();
    $avg = $avg_result->fetch_assoc()['avg_score'];

    if ($avg !== null) {
        $avg = round($avg, 2);
        $update_score = $conn->prepare("UPDATE manga SET score = ? WHERE id = ?");
        $update_score->bind_param("di", $avg, $manga_id);
        $update_score->execute();
    }
}

header("Location: manga_detail.php?id=" . $manga_id);
exit;
