<?php
if (!defined('_Code')) {
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Bảng Điểm'
];

layouts('header', $data);


// Kiểm tra trạng thái đăng nhập
// if (!isLogin()) {
//     redirect('?module=auth&action=login');
// }

$search = isset($_GET['search']) ? trim($_GET['search']) : ''; // Lấy từ khóa tìm kiếm

$per_page_record = 5;  // Number of entries to show in a page
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1; // Get the current page number
$start_from = ($page - 1) * $per_page_record; // Calculate the starting record index

// Query with JOIN across tables for paginated data
$query = "
    SELECT 
        sc.*, 
        s.student_name, 
        c.course_name 
    FROM 
        results sc
    JOIN 
        students s 
    ON 
        sc.student_Id = s.student_id 
    JOIN 
        courses c 
    ON 
        sc.course_Id = c.course_id 
    WHERE 1
";

if (!empty($search)) {
    $query .= "AND (s.student_name LIKE :search OR c.course_name LIKE :search) ";
}

$query .= "ORDER BY sc.update_at LIMIT :start_from, :per_page_record";

$stmt = $conn->prepare($query);

if (!empty($search)) {
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .container-fluid {
            display: flex;
            margin-top: -16px;
            height: 100vh;
        }

        nav {
            width: 340px;
            margin-left: -12px;
            padding-bottom: 260px;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }
        .mb {
            padding-bottom: 46rem;
        }
        .strengh {
            width: 20rem;
            height: 10rem;
            margin-top: 3rem;
        }
        .nav-item:hover {
            background-color: #343a40;
            /* Change this to the color you want on hover */
        }
        .dp {
            display: flex;
            justify-content: space-around;
        }

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
</head>
<body>
    <div class="container">
        <hr>
        <h2>Kết quả công nhận điểm</h2>
        <div class="d-flex justify-content-between align-items-center mb-3">
                <span>
                    <a href="?module=viewdetail&action=add_result" class="btn btn-success btn-sm fs-5">Thêm kết quả công nhận điểm<i class="fa-solid fa-plus ms-2"></i></a>
                    <a href="?module=students&action=filter" class="btn btn-success btn-sm fs-5">Lọc kết quả</a>
                </span>

                <!-- Thanh tìm kiếm -->
                <form method="GET" action="">
                    <input type="hidden" name="module" value="viewdetail">
                    <input type="hidden" name="action" value="view_result">
                    <div class="input-group">
                        <input type="text" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" class="form-control fs-5" placeholder="Tìm kiếm...">
                        <button type="submit" class="btn btn-primary fs-5">Tìm kiếm</button>
                    </div>
                </form>
            </div>

        <?php
        if (!empty($smg)) {
            getSmg($smg, $smg_type);
        }
        ?>
        <table class="table table-bordered table-font">
            <thead>
                <th>Stt</th>
                <th>Tên sinh viên</th>
                <th>Môn học phần</th>
                <th>Điểm</th>
                <th>Trạng thái công nhận điểm</th>
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
                            <td><?php echo htmlspecialchars($item['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['course_name']); ?></td>
                            <td><?php echo $item['marks']; ?></td>
                            <td><?php echo $item['approved'] == 1 ? '<button class="btn btn-success btn-sm">Đã công nhận </button>'
                                    : '<button class="btn btn-danger btn-sm">Chưa công nhận </button>'; ?></td>
                            <td><a href="<?php echo _WEB_HOST; ?>?module=viewdetail&action=edit_result&result_id=<?php echo $item['result_id']; ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
                            <td><a href="<?php echo _WEB_HOST; ?>?module=viewdetail&action=delete_result&result_id=<?php echo $item['result_id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                        </tr>
                    <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="7">
                            <div class="alert alert-danger text-center">Không có kết quả công nhận điểm nào</div>
                        </td>
                    </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php
            $query = "
            SELECT COUNT(*) 
            FROM results sc
            JOIN courses c ON sc.course_Id = c.course_id
            JOIN students s ON sc.student_Id = s.student_id
            WHERE 1
            ";
            if (!empty($search)) {
                $query .= "AND (s.student_name LIKE :search OR c.course_name LIKE :search)";
            }
            $stmt = $conn->prepare($query);
            if (!empty($search)) {
                $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            }
            $stmt->execute();
            $item = $stmt->fetch(PDO::FETCH_NUM);
            $total_records = $item[0];

            echo "</br>";
            // Number of pages required.   
            $total_pages = ceil($total_records / $per_page_record);
            $pagLink = "";

            if ($page >= 2) {
                echo "<a href='?module=viewdetail&action=view_result&page=" . ($page - 1) . "'>Prev</a>";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    $pagLink .= "<a class='active' href='?module=viewdetail&action=view_result&page=" . $i . "'>" . $i . "</a>";
                } else {
                    $pagLink .= "<a href='?module=viewdetail&action=view_result&page=" . $i . "'>" . $i . "</a>";
                }
            }
            echo $pagLink;

            if ($page < $total_pages) {
                echo "<a href='?module=viewdetail&action=view_result&page=" . ($page + 1) . "'>Next</a>";
            }
            ?>
        </div>
    </div>
</body>

</html>

<?php
layouts('footer');
?>