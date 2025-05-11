<?php
include("../db.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ Thiếu ID anime.";
    exit;
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM anime WHERE id = $id");
$anime = mysqli_fetch_assoc($result);

if (!$anime) {
    echo "❌ Anime không tồn tại.";
    exit;
}

$season_result = mysqli_query($conn, "SELECT id, name FROM seasons");
$director_result = mysqli_query($conn, "SELECT id, name FROM directors");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sửa Anime</title>
    <style>
        * {
            box-sizing: border-box;
        }

        .container {
            background: #fff;
            padding: 25px 40px;
            max-width: 700px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            font-weight: bold;
            margin-bottom: 6px;
            display: block;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        img.preview {
            max-width: 150px;
            margin-top: 10px;
            display: block;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
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
                <img src="../<?= $anime['image'] ?>" class="preview">
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
    </div>
</body>

</html>