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

if (isset($_GET['status'])) {
    $status = $_GET['status'];
    if ($status !== '') {
        $query .= " AND sc.approved = :status ";
    }
}

$query .= "ORDER BY sc.update_at LIMIT :start_from, :per_page_record";

$stmt = $conn->prepare($query);

if (!empty($search)) {
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}

if (isset($_GET['status']) && $status !== '') {
    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
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
if (!$regexResult) {
    echo 'Bạn không có quyền truy cập';
    exit;
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

        .flex-shrink-0 {
            height: 100vh;
            border-right: 1px solid #dee2e6;
        }

        .mb {
            padding-bottom: 46rem;
        }

        .btn-toggle {
            font-weight: 600;
            color: #495057;
            padding: 0.75rem 1rem;
            width: 100%;
            text-align: left;
            font-size: 1.5rem;
        }

        .btn-toggle:hover,
        .btn-toggle:focus {
            background-color: #ced4da;
            color: #212529;
        }

        .btn-toggle-nav a {
            padding: 0.5rem 1.5rem;
            color: #495057;
            transition: color 0.2s ease;
            font-size: 1.3rem;
        }

        .btn-toggle-nav a:hover {
            color: #007bff;
        }

        .btn-toggle-nav a.active {
            background-color: #007bff;
            color: #fff;
        }

        .collapse.show {
            background-color: #f1f3f5;
        }

        .collapse {
            padding-left: 0.75rem;
        }

        .list-unstyled {
            padding-left: 0;
        }

        .border-bottom {
            border-bottom: 1px solid #dee2e6;
        }

        .link-body-emphasis {
            color: #212529;
        }

        .link-body-emphasis:hover {
            color: #007bff;
            text-decoration: none;
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

        #sidebar.show {
            display: block;
        }

        .menu-icon {
            font-size: 1.8rem;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="menu-icon">
            <i class="fa-solid fa-bars" id="menu-icon"></i>
        </div>
        <div class="flex-shrink-0 p-3 sidebar" id="sidebar" style="width: 280px;">
            <ul class="list-unstyled ps-0">
                <li class="mb-1 pb-2">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                        Trang home
                    </button>
                    <div class="collapse" id="home-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="?module=home&action=dashboard" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Dashboard</a></li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1 pb-2">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                        Quản lý tài khoản
                    </button>
                    <div class="collapse" id="account-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <?php if (checkPrivilege('\?module=user&action=list')) { ?>
                                <li><a href="?module=user&action=list" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Trang người dùng</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=user&action=add')) { ?>
                                <li><a href="?module=user&action=add" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Thêm người dùng</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=user&action=edit&id=[0-9]*')) { ?>
                                <li><a href="?module=user&action=edit" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cập nhật người dùng</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=user&action=delete&id=[0-9]*')) { ?>
                                <li><a href="?module=user&action=delete" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Xóa người dùng</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
                <li class="mb-1 pb-2">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#student-collapse" aria-expanded="false">
                        Quản lý sinh viên
                    </button>
                    <div class="collapse" id="student-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <?php if (checkPrivilege('\?module=students&action=view')) { ?>
                                <li><a href="?module=students&action=view" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Trang sinh viên</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=students&action=add')) { ?>
                                <li><a href="?module=students&action=add" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Thêm sinh viên</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=students&action=update&student_id=[0-9]*')) { ?>
                                <li><a href="?module=students&action=update" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cập nhật sinh viên</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=students&action=delete&student_id=[0-9]*')) { ?>
                                <li><a href="?module=students&action=delete" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Xóa sinh viên</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
                <li class="mb-1 pb-2">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#section-collapse" aria-expanded="false">
                        Quản lý học phần
                    </button>
                    <div class="collapse" id="section-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <?php if (checkPrivilege('\?module=course&action=view_course')) { ?>
                                <li><a href="?module=course&action=view_course" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Trang học phần</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=course&action=add_course')) { ?>
                                <li><a href="?module=course&action=add_course" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Thêm học phần</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=course&action=update_course&course_id=[0-9]*')) { ?>
                                <li><a href="?module=course&action=update_course" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cập nhật học phần</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=course&action=delete_course&course_id=[0-9]*')) { ?>
                                <li><a href="?module=course&action=delete_course" class="l-ink-body-emphasis d-inline-flex text-decoration-none rounded">Xóa học phần</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
                <li class="mb-1 pb-2">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#transcript-collapse" aria-expanded="false">
                        Quản lý bảng điểm
                    </button>
                    <div class="collapse" id="transcript-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <?php if (checkPrivilege('\?module=scoresheets&action=student_courses')) { ?>
                                <li><a href="?module=scoresheets&action=student_courses" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Trang bảng điểm</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=scoresheets&action=add_score')) { ?>
                                <li><a href="?module=scoresheets&action=add_score" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Thêm bảng điểm</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=scoresheets&action=edit_scoresheets&id=[0-9]*')) { ?>
                                <li><a href="?module=scoresheets&action=edit_scoresheets" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cập nhật bảng điểm</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=scoresheets&action=delete_score&id=[0-9]*')) { ?>
                                <li><a href="?module=scoresheets&action=delete_score" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Xóa bảng điểm</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=scoresheets&action=print')) { ?>
                                <li><a href="?module=scoresheets&action=print" class="link-body-emphasis d-inline-flex text-decoration-none rounded">In bảng điểm</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#recognize-collapse" aria-expanded="false">
                        Kết quả công nhận điểm
                    </button>
                    <div class="collapse" id="recognize-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <?php if (checkPrivilege('\?module=viewdetail&action=view_result')) { ?>
                                <li><a href="?module=viewdetail&action=view_result" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Trang kết quả công nhận điểm</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=viewdetail&action=add_result')) { ?>
                                <li><a href="?module=viewdetail&action=add_result" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Thêm kết quả công nhận điểm</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=viewdetail&action=edit_result&result_id=[0-9]*')) { ?>
                                <li><a href="?module=viewdetail&action=edit_result" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cập nhật kết quả công nhận điểm</a></li>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=viewdetail&action=delete_result&result_id=[0-9]*')) { ?>
                                <li><a href="?module=viewdetail&action=delete_result" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Xóa kết quả công nhận điểm</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

        <div class="container">
            <hr>
            <h2>Kết quả công nhận điểm</h2>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <?php if (checkPrivilege('\?module=viewdetail&action=add_result')) { ?>
                        <a href="?module=viewdetail&action=add_result" class="btn btn-success btn-sm fs-5">Thêm kết quả công nhận điểm<i class="fa-solid fa-plus ms-2"></i></a>
                    <?php } ?>
                </div>

                <!-- Thanh tìm kiếm -->
                <form method="GET" action="">
                    <input type="hidden" name="module" value="viewdetail">
                    <input type="hidden" name="action" value="view_result">
                    <div class="input-group">
                        <input type="text" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" class="form-control fs-5" placeholder="Tìm kiếm...">
                        <select name="status" class="form-control fs-5">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" <?php echo isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : ''; ?>>Đã công nhận</option>
                            <option value="0" <?php echo isset($_GET['status']) && $_GET['status'] == '0' ? 'selected' : ''; ?>>Chưa công nhận</option>
                        </select>
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
                    <?php if (checkPrivilege('\?module=viewdetail&action=edit_result&result_id=[0-9]*')) { ?>
                        <th width="5%">Sửa</th>
                    <?php } ?>
                    <?php if (checkPrivilege('\?module=viewdetail&action=delete_result&result_id=[0-9]*')) { ?>
                        <th width="5%">Xóa</th>
                    <?php } ?>
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
                                <?php if (checkPrivilege('\?module=viewdetail&action=edit_result&result_id=[0-9]*')) { ?>
                                    <td><a href="<?php echo _WEB_HOST; ?>?module=viewdetail&action=edit_result&result_id=<?php echo $item['result_id']; ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
                                <?php } ?>
                                <?php if (checkPrivilege('\?module=viewdetail&action=delete_result&result_id=[0-9]*')) { ?>
                                    <td><a href="<?php echo _WEB_HOST; ?>?module=viewdetail&action=delete_result&result_id=<?php echo $item['result_id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                                <?php } ?>
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
    </div>
    <script>
        document.getElementById('menu-icon').addEventListener('click', function() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapse');
        });
    </script>
</body>

</html>

<?php
layouts('footer');
?>