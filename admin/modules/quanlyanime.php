<?php
include("../db.php");
if (isset($_SESSION['delete_message'])) {
    echo $_SESSION['delete_message']; // Hiển thị thông báo
    unset($_SESSION['delete_message']); // Xóa thông báo sau khi hiển thị
}

// Xử lý sắp xếp
$sort_column = $_GET['sort'] ?? 'id';
$sort_order = ($_GET['order'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

// Mapping tên cột để dùng trong ORDER BY
switch ($sort_column) {
    case 'season_name':
        $order_by = 's.name';
        break;
    case 'director_name':
        $order_by = 'd.name';
        break;
    case 'members':
        $order_by = 'members';
        break;
    case 'title':
    case 'score':
    case 'episodes':
    case 'status':
    case 'id':
        $order_by = "a.$sort_column";
        break;
    default:
        $order_by = "a.id";
}

$order_dir = strtoupper($sort_order) === 'DESC' ? 'DESC' : 'ASC';

// Truy vấn danh sách anime + số lượng members
$query = "SELECT a.*, 
                 s.name AS season_name, 
                 d.name AS director_name,
                 COUNT(af.id) AS members
          FROM anime a
          LEFT JOIN seasons s ON a.season_id = s.id
          LEFT JOIN directors d ON a.director_id = d.id
          LEFT JOIN anime_favorites af ON a.id = af.anime_id
          GROUP BY a.id
          ORDER BY $order_by $order_dir";

$result = mysqli_query($conn, $query);

// Hàm tạo link sắp xếp
function sort_link($column, $label)
{
    $base = "index.php?chucnang=quanlyanime";
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
    <title>Quản lý Anime</title>
    <style>
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
        }
    </style>
</head>

<body>
    <h2>Quản lý Anime</h2>
    <table>
        <tr>
            <th><?= sort_link('id', 'ID') ?></th>
            <th><?= sort_link('title', 'Tiêu đề') ?></th>
            <th><?= sort_link('score', 'Điểm') ?></th>
            <th>Ảnh</th>
            <th><?= sort_link('status', 'Trạng thái') ?></th>
            <th><?= sort_link('episodes', 'Số tập') ?></th>
            <th><?= sort_link('season_name', 'Season') ?></th>
            <th><?= sort_link('director_name', 'Đạo diễn') ?></th>
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
                <td><?= $row['episodes'] ?></td>
                <td><?= $row['season_name'] ?></td>
                <td><?= $row['director_name'] ?></td>
                <td><?= $row['members'] ?></td>
                <td class="actions">
                    <a href="index.php?chucnang=edit_anime&id=<?= $row['id'] ?>"><button>Sửa</button></a>
                    <a href="index.php?chucnang=delete_anime&id=<?= $row['id'] ?>" onclick="return confirm('Xoá anime này?')"><button>Xoá</button></a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>