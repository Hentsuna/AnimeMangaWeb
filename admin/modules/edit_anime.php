<?php
include("../db.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ Thiếu ID anime.";
    exit;
}

$id = $_GET['id'];

// Lấy dữ liệu anime
$result = mysqli_query($conn, "SELECT * FROM anime WHERE id = $id");
$anime = mysqli_fetch_assoc($result);

if (!$anime) {
    echo "❌ Anime không tồn tại.";
    exit;
}

// Lấy season và director cho dropdown
$season_result = mysqli_query($conn, "SELECT id, name FROM seasons");
$director_result = mysqli_query($conn, "SELECT id, name FROM directors");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sửa Anime</title>
    <style>
        .form-group { margin-bottom: 10px; }
        label { display: block; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 6px; }
    </style>
</head>
<body>
    <h2>Sửa Anime: <?= htmlspecialchars($anime['title']) ?></h2>
    <form method="post" action="modules/update_anime_action.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $anime['id'] ?>">

        <div class="form-group">
            <label>Tiêu đề:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($anime['title']) ?>" required>
        </div>

        <div class="form-group">
            <label>Điểm:</label>
            <input type="number" step="0.1" name="score" value="<?= $anime['score'] ?>">
        </div>

        <div class="form-group">
            <label>Ảnh hiện tại:</label><br>
            <img src="../<?= $anime['image'] ?>" width="100"><br>
            <label>Chọn ảnh mới (nếu muốn thay):</label>
            <input type="file" name="image" accept="image/*">
        </div>

        <div class="form-group">
            <label>Mô tả:</label>
            <textarea name="description"><?= htmlspecialchars($anime['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Trạng thái:</label>
            <select name="status">
                <?php
                $statuses = ['Airing', 'Completed', 'Upcoming'];
                foreach ($statuses as $status) {
                    $selected = ($anime['status'] === $status) ? 'selected' : '';
                    echo "<option value='$status' $selected>$status</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Số tập:</label>
            <input type="number" name="episodes" value="<?= $anime['episodes'] ?>">
        </div>

        <div class="form-group">
            <label>Season:</label>
            <select name="season_id">
                <?php while ($row = mysqli_fetch_assoc($season_result)) {
                    $selected = ($anime['season_id'] == $row['id']) ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                } ?>
            </select>
        </div>

        <div class="form-group">
            <label>Đạo diễn:</label>
            <select name="director_id">
                <?php while ($row = mysqli_fetch_assoc($director_result)) {
                    $selected = ($anime['director_id'] == $row['id']) ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                } ?>
            </select>
        </div>

        <div class="form-group">
            <button type="submit">Cập nhật</button>
        </div>
    </form>
</body>
</html>
