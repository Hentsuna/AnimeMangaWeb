<?php
session_start();
session_unset();  // Xóa tất cả các biến session
session_destroy();  // Hủy phiên làm việc

header('Location: index.php');  // Chuyển hướng về trang chủ sau khi đăng xuất
exit;
