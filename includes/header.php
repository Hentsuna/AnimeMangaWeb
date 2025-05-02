<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyAnimeList Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">MyAnimeList</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="anime.php">Anime</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manga.php">Manga</a>
                    </li>
                </ul>
            </div>
            <form class="d-flex mx-auto w-50">
                <input class="form-control me-2" type="search" placeholder="Search anime..." aria-label="Search">
            </form>
            <div class="d-flex">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Nếu người dùng đã đăng nhập, hiển thị avatar và tên đăng nhập -->
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Avatar người dùng -->
                            <img src="<?= isset($_SESSION['avatar']) ? $_SESSION['avatar'] : '/assets/default-avatar.jpg' ?>" alt="<?= $_SESSION['username'] ?>" class="rounded-circle" style="width: 30px; height: 30px; margin-right: 10px;">
                            <?= $_SESSION['username'] ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">Trang cá nhân</a></li>
                            <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <!-- Nếu người dùng chưa đăng nhập, hiển thị nút Login và Signup -->
                    <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
                    <a href="register.php" class="btn btn-primary">Signup</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Nội dung trang còn lại sẽ ở dưới -->
    <!-- Các phần còn lại của nội dung trang -->

</body>

</html>
