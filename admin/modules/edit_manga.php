<?php
include("../db.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ Thiếu ID manga.";
    exit;
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM manga WHERE id = $id");
$manga = mysqli_fetch_assoc($result);

if (!$manga) {
    echo "❌ Manga không tồn tại.";
    exit;
}

$author_result = mysqli_query($conn, "SELECT id, name FROM authors");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sửa Manga</title>
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
        <h2>Sửa Manga: <?= htmlspecialchars($manga['title']) ?></h2>
        <form method="post" action="modules/update_manga_action.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $manga['id'] ?>">

            <div class="form-group">
                <label>Tiêu đề:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($manga['title']) ?>" required>
            </div>

            <div class="form-group">
                <label>Điểm:</label>
                <input type="number" step="0.1" name="score" value="<?= $manga['score'] ?>">
            </div>

            <div class="form-group">
                <label>Ảnh hiện tại:</label><br>
                <img src="../<?= $manga['image'] ?>" class="preview">
                <label>Chọn ảnh mới (nếu muốn thay):</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="form-group">
                <label>Mô tả:</label>
                <textarea name="description"><?= htmlspecialchars($manga['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Trạng thái:</label>
                <select name="status">
                    <?php
                    $statuses = ['Publishing', 'Completed', 'Upcoming'];
                    foreach ($statuses as $status) {
                        $selected = ($manga['status'] === $status) ? 'selected' : '';
                        echo "<option value='$status' $selected>$status</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Số chương:</label>
                <input type="number" name="chapters" value="<?= $manga['chapters'] ?>">
            </div>

            <div class="form-group">
                <label>Số tập:</label>
                <input type="number" name="volumes" value="<?= $manga['volumes'] ?>">
            </div>

            <div class="form-group">
                <label>Tác giả:</label>
                <select name="author_id">
                    <?php while ($row = mysqli_fetch_assoc($author_result)) {
                        $selected = ($manga['author_id'] == $row['id']) ? 'selected' : '';
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
