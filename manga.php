<?php
include 'includes/header.php';
include 'db.php'; // file kết nối database

// Truy vấn manga
$sql = "SELECT * FROM manga ORDER BY created_at DESC LIMIT 20";
$result = mysqli_query($conn, $sql);
?>

<main class="container my-5">
    <h1 class="text-center mb-4">Top Manga</h1>
    <div class="row g-4">

        <?php while ($manga = mysqli_fetch_assoc($result)) : ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <img src="<?= htmlspecialchars($manga['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($manga['title']) ?>" style="height: 300px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($manga['title']) ?></h5>
                        <p class="card-text text-muted mb-2">Score: <?= $manga['score'] ?></p>
                        <a href="manga_detail.php?id=<?= $manga['id'] ?>" class="btn btn-primary mt-auto">View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
