<?php
include("../db.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ Thiếu ID đạo diễn.";
    exit;
}

$id = intval($_GET['id']);

// Kiểm tra đạo diễn có tồn tại
$result = mysqli_query($conn, "SELECT * FROM directors WHERE id = $id");
$daodien = mysqli_fetch_assoc($result);

if (!$daodien) {
    echo "❌ Đạo diễn không tồn tại.";
    exit;
}

// Xóa bản ghi đạo diễn
if (mysqli_query($conn, "DELETE FROM directors WHERE id = $id")) {
    echo "✅ Đạo diễn đã được xóa thành công.";
    echo '<script>
        setTimeout(function() {
            window.location.href = "../admin/index.php?chucnang=quanlydaodien";
        }, 1000);
    </script>';
    exit;
} else {
    echo "❌ Lỗi khi xóa đạo diễn: " . mysqli_error($conn);
}
?>
