<?php
include 'includes/header.php';
include 'db.php';

// Lấy ID từ URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Truy vấn manga theo ID
    $sql = "SELECT * FROM manga WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $manga = mysqli_fetch_assoc($result);

    if (!$manga) {
        echo "<div class='container my-5'><h2>Manga not found!</h2></div>";
        include 'footer.php';
        exit;
    }
} else {
    echo "<div class='container my-5'><h2>Invalid Manga ID!</h2></div>";
    include 'footer.php';
    exit;
}
?>

<main class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <img src="<?= htmlspecialchars($manga['image_url']) ?>" alt="<?= htmlspecialchars($manga['title']) ?>" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-8">
            <h1><?= htmlspecialchars($manga['title']) ?></h1>
            <p class="text-muted">Score: <?= $manga['score'] ?></p>
            <p><?= nl2br(htmlspecialchars($manga['description'])) ?></p>

            <a href="manga.php" class="btn btn-secondary mt-3">← Back to Manga List</a>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
