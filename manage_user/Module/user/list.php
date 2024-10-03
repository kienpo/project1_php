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
if (!$regexResult) {
    echo 'Bạn không có quyền truy cập';
    exit;
}
?>
<style>
    .container-fluid {
        display: flex;
        margin-top: -16px;
        height: 100vh;
    }

    .mb {
        padding-bottom: 46rem;
    }

    .flex-shrink-0 {
        height: 100vh;
        border-right: 1px solid #dee2e6;
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
        <h2>Quản Lý Người Dùng</h2>
        <p>
            <?php if (checkPrivilege('\?module=user&action=add')) { ?>
                <a href="?module=user&action=add" class="btn btn-success btn-sm fs-5">Thêm người dùng <i class="fa-solid fa-plus"></i></a>
            <?php } ?>
        </p>
        <?php
        if (!empty($smg)) {
            getSmg($smg, $smg_type);
        }
        ?>
        <table class="table table-bordered table-font fs-5">
            <thead>
                <th>Stt</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Trạng thái</th>
                <?php if (checkPrivilege('\?module=access&action=manage&id=[0-9]*')) { ?>
                    <th>Phân quyền</th>
                <?php } ?>
                <?php if (checkPrivilege('\?module=user&action=edit&id=[0-9]*')) { ?>
                    <th width="5%">Sửa</th>
                <?php } ?>
                <?php if (checkPrivilege('\?module=user&action=delete&id=[0-9]*')) { ?>
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
                            <td><?php echo $item['fullname']; ?></td>
                            <td><?php echo $item['email']; ?></td>
                            <td><?php echo $item['phone']; ?></td>
                            <td><?php echo $item['status'] == 1 ? '<button class="btn btn-success btn-sm fs-5">Đã kích hoat </button>'
                                    : '<button class="btn btn-danger btn-sm fs-5">Chưa kích hoat </button>'; ?></td>
                            <?php if (checkPrivilege('\?module=access&action=manage&id=[0-9]*')) { ?>
                                <td><a href="<?php echo _WEB_HOST; ?>?module=access&action=manage&id=<?php echo $item['id']; ?>" class="text-decoration-none">Phân quyền</a></td>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=user&action=edit&id=[0-9]*')) { ?>
                                <td><a href="<?php echo _WEB_HOST; ?>?module=user&action=edit&id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
                            <?php } ?>
                            <?php if (checkPrivilege('\?module=user&action=delete&id=[0-9]*')) { ?>
                                <td><a href="<?php echo _WEB_HOST; ?>?module=user&action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                            <?php } ?>
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
    <script>
        document.getElementById('menu-icon').addEventListener('click', function() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapse');
        });
    </script>
</div>

<?php
layouts('footer');
?>