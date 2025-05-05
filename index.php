<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyAnimeList Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>

<body>
    <?php
    include 'includes/header.php';
    include 'db.php';

    // Truy vấn từng nhóm
    $airing = $conn->query("SELECT * FROM anime WHERE status = 'airing' ORDER BY score DESC LIMIT 5");
    $upcoming = $conn->query("SELECT * FROM anime WHERE status = 'upcoming' LIMIT 5");
    $popular = $conn->query("SELECT * FROM anime WHERE status = 'Completed' ORDER BY members DESC LIMIT 5");
    ?>


    <main class="container py-5">
        <h1 class="h3 fw-bold mb-4">Bảng Xếp Hạng Anime</h1>
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

</body>

</html>