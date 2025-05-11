<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trang thêm manga</title>
    <link rel="stylesheet" href="include/style/addanime.css">
</head>

<body>
    <?php
    // Kết nối CSDL
    include("../db.php");

    // Lấy danh sách tác giả cho dropdown
    $author_result = mysqli_query($conn, "SELECT id, name FROM authors");
    ?>

    <div class="form-container">
        <h2>Thêm Manga Mới</h2>
        <form method="post" action="modules/add_manga_action.php" enctype="multipart/form-data">
            <div class="form-group">
                <label>Tiêu đề:</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Điểm (score):</label>
                <input type="number" step="0.1" name="score">
            </div>

            <div class="form-group">
                <label>Ảnh (upload):</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="form-group">
                <label>Mô tả:</label>
                <textarea name="description"></textarea>
            </div>

            <div class="form-group">
                <label>Trạng thái:</label>
                <select name="status">
                    <option value="Publishing">Publishing</option>
                    <option value="Completed">Completed</option>
                    <option value="Upcoming">Upcoming</option>
                    <option value="">Chưa rõ</option>
                </select>
            </div>

            <div class="form-group">
                <label>Số chương:</label>
                <input type="number" name="chapters">
            </div>

            <div class="form-group">
                <label>Số tập:</label>
                <input type="number" name="volumes">
            </div>

            <div class="form-group">
                <label>Tác giả:</label>
                <select name="author_id" required>
                    <?php while ($row = mysqli_fetch_assoc($author_result)) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit">Thêm manga</button>
            </div>
        </form>
    </div>
</body>

</html>
