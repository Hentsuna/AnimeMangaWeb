<!DOCTYPE html>
<html lang="en">
<?php
ob_start();
session_start();
include 'db.php';

$current_page = basename($_SERVER['PHP_SELF']);
$type = ($current_page === 'anime.php') ? 'anime' : (($current_page === 'manga.php') ? 'manga' : '');
$show_filters = true;

$genres = $directors = $seasons = $authors = [];

$genres = $conn->query("SELECT id, name FROM genre ORDER BY name")->fetch_all(MYSQLI_ASSOC);
if ($type === 'anime') {
    $directors = $conn->query("SELECT id, name FROM directors ORDER BY name")->fetch_all(MYSQLI_ASSOC);
    $seasons = $conn->query("
    SELECT DISTINCT s.id, s.name 
    FROM anime a 
    JOIN seasons s ON a.season_id = s.id 
    ORDER BY s.name DESC
")->fetch_all(MYSQLI_ASSOC);
} elseif ($type === 'manga') {
    $authors = $conn->query("SELECT id, name FROM authors ORDER BY name")->fetch_all(MYSQLI_ASSOC);
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyAnimeList Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">MyAnimeList</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link<?= $type === 'anime' ? ' active' : '' ?>" href="anime.php">Anime</a></li>
                    <li class="nav-item"><a class="nav-link<?= $type === 'manga' ? ' active' : '' ?>" href="manga.php">Manga</a></li>

                    <!-- Luôn hiển thị Thể loại -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Thể loại</a>
                        <ul class="dropdown-menu">
                            <?php foreach ($genres as $g): ?>
                                <li><a class="dropdown-item" href="search.php?type=<?= $type ?: 'anime' ?>&genre_id=<?= $g['id'] ?>">
                                        <?= htmlspecialchars($g['name']) ?>
                                    </a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>

                    <?php if ($type === 'anime'): ?>
                        <!-- Đạo diễn -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Đạo diễn</a>
                            <ul class="dropdown-menu">
                                <?php foreach ($directors as $d): ?>
                                    <li><a class="dropdown-item" href="search.php?type=anime&director_id=<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <!-- Mùa -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Mùa</a>
                            <ul class="dropdown-menu">
                                <?php foreach ($seasons as $season): ?>
                                    <li><a class="dropdown-item" href="search.php?type=anime&season_id=<?= $season['id'] ?>">
                                            <?= htmlspecialchars($season['name']) ?>
                                        </a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php elseif ($type === 'manga'): ?>
                        <!-- Tác giả -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Tác giả</a>
                            <ul class="dropdown-menu">
                                <?php foreach ($authors as $a): ?>
                                    <li><a class="dropdown-item" href="search.php?type=manga&author_id=<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>

            <form class="d-flex mx-auto w-50 position-relative" id="searchForm" method="GET" action="search.php">
                <input
                    class="form-control me-2"
                    type="search"
                    placeholder="Tìm anime hoặc manga..."
                    aria-label="Search"
                    id="searchInput"
                    name="q"
                    autocomplete="off">
                <ul id="suggestions" class="list-group position-absolute w-100 shadow" style="top: 100%; z-index: 1000;"></ul>
            </form>



            <div class="d-flex">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" data-bs-toggle="dropdown">
                            <img src="<?= $_SESSION['avatar'] ?? '/assets/default-avatar.jpg' ?>" class="rounded-circle" style="width: 30px; height: 30px; margin-right: 10px;" alt="Avatar">
                            <?= $_SESSION['username'] ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php">Trang cá nhân</a></li>
                            <li><a class="dropdown-item" href="anime_favorites.php">Anime yêu thích</a></li>
                            <li><a class="dropdown-item" href="manga_favorites.php">Manga yêu thích</a></li>
                            <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
                    <a href="register.php" class="btn btn-primary">Signup</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('searchInput');
            const list = document.getElementById('suggestions');

            input.addEventListener('input', async () => {
                const term = input.value.trim();
                if (!term) {
                    list.innerHTML = '';
                    return;
                }

                const res = await fetch('search_suggestions.php?term=' + encodeURIComponent(term));
                const data = await res.json();

                list.innerHTML = '';
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action d-flex align-items-center';

                    li.innerHTML = `
                <img src="${item.image}" alt="${item.title}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                <div class="flex-grow-1">
                    ${item.title}
                    <span class="badge bg-secondary ms-2 text-capitalize">${item.type}</span>
                </div>
            `;

                    li.addEventListener('click', () => {
                        const url = item.type === 'anime' ?
                            `anime_detail.php?id=${item.id}` :
                            `manga_detail.php?id=${item.id}`;
                        window.location.href = url;
                    });

                    list.appendChild(li);
                });
            });

            // Enter = chuyển đến trang search.php
            document.getElementById('searchForm').addEventListener('submit', function(e) {
                if (!input.value.trim()) {
                    e.preventDefault();
                }
            });

            // Ẩn danh sách gợi ý sau khi blur
            input.addEventListener('blur', () => setTimeout(() => list.innerHTML = '', 200));
        });
    </script>



    <!-- Nội dung trang còn lại sẽ ở dưới -->
    <!-- Các phần còn lại của nội dung trang -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>