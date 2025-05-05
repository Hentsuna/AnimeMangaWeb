<?php
include 'includes/header.php';
require 'db.php'; // Kết nối CSDL dùng $conn hoặc $pdo tùy bạn

// Nếu chưa đăng nhập thì chuyển về trang đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user_id'];
$errors = [];
$success = '';

$sql = "SELECT * FROM users
        WHERE users.id = '$user'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$user_id = $row['id'];
$username = $row['username'];
$email = $row['email'];
$password = $row['password'];
$avatar = $row['avatar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = trim($_POST['id']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($_FILES['avatarUpload']['name'])) {
        $avatar = $row['avatar'];
    } else {
        $file_name = $_FILES['avatarUpload']['name'];
        $file_size = $_FILES['avatarUpload']['size'];
        $file_tmp = $_FILES['avatarUpload']['tmp_name'];
        $file_ext = @strtolower(end(explode('.', $_FILES['avatarUpload']['name'])));
        $expensions = array("jpeg", "jpg", "png");

        if (in_array($file_ext, $expensions) === false) {
            $errors[] = "Don't accept image files with this extension, please choose JPEG or PNG.";
        }
        if ($file_size > 2097152) {
            $errors[] = 'File size should be 2MB';
        }
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, $_SERVER['DOCUMENT_ROOT'] . '/AnimeMangaWeb/assets/images/avatars/' . $file_name);
            $avatar = "./assets/images/avatars/" . $file_name;
        }
    }
    if (!empty($new_password) || !empty($confirm_password)) {
        if ($new_password !== $confirm_password) {
            $errors[] = 'Mật khẩu mới không khớp.';
        } elseif (strlen($new_password) < 8) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
        } else {
            // Băm mật khẩu
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password_sql = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_password_sql->bind_param("si", $hashed_password, $user_id);
            if (!$update_password_sql->execute()) {
                $errors[] = 'Lỗi khi cập nhật mật khẩu.';
            }
        }
    }

    // Kiểm tra cơ bản
    if (empty($username)) $errors[] = 'Tên người dùng không được để trống.';
    if (empty($email)) $errors[] = 'Email không được để trống.';

    if (empty($errors)) {
        // Cập nhật vào CSDL
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, avatar = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $avatar, $user_id);
        if ($stmt->execute()) {
            // Cập nhật lại session
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['avatar'] = $avatar;
            $success = 'Cập nhật thông tin thành công.';
        } else {
            $errors[] = 'Lỗi khi cập nhật dữ liệu.';
        }
    }
    header("Location: profile.php");
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

                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="hidden" name="id" value="<?php echo $user_id ?>">
                    </div>
                    <div class="mb-3 text-center">
                        <img src="<?php echo $avatar; ?>" width="100" height="100" class="rounded-circle mb-2" alt="Avatar">
                        <input type="file" name="avatarUpload">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $username ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" value="<?php echo $email ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" name="new_password" class="form-control" id="new_password" maxlength="8"
                                onfocus="showHint('newPasswordHint')" onblur="hideHint('newPasswordHint')">
                            <span class="input-group-text" onclick="togglePassword('new_password', 'eyeIconNew')" style="cursor: pointer;">
                                <i id="eyeIconNew" class="bi bi-eye"></i>
                            </span>
                        </div>
                        <small id="newPasswordHint" class="form-text text-muted" style="display: none;">Mật khẩu tối đa 8 ký tự.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nhập lại mật khẩu mới</label>
                        <input type="password" name="confirm_password" class="form-control" maxlength="8">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Lưu thay đổi</button>
                    <a href="logout.php" class="btn btn-outline-danger w-100 mt-2">Đăng xuất</a>
                </form>

                <script>
                    function togglePassword(inputId, iconId) {
                        const input = document.getElementById(inputId);
                        const icon = document.getElementById(iconId);

                        if (input.type === "password") {
                            input.type = "text";
                            icon.classList.remove("bi-eye");
                            icon.classList.add("bi-eye-slash");
                        } else {
                            input.type = "password";
                            icon.classList.remove("bi-eye-slash");
                            icon.classList.add("bi-eye");
                        }
                    }

                    function showHint(hintId) {
                        document.getElementById(hintId).style.display = "block";
                    }

                    function hideHint(hintId) {
                        document.getElementById(hintId).style.display = "none";
                    }
                </script>

            </div>
        </div>
    </div>
</body>

</html>

<?php include 'includes/footer.php'; ?>