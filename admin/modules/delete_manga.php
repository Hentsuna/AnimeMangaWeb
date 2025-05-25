<?php
include("../db.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ Thiếu ID manga.";
    exit;
}

$id = intval($_GET['id']);

// Kiểm tra manga có tồn tại không
$result = mysqli_query($conn, "SELECT * FROM manga WHERE id = $id");
$manga = mysqli_fetch_assoc($result);

if (!$manga) {
    echo "❌ Manga không tồn tại.";
    exit;
}

// Xoá ảnh (nếu có)
$image_path = "../" . $manga['image'];
if (file_exists($image_path)) {
    unlink($image_path);
}

// Xoá dữ liệu liên quan (nếu có bảng phụ như manga_favorites)
mysqli_query($conn, "DELETE FROM manga_favorites WHERE manga_id = $id");

// Xoá manga khỏi bảng chính
if (mysqli_query($conn, "DELETE FROM manga WHERE id = $id")) {
    echo "✅ Manga đã được xóa thành công.";
    echo '<script>
        setTimeout(function() {
            window.location.href = "../admin/?chucnang=quanlymanga#";
        }, 1000);
    </script>';
    exit;
} else {
    echo "❌ Lỗi khi xóa manga: " . mysqli_error($conn);
}
?>
