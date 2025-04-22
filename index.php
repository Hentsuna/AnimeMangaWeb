<?php include 'includes/header.php'; ?>

<!-- Main Content -->
<main class="container py-5">
    <h1 class="h2 fw-bold mb-4">Top Anime</h1>
    <div class="row g-4">

        <?php
        $animes = [
            ['id' => 1, 'title' => 'Attack on Titan', 'score' => 9.1, 'image' => 'https://via.placeholder.com/300x400'],
            ['id' => 2, 'title' => 'One Piece', 'score' => 8.9, 'image' => 'https://via.placeholder.com/300x400'],
            ['id' => 3, 'title' => 'Naruto', 'score' => 8.2, 'image' => 'https://via.placeholder.com/300x400'],
            ['id' => 4, 'title' => 'Demon Slayer', 'score' => 8.7, 'image' => 'https://via.placeholder.com/300x400'],
        ];
        ?>

        <?php foreach ($animes as $anime) : ?>
            <div class="card h-100">
                <a href="anime.php?id=<?= $anime['id'] ?>">
                    <img src="<?= $anime['image'] ?>" alt="<?= htmlspecialchars($anime['title']) ?>" class="card-img-top">
                </a>
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="anime.php?id=<?= $anime['id'] ?>" class="text-decoration-none text-dark">
                            <?= htmlspecialchars($anime['title']) ?>
                        </a>
                    </h5>
                    <p class="card-text text-muted">Score: <?= $anime['score'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>


    </div>
</main>

<?php include 'includes/footer.php'; ?>