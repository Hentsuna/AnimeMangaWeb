<?php
$servername = "localhost";
$username = "root"; // Thay bằng tên người dùng MySQL của bạn
$password = ""; // Thay bằng mật khẩu MySQL của bạn
$dbname = "animemangaweb";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
