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
    $chapters = $_POST['chapters'];
    $volumes = $_POST['volumes'];
    $author_id = $_POST['author_id'];

    // Xử lý upload ảnh
    $image_url = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = "../../assets/images/";
        $filename = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $filename;

        // Di chuyển ảnh vào thư mục assets/images/
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = "assets/images/" . $filename; // Đường dẫn lưu trong DB
        } else {
            die("❌ Không thể upload ảnh.");
        }
    }

    // Thêm vào bảng manga
    $query = "INSERT INTO manga (title, score, image, description, status, chapters, volumes, created_at, author_id)
              VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sdsssiii", $title, $score, $image_url, $description, $status, $chapters, $volumes, $author_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "✅ Thêm manga thành công!";
        echo "<br><a href='../add_manga.php'>Thêm manga khác</a>";
        echo " | <a href='../quanlymanga.php'>Quản lý manga</a>";
    } else {
        echo "❌ Lỗi khi thêm manga: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "❌ Truy cập không hợp lệ.";
}
?>
