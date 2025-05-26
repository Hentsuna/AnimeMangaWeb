<?php
include 'includes/header.php';
include 'db.php';

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $string = [
        'y' => 'năm',
        'm' => 'tháng',
        'd' => 'ngày',
        'h' => 'giờ',
        'i' => 'phút',
        's' => 'giây',
    ];
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v;
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' trước' : 'vừa xong';
}

$anime_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Truy vấn anime
$stmt = $conn->prepare("SELECT a.*, d.name AS director_name, s.name AS season_name 
                        FROM anime a 
                        LEFT JOIN directors d ON a.director_id = d.id 
                        LEFT JOIN seasons s ON a.season_id = s.id 
                        WHERE a.id = ?");
$stmt->bind_param("i", $anime_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container my-5'><h2>Anime không tồn tại.</h2></div>";
    include 'includes/footer.php';
    exit;
}

$anime = $result->fetch_assoc();

// Truy vấn thể loại
$genre_stmt = $conn->prepare("SELECT g.name FROM genre g 
                              INNER JOIN anime_genre ag ON g.id = ag.genre_id 
                              WHERE ag.anime_id = ?");
$genre_stmt->bind_param("i", $anime_id);
$genre_stmt->execute();
$genre_result = $genre_stmt->get_result();
$genres = [];
while ($row = $genre_result->fetch_assoc()) {
    $genres[] = $row['name'];
}

// Kiểm tra yêu thích
$is_favorited = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $check = $conn->prepare("SELECT 1 FROM anime_favorites WHERE user_id = ? AND anime_id = ?");
    $check->bind_param("ii", $user_id, $anime_id);
    $check->execute();
    $is_favorited = $check->get_result()->num_rows > 0;
}

// Xử lý yêu thích
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['anime_id']) && isset($_SESSION['user_id'])) {
    $anime_id = (int)$_POST['anime_id'];
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['unfavorite'])) {
        $stmt = $conn->prepare("DELETE FROM anime_favorites WHERE user_id = ? AND anime_id = ?");
        $stmt->bind_param("ii", $user_id, $anime_id);
        $stmt->execute();
    } else {
        $check = $conn->prepare("SELECT 1 FROM anime_favorites WHERE user_id = ? AND anime_id = ?");
        $check->bind_param("ii", $user_id, $anime_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO anime_favorites (user_id, anime_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $anime_id);
            $stmt->execute();
        }
    }

    header("Location: anime_detail.php?id=" . $anime_id);
    exit;
}
?>

