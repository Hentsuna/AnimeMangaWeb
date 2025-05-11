<?php
include("../../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $score = $_POST['score'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $episodes = $_POST['episodes'];
    $season_id = $_POST['season_id'];
    $director_id = $_POST['director_id'];

    // Lấy ảnh cũ
    $old_image_query = mysqli_query($conn, "SELECT image FROM anime WHERE id = $id");
    $old_image_row = mysqli_fetch_assoc($old_image_query);
    $image_url = $old_image_row['image'];

    // Nếu có ảnh mới thì xử lý upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = "../../assets/images/";
        $filename = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = "assets/images/" . $filename;

            // Xoá ảnh cũ nếu cần (nếu không trùng ảnh mặc định chẳng hạn)
            if (file_exists("../../" . $old_image_row['image'])) {
                unlink("../../" . $old_image_row['image']);
            }
        } else {
            echo "❌ Không thể upload ảnh.";
            exit;
        }
    }

    // Cập nhật vào bảng anime
    $query = "UPDATE anime 
              SET title = ?, score = ?, image = ?, description = ?, status = ?, episodes = ?, season_id = ?, director_id = ?
              WHERE id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sdsssiiii", $title, $score, $image_url, $description, $status, $episodes, $season_id, $director_id, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "✅ Cập nhật anime thành công!";
        echo "<br><a href='../sua_anime.php?id=$id'>Quay lại</a>";
        echo " | <a href='../index.php?chucnang=quanlyanime'>Quản lý anime</a>";
    } else {
        echo "❌ Lỗi khi cập nhật anime: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "❌ Truy cập không hợp lệ.";
}
?>
