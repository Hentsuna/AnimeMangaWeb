<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include '../db.php'; ?>
    <title>Trang Admin</title>
    <link rel="icon" href="<?php echo $icon; ?>">
    <link rel="stylesheet" href="include/fontawesome/css/all.css">
    <link rel="stylesheet" href="include/style/bootstrap.css">
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['loginadmin'])) {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = $_POST['password'];

            $getdata = mysqli_query($conn, "SELECT password, role FROM users WHERE username = '$username'");
            $user = mysqli_fetch_assoc($getdata);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['role'] === 'admin') {
                    $_SESSION['loginadmin'] = true;
                    $_SESSION['user'] = $username;
                    $_SESSION['user_role'] = 'admin';
                } else {
                    echo '<script>alert("Bạn không có quyền truy cập trang quản trị.")</script>';
                }
            } else {
                echo '<script>alert("Tài khoản hoặc mật khẩu không đúng.")</script>';
            }
        }
    }

    if (isset($_POST['act']) && $_POST['act'] === 'logout') {
        session_destroy();
        header("Location: index.php");
        exit();
    }

    if (isset($_SESSION['loginadmin']) && $_SESSION['user_role'] === 'admin') {
        include 'trangquanly.php';
    } else {
        include 'login.php';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['act']) && $_POST['act'] === 'true') {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }
    ?>
</body>

</html>