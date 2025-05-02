<?php
session_start();
require 'db.php'; // Kết nối CSDL dùng $conn hoặc $pdo tùy bạn

// Nếu chưa đăng nhập thì chuyển về trang đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user_id'];
$errors = [];
$success = '';

$sql = "SELECT *  FROM users
        WHERE users.id = '$user'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$user_id = $row['id'];
$username = $row['username'];
$email = $row['email'];
$password = $row['password'];
$avatar = $row['avatar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    

    // Kiểm tra cơ bản
    if (empty($username)) $errors[] = 'Tên người dùng không được để trống.';
    if (empty($email)) $errors[] = 'Email không được để trống.';

    if (empty($errors)) {
        // Cập nhật vào CSDL
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, avatar = ? WHERE id = ?");
        if ($stmt->execute([$username, $email, $user['id']])) {
            // Cập nhật lại session
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $success = 'Cập nhật thông tin thành công.';
        } else {
            $errors[] = 'Lỗi khi cập nhật dữ liệu.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto shadow" style="max-width: 600px;">
            <div class="card-body">
                <h3 class="text-center mb-4">Trang cá nhân</h3>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <?php foreach ($errors as $err): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
                <?php endforeach; ?>

                <form method="post">
                    <div class="mb-3 text-center">
                        <img src="<?= htmlspecialchars($user['avatar'] ?? 'default-avatar.png') ?>" width="100" class="rounded-circle mb-2" alt="Avatar">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $username ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" value="<?php echo $email ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Lưu thay đổi</button>
                    <a href="logout.php" class="btn btn-outline-danger w-100 mt-2">Đăng xuất</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
