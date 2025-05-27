<?php
include 'includes/header.php';
include 'db.php'; // file kết nối database

$limit = 16; // 4 dòng × 4 manga
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Tổng số manga để tính số trang
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM manga");
$total_row = mysqli_fetch_assoc($total_result);
$total_manga = $total_row['total'];
$total_pages = ceil($total_manga / $limit);

// Truy vấn danh sách manga có phân trang
$sql = "SELECT * FROM manga ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<main class="container my-5">
    <h1 class="text-center mb-4">Manga</h1>
    <div class="row g-4">

        <?php while ($manga = mysqli_fetch_assoc($result)) : ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <img src="<?= htmlspecialchars($manga['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($manga['title']) ?>" style="height: 300px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($manga['title']) ?></h5>
                        <p class="card-text text-muted mb-2">Score: <?= $manga['score'] ?></p>
                        <a href="manga_detail.php?id=<?= $manga['id'] ?>" class="btn btn-primary mt-auto">View Details</a>
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
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
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