<main class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <img src="<?= htmlspecialchars($anime['image']) ?>" alt="<?= htmlspecialchars($anime['title']) ?>" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-8">
            <h2 class="mb-3"><?= htmlspecialchars($anime['title']) ?></h2>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Score:</div>
                <div class="col-sm-8"><?= $anime['score'] ?? 'N/A' ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Status:</div>
                <div class="col-sm-8"><?= htmlspecialchars($anime['status']) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Episodes:</div>
                <div class="col-sm-8"><?= $anime['episodes'] ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Genres:</div>
                <div class="col-sm-8"><?= htmlspecialchars(implode(', ', $genres)) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Director:</div>
                <div class="col-sm-8"><?= htmlspecialchars($anime['director_name'] ?? 'Unknown') ?></div>
            </div>
            <div class="row mb-4">
                <div class="col-sm-4 fw-bold">Season:</div>
                <div class="col-sm-8"><?= htmlspecialchars($anime['season_name'] ?? 'Unknown') ?></div>
            </div>
            <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($anime['description'])) ?></p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="post" class="mt-3">
                    <input type="hidden" name="anime_id" value="<?= $anime['id'] ?>">
                    <?php if ($is_favorited): ?>
                        <button type="submit" name="unfavorite" value="1" class="btn btn-danger">
                            <i class="bi bi-heartbreak-fill me-1"></i> Huỷ yêu thích
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-heart-fill me-1"></i> Thêm vào yêu thích
                        </button>
                    <?php endif; ?>
                </form>

                <!-- Nút mở popup chấm điểm -->
                <button type="button" class="btn btn-warning mt-2" data-bs-toggle="modal" data-bs-target="#scoreModal">
                    <i class="bi bi-star-fill me-1"></i> Chấm điểm
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal chấm điểm -->
    <div class="modal fade" id="scoreModal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="rate_anime.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scoreModalLabel">Chấm điểm Anime</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="anime_id" value="<?= $anime['id'] ?>">
                    <label for="score" class="form-label">Điểm (1-10):</label>
                    <select name="score_given" id="score" class="form-select" required>
                        <?php for ($i = 10; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <hr class="my-5">
    <h4>Bình luận</h4>


    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Form bình luận -->
        <div class="d-flex mb-4">
            <img src="<?= $_SESSION['avatar'] ?? 'default-avatar.png' ?>" alt="avatar" width="50" height="50" class="rounded-circle me-3">
            <form action="post_comment.php" method="post" class="flex-grow-1">
                <input type="hidden" name="type" value="anime">
                <input type="hidden" name="anime_id" value="<?= $anime_id ?>">
                <input type="hidden" name="parent_id" value="">
                <textarea name="content" class="form-control mb-2" rows="2" placeholder="Viết bình luận..."></textarea>
                <button class="btn btn-primary btn-sm">Gửi</button>
            </form>
        </div>
    <?php else: ?>
        <p>Bạn cần <a href="login.php">đăng nhập</a> để bình luận.</p>
    <?php endif; ?>

    <!-- Danh sách bình luận -->
    <?php
    $comment_stmt = $conn->prepare("SELECT c.*, u.username, u.avatar 
                                    FROM comments c 
                                    JOIN users u ON c.user_id = u.id 
                                    WHERE c.anime_id = ? AND c.parent_id IS NULL 
                                    ORDER BY c.created_at DESC");
    $comment_stmt->bind_param("i", $anime_id);
    $comment_stmt->execute();
    $comment_result = $comment_stmt->get_result();
    ?>

    <?php while ($cmt = $comment_result->fetch_assoc()): ?>
        <div class="d-flex mb-3">
            <img src="<?= htmlspecialchars($cmt['avatar']) ?>" alt="avatar" width="50" height="50" class="rounded-circle me-3">
            <div>
                <strong><?= htmlspecialchars($cmt['username']) ?></strong>
                <small class="text-muted"> - <?= time_elapsed_string($cmt['created_at']) ?></small>
                <p><?= nl2br(htmlspecialchars($cmt['content'])) ?></p>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="#" class="reply-toggle text-decoration-none text-primary" data-id="<?= $cmt['id'] ?>">Phản hồi</a>
                    <form action="post_comment.php" method="post" class="reply-form mt-2 d-none">
                        <input type="hidden" name="type" value="anime">
                        <input type="hidden" name="anime_id" value="<?= $anime_id ?>">
                        <input type="hidden" name="parent_id" value="<?= $cmt['id'] ?>">
                        <textarea name="content" class="form-control mb-2" rows="2" placeholder="Nhập phản hồi..."></textarea>
                        <button class="btn btn-sm btn-secondary">Gửi phản hồi</button>
                    </form>
                <?php endif; ?>

                <!-- Hiển thị phản hồi -->
                <?php
                $reply_sql = "SELECT c.*, u.username, u.avatar FROM comments c
                          JOIN users u ON c.user_id = u.id
                          WHERE c.parent_id = ? ORDER BY c.created_at ASC";
                $reply_stmt = $conn->prepare($reply_sql);
                $reply_stmt->bind_param("i", $cmt['id']);
                $reply_stmt->execute();
                $reply_result = $reply_stmt->get_result();

                while ($reply = $reply_result->fetch_assoc()):
                ?>
                    <div class="d-flex mt-3 ms-5">
                        <img src="<?= htmlspecialchars($reply['avatar']) ?>" width="40" height="40" class="rounded-circle me-2">
                        <div>
                            <strong><?= htmlspecialchars($reply['username']) ?></strong>
                            <small class="text-muted"> - <?= time_elapsed_string($reply['created_at']) ?></small>
                            <p><?= nl2br(htmlspecialchars($reply['content'])) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endwhile; ?>

    <script>
        document.querySelectorAll('.reply-toggle').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const form = btn.nextElementSibling;
                form.classList.toggle('d-none');
            });
        });
    </script>
</main>

<?php include 'includes/footer.php'; ?>
