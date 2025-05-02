<?php
include 'includes/header.php';
include 'db.php';

// Lấy ID từ URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Truy vấn anime theo ID
    $sql = "SELECT * FROM anime WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $anime = mysqli_fetch_assoc($result);

    if (!$anime) {
        echo "<div class='container my-5'><h2>Anime not found!</h2></div>";
        include 'footer.php';
        exit;
    }
} else {
    echo "<div class='container my-5'><h2>Invalid Anime ID!</h2></div>";
    include 'footer.php';
    exit;
}
?>

<main class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <img src="<?= htmlspecialchars($anime['image_url']) ?>" alt="<?= htmlspecialchars($anime['title']) ?>" class="img-fluid rounded shadow">
            <a href="anime_episodes.php?id=<?= $anime['id'] ?>" class="btn btn-primary mt-3">Xem các tập</a>
        </div>
        <div class="col-md-8">
            <h1><?= htmlspecialchars($anime['title']) ?></h1>
            <p class="text-muted">Score: <?= $anime['score'] ?></p>
            <p><?= nl2br(htmlspecialchars($anime['description'])) ?></p>

            <a href="anime.php" class="btn btn-secondary mt-3">← Back to Anime List</a>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
