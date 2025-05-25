<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyAnimeList</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php
    include 'includes/header.php';
    include 'db.php';

    $top_manga = $conn->query("SELECT * FROM manga ORDER BY score DESC LIMIT 20");

    // Truy vấn từng nhóm
    $airing = $conn->query("SELECT * FROM anime WHERE status = 'airing' ORDER BY score DESC LIMIT 5");
    $upcoming = $conn->query("SELECT * FROM anime WHERE status = 'upcoming' LIMIT 5");
    $popular = $conn->query("SELECT * FROM anime WHERE status = 'Completed' ORDER BY members DESC LIMIT 5");
    ?>


    <main class="container py-5">
        <h2 class="h4 fw-bold mb-3">Top Manga</h2>
        <div class="position-relative">
            <button class="btn btn-danger position-absolute top-50 start-0 translate-middle-y z-3 d-flex align-items-center justify-content-center"
                id="scrollLeft" style="width: 40px; height: 40px; padding: 0;">
                <i class="bi bi-chevron-left fs-4"></i>
            </button>


            <div class="d-flex overflow-auto px-5" id="mangaCarousel" style="scroll-behavior: smooth;">
                <?php while ($manga = $top_manga->fetch_assoc()): ?>
                    <div class="card mx-2" style="min-width: 180px;">
                        <img src="<?= $manga['image'] ?>" class="card-img-top" style="height: 240px; object-fit: cover;">
                        <div class="card-body p-2">
                            <h6 class="card-title text-truncate mb-1" title="<?= htmlspecialchars($manga['title']) ?>">
                                <a href="manga_detail.php?id=<?= $manga['id'] ?>" class="text-decoration-none fw-bold"><?= htmlspecialchars($manga['title']) ?></a>
                            </h6>
                            <p class="mb-0"><i class="bi bi-star-fill text-warning"></i> <?= $manga['score'] ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <button class="btn btn-danger position-absolute top-50 end-0 translate-middle-y z-3 d-flex align-items-center justify-content-center"
                id="scrollRight" style="width: 40px; height: 40px; padding: 0;">
                <i class="bi bi-chevron-right fs-4"></i>
            </button>
        </div>

        <h1 class="h3 fw-bold mt-4">Bảng Xếp Hạng Anime</h1>
        <div class="row">
            <!-- Airing -->
            <div class="col-md-3 border-dark p-3 m-5 rounded">
                <h5>Top Airing Anime</h5>
                <?php $i = 1;
                while ($row = $airing->fetch_assoc()): ?>
                    <div class="d-flex mb-3">
                        <span class="fw-bold me-2"><?= $i++ ?>.</span>
                        <img src="<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['title']) ?>" width="50" height="70" class="me-2">
                        <div>
                            <a href="anime_detail.php?id=<?= $row['id'] ?>" class="fw-semibold d-block"><?= htmlspecialchars($row['title']) ?></a>
                            <small class="text-muted">
                                <?= $row['episodes'] ?> eps, scored <?= $row['score'] ?? 'N/A' ?><br>
                                <?= number_format($row['members']) ?> members
                            </small>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Upcoming -->
            <div class="col-md-3 border-dark p-3 m-5 rounded">
                <h5>Top Upcoming Anime</h5>
                <?php $i = 1;
                while ($row = $upcoming->fetch_assoc()): ?>
                    <div class="d-flex mb-3">
                        <span class="fw-bold me-2"><?= $i++ ?>.</span>
                        <img src="<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['title']) ?>" width="50" height="70" class="me-2">
                        <div>
                            <a href="anime_detail.php?id=<?= $row['id'] ?>" class="fw-semibold d-block"><?= htmlspecialchars($row['title']) ?></a>
                            <small class="text-muted">
                                <?= $row['episodes'] ?> eps, scored <?= $row['score'] ?? 'N/A' ?><br>
                                <?= number_format($row['members']) ?> members
                            </small>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Popular -->
            <div class="col-md-3 border-dark p-3 m-5 rounded">
                <h5>Most Popular Anime</h5>
                <?php
                $i = 1;
                while ($row = $popular->fetch_assoc()): ?>
                    <div class="d-flex mb-3">
                        <span class="fw-bold me-2"><?= $i++ ?>.</span>
                        <img src="<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['title']) ?>" width="50" height="70" class="me-2">
                        <div>
                            <a href="anime_detail.php?id=<?= $row['id'] ?>" class="fw-semibold d-block"><?= htmlspecialchars($row['title']) ?></a>
                            <small class="text-muted">
                                <?= $row['episodes'] ?> eps, scored <?= $row['score'] ?? 'N/A' ?><br>
                                <?= number_format($row['members']) ?> members
                            </small>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        const carousel = document.getElementById('mangaCarousel');
        document.getElementById('scrollLeft').onclick = () => carousel.scrollBy({
            left: -300,
            behavior: 'smooth'
        });
        document.getElementById('scrollRight').onclick = () => carousel.scrollBy({
            left: 300,
            behavior: 'smooth'
        });
    </script>

</body>

</html>