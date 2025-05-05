<?php
include 'includes/header.php';
include 'db.php';

// Tạm thời giả định người dùng có ID là 1
$user_id = $_SESSION['user_id'];

// Xử lý khi người dùng yêu cầu xóa anime yêu thích
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $anime_id = (int)$_POST['remove_id'];
    $stmt = $conn->prepare("DELETE FROM anime_favorites WHERE user_id = ? AND anime_id = ?");
    $stmt->bind_param("ii", $user_id, $anime_id);
    $stmt->execute();
    // Chuyển hướng lại để tránh gửi lại form khi refresh
    header("Location: anime_favorites.php");
    exit;
}

// Truy vấn danh sách anime yêu thích
$sql = "
    SELECT a.* FROM anime_favorites af
    JOIN anime a ON af.anime_id = a.id
    WHERE af.user_id = ?
    ORDER BY af.id DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main class="container my-5">
    <h1 class="text-center mb-4">Anime Yêu Thích Của Bạn</h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="row g-4">
            <?php while ($anime = $result->fetch_assoc()): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($anime['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($anime['title']) ?>" style="height: 300px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($anime['title']) ?></h5>
                            <p class="card-text text-muted mb-2">Score: <?= $anime['score'] ?></p>
                            <a href="anime_detail.php?id=<?= $anime['id'] ?>" class="btn btn-sm btn-primary mb-2">Xem Chi Tiết</a>
                            <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá anime này khỏi danh sách yêu thích?');">
                                <input type="hidden" name="remove_id" value="<?= $anime['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Xoá khỏi yêu thích</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center">Bạn chưa thêm anime nào vào danh sách yêu thích.</p>
    <?php endif; ?>


</main>

<?php include 'includes/footer.php'; ?>