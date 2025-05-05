<?php
include 'includes/header.php';
include 'db.php'; // Bao gồm file kết nối cơ sở dữ liệu

// Kiểm tra nếu người dùng đã gửi form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Kiểm tra nếu mật khẩu và xác nhận mật khẩu khớp
    if ($password != $confirm_password) {
        $error = "Mật khẩu và xác nhận mật khẩu không khớp!";
    }

    // Kiểm tra nếu có lỗi không
    if (!isset($error)) {
        // Băm mật khẩu trước khi lưu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Thực hiện truy vấn để lưu người dùng mới vào cơ sở dữ liệu
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password); // 'sss' là kiểu dữ liệu cho 3 chuỗi
        $stmt->execute();

        // Đăng ký thành công, chuyển hướng người dùng đến trang đăng nhập
        header('Location: login.php');
        exit;
    }
}
?>

<main class="container my-5">
    <h2>Đăng Ký</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="username">Tên Người Dùng</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mật Khẩu</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" maxlength="8" required onfocus="showHint()" onblur="hideHint()">
                <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                    <i id="eyeIcon" class="bi bi-eye"></i>
                </span>
            </div>
            <small id="passwordHint" class="form-text text-muted" style="display: none;">Mật khẩu tối đa 8 ký tự.</small>
        </div>


        <div class="form-group">
            <label for="confirm_password">Xác Nhận Mật Khẩu</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" maxlength="8" required>
        </div>
        <button type="submit" class="btn btn-primary">Đăng Ký</button>
    </form>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const icon = document.getElementById("eyeIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }

        function showHint() {
            document.getElementById("passwordHint").style.display = "block";
        }

        function hideHint() {
            document.getElementById("passwordHint").style.display = "none";
        }
    </script>




    <p class="mt-3">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
</main>

<?php include 'includes/footer.php'; ?>