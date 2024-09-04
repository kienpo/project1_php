<?php
if (!defined('_Code')) {
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Danh sách người dùng'
];

layouts('header', $data);

// Kiểm tra trạng thái đăng nhập
// if (!isLogin()) {
//     redirect('?module=auth&action=login');
// }

$per_page_record = 5;  // Number of entries to show in a page
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1; // Get the current page number
$start_from = ($page - 1) * $per_page_record; // Calculate the starting record index

// Fetching the paginated data from the database
$query = "SELECT * FROM user ORDER BY update_at LIMIT :start_from, :per_page_record";
$stmt = $conn->prepare($query);
$stmt->bindValue(':start_from', $start_from, PDO::PARAM_INT);
$stmt->bindValue(':per_page_record', $per_page_record, PDO::PARAM_INT);
$stmt->execute();
$ListUser = $stmt->fetchAll(PDO::FETCH_ASSOC);

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
// $errors = getFlashData('errors');
// $old = getFlashData('old');

$regexResult = checkPrivilege();
if (!$regexResult){
    echo 'Bạn không có quyền truy cập';exit;
}
?>
<style>
    .pagination {
        display: inline-block;
    }

    .pagination a {
        font-weight: bold;
        font-size: 18px;
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        border: 1px solid black;
    }

    .pagination a.active {
        background-color: pink;
    }

    .pagination a:hover:not(.active) {
        background-color: skyblue;
    }

    .table-font {
        font-size: 20px;
    }
</style>

<div class="container">
    <hr>
    <h2>Quản Lý Người Dùng</h2>
    <p>
        <a href="?module=user&action=add" class="btn btn-success btn-sm fs-5">Thêm người dùng <i class="fa-solid fa-plus"></i></a>
    </p>
    <?php
    if (!empty($smg)) {
        getSmg($smg, $smg_type);
    }
    ?>
    <table class="table table-bordered table-font">
        <thead>
            <th>Stt</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Trạng thái</th>
            <th width="5%">Sửa</th>
            <th width="5%">Xóa</th>
        </thead>
        <tbody>
            <?php
            if (!empty($ListUser)):
                $count = ($page - 1) * $per_page_record + 1;
                foreach ($ListUser as $item):
            ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $item['fullname']; ?></td>
                        <td><?php echo $item['email']; ?></td>
                        <td><?php echo $item['phone']; ?></td>
                        <td><?php echo $item['status'] == 1 ? '<button class="btn btn-success btn-sm">Đã kích hoat </button>'
                                : '<button class="btn btn-danger btn-sm">Chưa kích hoat </button>'; ?></td>
                        <td><a href="<?php echo _WEB_HOST; ?>?module=user&action=edit&id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
                        <td><a href="<?php echo _WEB_HOST; ?>?module=user&action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                    </tr>
                <?php
                endforeach;
            else:
                ?>
                <tr>
                    <td colspan="7">
                        <div class="alert alert-danger text-center">Không có người dùng nào</div>
                    </td>
                </tr>
            <?php
            endif;
            ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php
        $query = "SELECT COUNT(*) FROM user";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_NUM);
        $total_records = $item[0];

        echo "</br>";
        // Number of pages required.   
        $total_pages = ceil($total_records / $per_page_record);
        $pagLink = "";

        if ($page >= 2) {
            echo "<a href='?module=user&action=list&page=" . ($page - 1) . "'>Prev</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                $pagLink .= "<a class='active' href='?module=user&action=list&page=" . $i . "'>" . $i . "</a>";
            } else {
                $pagLink .= "<a href='?module=user&action=list&page=" . $i . "'>" . $i . "</a>";
            }
        }
        echo $pagLink;

        if ($page < $total_pages) {
            echo "<a href='?module=user&action=list&page=" . ($page + 1) . "'>Next</a>";
        }
        ?>
    </div>
</div>

<?php
layouts('footer');
?>