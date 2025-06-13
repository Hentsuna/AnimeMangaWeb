<?php
include 'includes/header.php';
include 'db.php'; // Bao gồm file kết nối cơ sở dữ liệu

// Kiểm tra nếu người dùng đã gửi form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Thực hiện truy vấn để tìm người dùng theo email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // 's' cho kiểu dữ liệu chuỗi
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra xem có người dùng nào không
    if ($result->num_rows > 0) {
        // Lấy dữ liệu người dùng
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Mật khẩu đúng, đăng nhập thành công
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['avatar'] = $user['avatar'];
            header('Location: index.php'); // Chuyển hướng đến trang chính
            exit;
        } else {
            $error = "Mật khẩu không đúng!";
        }
    } else {
        $error = "Email không tồn tại!";
    }
}
?>

<main class="container my-5">
    <h2>Đăng Nhập</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mật Khẩu</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" maxlength="8"
                    required onfocus="showHint('passwordHint')" onblur="hideHint('passwordHint')">
                <span class="input-group-text" onclick="togglePassword('password', 'eyeIconPassword')" style="cursor: pointer;">
                    <i id="eyeIconPassword" class="bi bi-eye"></i>
                </span>
            </div>
            <small id="passwordHint" class="form-text text-muted" style="display: none;">Mật khẩu tối đa 8 ký tự.</small>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                <a href="quen-mat-khau.php" class="text-decoration-none">Quên mật khẩu?</a>
                
            </div>
        </div>
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


    <p class="mt-3">Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
</main>

<?php include 'includes/footer.php'; ?>