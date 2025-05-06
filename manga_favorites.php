<?php
include 'includes/header.php';
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Xử lý xoá manga yêu thích
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $manga_id = (int)$_POST['remove_id'];
    $stmt = $conn->prepare("DELETE FROM manga_favorites WHERE user_id = ? AND manga_id = ?");
    $stmt->bind_param("ii", $user_id, $manga_id);
    $stmt->execute();
    header("Location: manga_favorites.php");
    exit;
}

// Truy vấn danh sách manga yêu thích
$sql = "
    SELECT m.* FROM manga_favorites mf
    JOIN manga m ON mf.manga_id = m.id
    WHERE mf.user_id = ?
    ORDER BY mf.id DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main class="container my-5">
    <h1 class="text-center mb-4">Manga Yêu Thích Của Bạn</h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="row g-4">
            <?php while ($manga = $result->fetch_assoc()): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($manga['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($manga['title']) ?>" style="height: 300px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($manga['title']) ?></h5>
                            <p class="card-text text-muted mb-2">Volumes: <?= $manga['volumes'] ?? 'N/A' ?></p>
                            <a href="manga_detail.php?id=<?= $manga['id'] ?>" class="btn btn-sm btn-primary mb-2">Xem Chi Tiết</a>
                            <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá manga này khỏi danh sách yêu thích?');">
                                <input type="hidden" name="remove_id" value="<?= $manga['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Xoá khỏi yêu thích</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center">Bạn chưa thêm manga nào vào danh sách yêu thích.</p>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
