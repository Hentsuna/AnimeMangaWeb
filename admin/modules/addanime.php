<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trang thêm anime</title>
    <link rel="stylesheet" href="include/style/addanime.css">
</head>

<body>
    <?php
    // Kết nối CSDL
    include("../db.php");

    // Lấy season và director để tạo dropdown
    $season_result = mysqli_query($conn, "SELECT id, name FROM seasons");
    $director_result = mysqli_query($conn, "SELECT id, name FROM directors");
    ?>

    <div class="form-container">
        <h2>Thêm Anime Mới</h2>
        <form method="post" action="modules/add_anime_action.php" enctype="multipart/form-data">
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
                    <option value="Airing">Airing</option>
                    <option value="Completed">Completed</option>
                    <option value="Upcoming">Upcoming</option>
                    <option value="">Chưa rõ</option>
                </select>
            </div>

            <div class="form-group">
                <label>Số tập:</label>
                <input type="number" name="episodes">
            </div>

            <div class="form-group">
                <label>Season:</label>
                <select name="season_id">
                    <?php while ($row = mysqli_fetch_assoc($season_result)) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Đạo diễn:</label>
                <select name="director_id">
                    <?php while ($row = mysqli_fetch_assoc($director_result)) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit">Thêm anime</button>
            </div>
        </form>
    </div>
</body>

</html>