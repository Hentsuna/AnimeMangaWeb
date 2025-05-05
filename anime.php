<?php
include 'includes/header.php';
include 'db.php'; // kết nối database

// Phân trang
$limit = 16; // 4 dòng × 4 card
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Tổng số anime để tính tổng số trang
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM anime");
$total_row = mysqli_fetch_assoc($total_result);
$total_anime = $total_row['total'];
$total_pages = ceil($total_anime / $limit);

// Truy vấn danh sách anime có phân trang
$sql = "SELECT * FROM anime ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>


<main class="container my-5">
    <h1 class="text-center mb-4">Top Anime</h1>
    <div class="row g-4">

        <?php while ($anime = mysqli_fetch_assoc($result)) : ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <img src="<?= htmlspecialchars($anime['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($anime['title']) ?>" style="height: 300px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($anime['title']) ?></h5>
                        <p class="card-text text-muted mb-2">Score: <?= $anime['score'] ?></p>
                        <a href="anime_detail.php?id=<?= $anime['id'] ?>" class="btn btn-primary mt-auto">View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

    </div>
    <nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
        <!-- Nút Previous -->
        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <!-- Các số trang -->
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Nút Next -->
        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

</main>

<?php include 'includes/footer.php'; ?>
