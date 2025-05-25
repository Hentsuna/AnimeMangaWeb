<?php
include("../db.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ Thiếu ID thể loại.";
    exit;
}

$id = intval($_GET['id']);

// Kiểm tra thể loại có tồn tại
$result = mysqli_query($conn, "SELECT * FROM genre WHERE id = $id");
$genre = mysqli_fetch_assoc($result);

if (!$genre) {
    echo "❌ Thể loại không tồn tại.";
    exit;
}

// Xóa bản ghi thể loại
if (mysqli_query($conn, "DELETE FROM genre WHERE id = $id")) {
    echo "✅ Thể loại đã được xóa thành công.";
    echo '<script>
        setTimeout(function() {
            window.location.href = "../admin/index.php?chucnang=quanlytheloai";
        }, 1000);
    </script>';
    exit;
} else {
    echo "❌ Lỗi khi xóa thể loại: " . mysqli_error($conn);
}
?>
