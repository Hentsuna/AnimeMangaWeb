<?php include 'includes/header.php'; ?>

<main class="container my-5">

    <?php
    // Giả lập lại dữ liệu anime
    $animes = [
        1 => [
            'title' => 'Attack on Titan',
            'score' => 9.1,
            'description' => 'Eren joins the scouting legion to get revenge on the Titans.',
            'image' => 'https://via.placeholder.com/300x400',
        ],
        2 => [
            'title' => 'One Piece',
            'score' => 8.9,
            'description' => 'Follow Luffy and his crew in their search for the ultimate treasure: the One Piece.',
            'image' => 'https://via.placeholder.com/300x400',
        ],
        3 => [
            'title' => 'Naruto',
            'score' => 8.2,
            'description' => 'Naruto Uzumaki, a young ninja, seeks recognition from his peers and dreams of becoming Hokage.',
            'image' => 'https://via.placeholder.com/300x400',
        ],
        4 => [
            'title' => 'Demon Slayer',
            'score' => 8.7,
            'description' => 'A boy joins the Demon Slayer Corps after demons kill his family.',
            'image' => 'https://via.placeholder.com/300x400',
        ],
    ];

    // Lấy id từ URL
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    // Kiểm tra anime có tồn tại không
    if (isset($animes[$id])) {
        $anime = $animes[$id];
    } else {
        echo "<div class='alert alert-danger'>Anime not found.</div>";
        include 'includes/footer.php';
        exit;
    }
    ?>

    <div class="row">
        <div class="col-md-4">
            <img src="<?= $anime['image'] ?>" alt="<?= htmlspecialchars($anime['title']) ?>" class="img-fluid rounded shadow-sm">
        </div>
        <div class="col-md-8">
            <h1 class="mb-3"><?= htmlspecialchars($anime['title']) ?></h1>
            <h5 class="text-muted mb-4">Score: <?= $anime['score'] ?></h5>
            <p><?= nl2br(htmlspecialchars($anime['description'])) ?></p>
            <a href="index.php" class="btn btn-primary mt-3">← Back to Top Anime</a>
        </div>
    </div>

</main>

<?php include 'includes/footer.php'; ?>