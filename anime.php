<?php
include 'includes/header.php';
include 'db.php'; // kết nối database

// Truy vấn danh sách anime
$sql = "SELECT * FROM anime ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<main class="container my-5">
    <h1 class="text-center mb-4">Top Anime</h1>
    <div class="row g-4">

        <?php while ($anime = mysqli_fetch_assoc($result)) : ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <img src="<?= htmlspecialchars($anime['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($anime['title']) ?>" style="height: 300px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($anime['title']) ?></h5>
                        <p class="card-text text-muted mb-2">Score: <?= $anime['score'] ?></p>
                        <a href="anime_detail.php?id=<?= $anime['id'] ?>" class="btn btn-primary mt-auto">View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
