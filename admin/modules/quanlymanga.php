<?php
include("../db.php");

// Xử lý sắp xếp
$sort_column = $_GET['sort'] ?? 'id';
$sort_order = ($_GET['order'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

// Mapping tên cột để dùng trong ORDER BY
switch ($sort_column) {
    case 'members':      $order_by = 'members'; break;
    case 'title':
    case 'score':
    case 'status':
    case 'chapters':
    case 'volumes':
    case 'id':           $order_by = "m.$sort_column"; break;
    default:             $order_by = "m.id";
}

$order_dir = strtoupper($sort_order) === 'DESC' ? 'DESC' : 'ASC';

// Truy vấn danh sách manga + số lượng members
$query = "SELECT m.*, 
                 COUNT(mf.id) AS members
          FROM manga m
          LEFT JOIN manga_favorites mf ON m.id = mf.manga_id
          GROUP BY m.id
          ORDER BY $order_by $order_dir";

$result = mysqli_query($conn, $query);

// Hàm tạo link sắp xếp
function sort_link($column, $label) {
    $base = "index.php?chucnang=quanlymanga";
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
    <title>Quản lý Manga</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th a { text-decoration: none; color: black; }
        .actions button { margin-right: 5px; }
    </style>
</head>
<body>
    <h2>Quản lý Manga</h2>
    <table>
        <tr>
            <th><?= sort_link('id', 'ID') ?></th>
            <th><?= sort_link('title', 'Tiêu đề') ?></th>
            <th><?= sort_link('score', 'Điểm') ?></th>
            <th>Ảnh</th>
            <th><?= sort_link('status', 'Trạng thái') ?></th>
            <th><?= sort_link('chapters', 'Số chương') ?></th>
            <th><?= sort_link('volumes', 'Số volume') ?></th>
            <th><?= sort_link('members', 'Members') ?></th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= $row['score'] ?></td>
                <td><img src="../<?= $row['image'] ?>" alt="Ảnh" width="60"></td>
                <td><?= $row['status'] ?></td>
                <td><?= $row['chapters'] ?></td>
                <td><?= $row['volumes'] ?></td>
                <td><?= $row['members'] ?></td>
                <td class="actions">
                    <a href="index.php?chucnang=edit_manga&id=<?= $row['id'] ?>"><button>Sửa</button></a>
                    <a href="index.php?chucnang=delete_manga&id=<?= $row['id'] ?>" onclick="return confirm('Xoá manga này?')"><button>Xoá</button></a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
