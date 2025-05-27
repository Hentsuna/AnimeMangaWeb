<?php
include("../db.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ Thiếu ID anime.";
    exit;
}

$id = intval($_GET['id']);

// Kiểm tra anime có tồn tại
$result = mysqli_query($conn, "SELECT * FROM anime WHERE id = $id");
$anime = mysqli_fetch_assoc($result);

if (!$anime) {
    echo "❌ Anime không tồn tại.";
    exit;
}

// Xoá ảnh (nếu có)
$image_path = "../" . $anime['image'];
if (file_exists($image_path)) {
    unlink($image_path);
}

// Xoá dữ liệu liên quan trong bảng con
mysqli_query($conn, "DELETE FROM anime_favorites WHERE anime_id = $id");
mysqli_query($conn, "DELETE FROM comments WHERE anime_id = $id");


// Xoá bản ghi anime
if (mysqli_query($conn, "DELETE FROM anime WHERE id = $id")) {
    echo "✅ Anime đã được xóa thành công.";
    echo '<script>
        setTimeout(function() {
            window.location.href = "../admin/?chucnang=quanlyanime#";
        }, 1000);
    </script>';
    exit;
} else {
    echo "❌ Lỗi khi xóa anime: " . mysqli_error($conn);
}
?>
