<?php
include 'includes/header.php';
include 'db.php';

$keyword = $_GET['keyword'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;

$genre_id = $_GET['genre_id'] ?? null;
$author_id = $_GET['author_id'] ?? null;
$director_id = $_GET['director_id'] ?? null;

$anime_items = [];
$manga_items = [];
$total_anime = 0;
$total_manga = 0;

$anime_query = "SELECT SQL_CALC_FOUND_ROWS * FROM anime WHERE title LIKE ?";
$manga_query = "SELECT SQL_CALC_FOUND_ROWS * FROM manga WHERE title LIKE ?";
$anime_params = ["%$keyword%"];
$manga_params = ["%$keyword%"];
$anime_types = 's';
$manga_types = 's';

if ($genre_id) {
    $anime_query = "SELECT SQL_CALC_FOUND_ROWS a.* FROM anime a JOIN anime_genre ag ON a.id = ag.anime_id WHERE a.title LIKE ? AND ag.genre_id = ?";
    $manga_query = "SELECT SQL_CALC_FOUND_ROWS m.* FROM manga m JOIN manga_genre mg ON m.id = mg.manga_id WHERE m.title LIKE ? AND mg.genre_id = ?";
    $anime_params[] = $genre_id;
    $manga_params[] = $genre_id;
    $anime_types .= 'i';
    $manga_types .= 'i';
}

if ($director_id) {
    $anime_query .= " AND director_id = ?";
    $anime_params[] = $director_id;
    $anime_types .= 'i';
}

if ($author_id) {
    $manga_query .= " AND author_id = ?";
    $manga_params[] = $author_id;
    $manga_types .= 'i';
}

$anime_query .= " LIMIT $limit OFFSET $offset";
$manga_query .= " LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($anime_query);
$stmt->bind_param($anime_types, ...$anime_params);
$stmt->execute();
$result = $stmt->get_result();
$anime_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt = $conn->query("SELECT FOUND_ROWS() as total");
$total_anime = $stmt->fetch_assoc()['total'];

$stmt = $conn->prepare($manga_query);
$stmt->bind_param($manga_types, ...$manga_params);
$stmt->execute();
$result = $stmt->get_result();
$manga_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt = $conn->query("SELECT FOUND_ROWS() as total");
$total_manga = $stmt->fetch_assoc()['total'];


function render_items($items, $type) {
    foreach ($items as $item) {
        echo '<div class="col-6 col-md-4 col-lg-3">';
        echo '<div class="card h-100 shadow-sm">';
        echo '<img src="' . htmlspecialchars($item['image']) . '" class="card-img-top" alt="' . htmlspecialchars($item['title']) . '" style="height: 300px; object-fit: cover;">';
        echo '<div class="card-body d-flex flex-column">';
        echo '<h5 class="card-title">' . htmlspecialchars($item['title']) . '</h5>';
        echo '<p class="card-text text-muted mb-2">Score: ' . $item['score'] . '</p>';
        echo '<a href="' . $type . '_detail.php?id=' . $item['id'] . '" class="btn btn-sm btn-primary mt-auto">Xem Chi Tiết</a>';
        echo '</div></div></div>';
    }
}
?>

<main class="container my-5">

    <form class="row g-3 mb-4" method="GET">
        <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
        <div class="col-md-4">
            <label for="genre_id" class="form-label">Thể loại</label>
            <select name="genre_id" id="genre_id" class="form-select">
                <option value="">-- Tất cả --</option>
                <?php
                $genres = $conn->query("SELECT * FROM genre ORDER BY name");
                while ($g = $genres->fetch_assoc()): ?>
                    <option value="<?= $g['id'] ?>" <?= ($genre_id == $g['id']) ? 'selected' : '' ?>><?= $g['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="director_id" class="form-label">Đạo diễn (Anime)</label>
            <select name="director_id" id="director_id" class="form-select">
                <option value="">-- Tất cả --</option>
                <?php
                $directors = $conn->query("SELECT * FROM directors ORDER BY name");
                while ($d = $directors->fetch_assoc()): ?>
                    <option value="<?= $d['id'] ?>" <?= ($director_id == $d['id']) ? 'selected' : '' ?>><?= $d['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="author_id" class="form-label">Tác giả (Manga)</label>
            <select name="author_id" id="author_id" class="form-select">
                <option value="">-- Tất cả --</option>
                <?php
                $authors = $conn->query("SELECT * FROM authors ORDER BY name");
                while ($a = $authors->fetch_assoc()): ?>
                    <option value="<?= $a['id'] ?>" <?= ($author_id == $a['id']) ? 'selected' : '' ?>><?= $a['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </form>

    <?php if (count($anime_items) > 0): ?>
        <h2 class="mb-3">Anime</h2>
        <div class="row g-4 mb-5">
            <?php render_items($anime_items, 'anime'); ?>
        </div>
    <?php endif; ?>

    <?php if (count($manga_items) > 0): ?>
        <h2 class="mb-3">Manga</h2>
        <div class="row g-4">
            <?php render_items($manga_items, 'manga'); ?>
        </div>
    <?php endif; ?>

    <?php if (count($anime_items) === 0 && count($manga_items) === 0): ?>
        <p class="text-center text-muted">Không tìm thấy kết quả phù hợp.</p>
    <?php endif; ?>
</main>

<?php
$total_items = max($total_anime, $total_manga);
$total_pages = ceil($total_items / $limit);

if ($total_pages > 1): ?>
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link"
                       href="?keyword=<?= urlencode($keyword) ?>&genre_id=<?= urlencode($genre_id) ?>&director_id=<?= urlencode($director_id) ?>&author_id=<?= urlencode($author_id) ?>&page=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
