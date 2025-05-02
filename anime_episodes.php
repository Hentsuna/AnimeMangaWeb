<?php
include 'includes/header.php';
include 'db.php';

if (isset($_GET['id'])) {
    $anime_id = (int)$_GET['id'];

    // Lấy thông tin anime
    $anime_sql = "SELECT * FROM anime WHERE id = $anime_id";
    $anime_result = mysqli_query($conn, $anime_sql);
    $anime = mysqli_fetch_assoc($anime_result);

    // Lấy danh sách các tập
    $ep_sql = "SELECT * FROM anime_episodes WHERE anime_id = $anime_id ORDER BY episode_number ASC";
    $episodes = mysqli_query($conn, $ep_sql);
} else {
    echo "ID không hợp lệ.";
    exit;
}
?>

<main class="container my-5">
    <h2 class="mb-4">Các tập của: <?= htmlspecialchars($anime['title']) ?></h2>

    <?php while ($ep = mysqli_fetch_assoc($episodes)): ?>
        <div class="mb-5">
            <h5>Tập <?= $ep['episode_number'] ?>: <?= htmlspecialchars($ep['title']) ?></h5>
            <div class="ratio ratio-16x9 mb-3">
                <iframe src="<?= htmlspecialchars($ep['video_url']) ?>" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    <?php endwhile; ?>

    <a href="anime_detail.php?id=<?= $anime['id'] ?>" class="btn btn-secondary">← Quay lại chi tiết anime</a>
</main>

<?php include 'includes/footer.php'; ?>
