<?php
// Kết nối CSDL
include("../../db.php");

// Kiểm tra nếu form đã được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $title = $_POST['title'];
    $score = $_POST['score'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $episodes = $_POST['episodes'];
    $season_id = $_POST['season_id'];
    $director_id = $_POST['director_id'];

    // Xử lý upload ảnh
    $image_url = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = "../../assets/images/";
        $filename = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $filename;

        // Di chuyển ảnh vào thư mục assets/images/
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = "assets/images/" . $filename; // Lưu vào DB
        } else {
            die("❌ Không thể upload ảnh.");
        }
    }

    // Thêm vào bảng anime
    $query = "INSERT INTO anime (title, score, image, description, status, episodes, season_id, director_id)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sdsssiii", $title, $score, $image_url, $description, $status, $episodes, $season_id, $director_id);

    // if (mysqli_stmt_execute($stmt)) {
    //     echo "✅ Thêm anime thành công!";
    //     echo "<br><a href='../addanime.php'>Thêm anime khác</a>";
    //     echo " | <a href='../quanlyanime.php'>Quản lý anime</a>";
    // } else {
    //     echo "❌ Lỗi khi thêm anime: " . mysqli_error($conn);
    // }

    if (mysqli_stmt_execute($stmt)) {
        echo "✅ Thêm anime thành công.";
        echo '<script>
        setTimeout(function() {
            window.location.href = "../admin/?chucnang=quanlyanime#";
        }, 1000);
    </script>';
        exit;
    } else {
        echo "❌ Lỗi khi thêm anime: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "❌ Truy cập không hợp lệ.";
}
