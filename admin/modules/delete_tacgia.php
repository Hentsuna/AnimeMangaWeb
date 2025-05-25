<?php
include("../db.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ Thiếu ID tác giả.";
    exit;
}

$id = intval($_GET['id']);

// Kiểm tra tác giả có tồn tại
$result = mysqli_query($conn, "SELECT * FROM authors WHERE id = $id");
$author = mysqli_fetch_assoc($result);

if (!$author) {
    echo "❌ Tác giả không tồn tại.";
    exit;
}

// Xóa bản ghi tác giả
if (mysqli_query($conn, "DELETE FROM authors WHERE id = $id")) {
    echo "✅ Tác giả đã được xóa thành công.";
    echo '<script>
        setTimeout(function() {
            window.location.href = "../admin/index.php?chucnang=quanlytacgia";
        }, 1000);
    </script>';
    exit;
} else {
    echo "❌ Lỗi khi xóa tác giả: " . mysqli_error($conn);
}
?>
