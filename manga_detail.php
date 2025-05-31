<?php
include 'includes/header.php';
include 'db.php';

function time_elapsed_string($datetime, $full = false)
{
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

$manga_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT m.*, a.name AS author_name FROM manga m
                        LEFT JOIN authors a ON m.author_id = a.id
                        WHERE m.id = ?");
$stmt->bind_param("i", $manga_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container my-5'><h2>Manga không tồn tại.</h2></div>";
    include 'includes/footer.php';
    exit;
}

$manga = $result->fetch_assoc();

// Lấy thể loại
$genre_stmt = $conn->prepare("SELECT g.name FROM genre g
                              INNER JOIN manga_genre mg ON g.id = mg.genre_id
                              WHERE mg.manga_id = ?");
$genre_stmt->bind_param("i", $manga_id);
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
    $check = $conn->prepare("SELECT 1 FROM manga_favorites WHERE user_id = ? AND manga_id = ?");
    $check->bind_param("ii", $user_id, $manga_id);
    $check->execute();
    $is_favorited = $check->get_result()->num_rows > 0;
}

// Xử lý yêu thích
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['manga_id']) && isset($_SESSION['user_id'])) {
    $manga_id = (int)$_POST['manga_id'];
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['unfavorite'])) {
        $stmt = $conn->prepare("DELETE FROM manga_favorites WHERE user_id = ? AND manga_id = ?");
        $stmt->bind_param("ii", $user_id, $manga_id);
        $stmt->execute();
    } else {
        $check = $conn->prepare("SELECT 1 FROM manga_favorites WHERE user_id = ? AND manga_id = ?");
        $check->bind_param("ii", $user_id, $manga_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO manga_favorites (user_id, manga_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $manga_id);
            $stmt->execute();
        }
    }

    header("Location: manga_detail.php?id=" . $manga_id);
    exit;
}
?>

<main class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <img src="<?= htmlspecialchars($manga['image']) ?>" alt="<?= htmlspecialchars($manga['title']) ?>" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-8">
            <h2 class="mb-3"><?= htmlspecialchars($manga['title']) ?></h2>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Điểm:</div>
                <div class="col-sm-8"><?= $manga['score'] ?? 'N/A' ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Tác giả:</div>
                <div class="col-sm-8"><?= htmlspecialchars($manga['author_name'] ?? 'Không rõ') ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Số chương:</div>
                <div class="col-sm-8"><?= $manga['chapters'] ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Trạng thái:</div>
                <div class="col-sm-8"><?= htmlspecialchars($manga['status']) ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Thể loại:</div>
                <div class="col-sm-8"><?= htmlspecialchars(implode(', ', $genres)) ?></div>
            </div>
            <p><strong>Mô tả:</strong><br><?= nl2br(htmlspecialchars($manga['description'])) ?></p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="post" class="mt-3">
                    <input type="hidden" name="manga_id" value="<?= $manga['id'] ?>">
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

                <!-- Nút mở modal chấm điểm -->
                <button type="button" class="btn btn-warning mt-2" data-bs-toggle="modal" data-bs-target="#scoreModal">
                    <i class="bi bi-star-fill me-1"></i> Chấm điểm
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal chấm điểm -->
    <div class="modal fade" id="scoreModal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="rate_manga.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scoreModalLabel">Chấm điểm Manga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="manga_id" value="<?= $manga['id'] ?>">
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
                <input type="hidden" name="type" value="manga">
                <input type="hidden" name="manga_id" value="<?= $manga_id ?>">
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
                                    FROM manga_comments c 
                                    JOIN users u ON c.user_id = u.id 
                                    WHERE c.manga_id = ? AND c.parent_id IS NULL 
                                    ORDER BY c.created_at DESC");
    $comment_stmt->bind_param("i", $manga_id);
    $comment_stmt->execute();
    $comment_result = $comment_stmt->get_result();?>

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
                        <input type="hidden" name="type" value="manga">
                        <input type="hidden" name="manga_id" value="<?= $manga_id ?>">
                        <input type="hidden" name="parent_id" value="<?= $cmt['id'] ?>">
                        <textarea name="content" class="form-control mb-2" rows="2" placeholder="Nhập phản hồi..."></textarea>
                        <button class="btn btn-sm btn-secondary">Gửi phản hồi</button>
                    </form>
                <?php endif; ?>

                <?php
                $reply_sql = "SELECT c.*, u.username, u.avatar FROM manga_comments c
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