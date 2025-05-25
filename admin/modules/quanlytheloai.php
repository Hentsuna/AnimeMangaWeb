<?php
include("../db.php");

$success_message = '';
$redirect_script = '';

// Thêm thể loại
if (isset($_GET['action']) && $_GET['action'] === 'luu' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO genre (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $success_message = "✅ Thêm thể loại thành công.";
        $redirect_script = "<script>setTimeout(() => { window.location.href='index.php?chucnang=quanlytheloai'; }, 1000);</script>";
    } else {
        $success_message = "❌ Vui lòng nhập tên thể loại.";
    }
}

// Sửa thể loại
if (isset($_GET['action']) && $_GET['action'] === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;
    $edit_name = isset($_POST['edit_name']) ? trim($_POST['edit_name']) : '';
    if ($edit_id > 0 && !empty($edit_name)) {
        $stmt = $conn->prepare("UPDATE genre SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $edit_name, $edit_id);
        $stmt->execute();
        $success_message = "✅ Cập nhật thể loại thành công.";
        $redirect_script = "<script>setTimeout(() => { window.location.href='index.php?chucnang=quanlytheloai'; }, 1000);</script>";
    } else {
        $success_message = "❌ Tên thể loại không được để trống.";
    }
}

// Lấy danh sách thể loại, có sắp xếp
$sort_column = $_GET['sort'] ?? 'id';
$sort_order = ($_GET['order'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
$order_by = in_array($sort_column, ['id', 'name']) ? $sort_column : 'id';
$order_dir = strtoupper($sort_order) === 'DESC' ? 'DESC' : 'ASC';

$result = mysqli_query($conn, "SELECT * FROM genre ORDER BY $order_by $order_dir");

function sort_link($column, $label)
{
    $base = "index.php?chucnang=quanlytheloai";
    $current_sort = $_GET['sort'] ?? '';
    $current_order = $_GET['order'] ?? 'asc';
    $next_order = ($current_sort === $column && $current_order === 'asc') ? 'desc' : 'asc';
    return "<a href='$base&sort=$column&order=$next_order'>$label</a>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Quản lý Thể loại</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th a {
            text-decoration: none;
            color: black;
        }

        .actions button {
            margin-right: 5px;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .actions button.edit {
            background-color: #4CAF50;
            color: white;
        }

        .actions button.delete {
            background-color: #f44336;
            color: white;
        }

        .add-button button {
            padding: 8px 15px;
            background-color: #2196F3;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-button {
            margin-bottom: 15px;
        }

        .popup {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .popup-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 320px;
            border-radius: 10px;
            position: relative;
            text-align: center;
        }

        .close {
            position: absolute;
            top: 8px;
            right: 12px;
            color: #aaa;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: red;
        }

        .popup button.confirm {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 5px 0 5px;
        }

        .popup button.cancel {
            background-color: #bbb;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 5px 0 5px;
        }
    </style>
</head>

<body>
    <h2>Quản lý Thể loại</h2>

    <?php if (!empty($success_message)): ?>
        <div style="color: green; margin-bottom: 10px;">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>

    <div class="add-button">
        <button onclick="openAddPopup()">➕ Thêm thể loại</button>
    </div>

    <table>
        <tr>
            <th><?= sort_link('id', 'ID') ?></th>
            <th><?= sort_link('name', 'Tên thể loại') ?></th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td class="actions">
                    <button class="edit" onclick='openEditPopup(<?= $row['id'] ?>, <?= json_encode($row['name']) ?>)'>Sửa</button>
                    <a href="index.php?chucnang=delete_theloai&id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn xoá thể loại \'<?= addslashes($row['name']) ?>\' không?')">
                        <button class="delete">Xoá</button>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Popup Thêm Thể Loại -->
    <div id="popupAdd" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeAddPopup()">&times;</span>
            <h3>Thêm thể loại</h3>
            <form method="post" action="index.php?chucnang=quanlytheloai&action=luu">
                <input type="text" name="name" placeholder="Tên thể loại" required>
                <br><br>
                <button type="submit">Lưu</button>
            </form>
        </div>
    </div>

    <!-- Popup Sửa Thể Loại -->
    <div id="popupEdit" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeEditPopup()">&times;</span>
            <h3>Chỉnh sửa thể loại</h3>
            <form method="post" action="index.php?chucnang=quanlytheloai&action=edit">
                <input type="hidden" name="edit_id" id="edit_id">
                <input type="text" name="edit_name" id="edit_name" placeholder="Tên thể loại" required>
                <br><br>
                <button type="submit">Lưu</button>
            </form>
        </div>
    </div>

    <script>
        function openAddPopup() {
            document.getElementById("popupAdd").style.display = "block";
        }

        function closeAddPopup() {
            document.getElementById("popupAdd").style.display = "none";
        }

        function openEditPopup(id, name) {
            document.getElementById("edit_id").value = id;
            document.getElementById("edit_name").value = name;
            document.getElementById("popupEdit").style.display = "block";
        }

        function closeEditPopup() {
            document.getElementById("popupEdit").style.display = "none";
        }

        // Delete popup
        let deleteId = null;

        function openDeletePopup(id, name) {
            deleteId = id;
            document.getElementById('delete-message').textContent = `Bạn có chắc muốn xoá thể loại "${name}" không?`;
            document.getElementById('popupDelete').style.display = "block";
        }

        function closeDeletePopup() {
            deleteId = null;
            document.getElementById('popupDelete').style.display = "none";
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteId !== null) {
                // Chuyển đến trang xoá
                window.location.href = `index.php?chucnang=delete_theloai&id=${deleteId}`;
            }
        });
    </script>

    <?= $redirect_script ?>
</body>

</html>