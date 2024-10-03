<?php
if (!defined('_Code')) {
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Trang dashboard'
];

layouts('header', $data);

// Kiểm tra trạng thái đăng nhập
// if (!isLogin()) {
//     redirect('?module=auth&action=login');
// }

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

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .strengh {
            width: 20rem;
            height: 10rem;
            margin-top: 3rem;
        }

        .mb {
            height: 55.3rem;
        }

        .dp {
            display: flex;
            justify-content: space-around;
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
                <li class="mb-1 pb-2"><a class="p-4 fs-3" href="?module=home&action=dashboard"><i class="fa-solid fa-house"><span class="ms-3">DashBoard</span></i></a></li>
            </ul>
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
        <div class="main-content">
            <div class="bg-light mb">
                <!-- <h1>Dashboard</h1> -->
                <ul class="list-unstyled dp">
                    <li class="d-flex justify-content-around">
                        <a href="?module=students&action=view" class="border p-3 strengh bg-primary text-white fs-4 text-decoration-none">Danh sách sinh viên</a>
                    </li>
                    <li class="d-flex justify-content-around">
                        <a href="?module=course&action=view_course" class="border p-3 strengh bg-danger text-white fs-4 text-decoration-none">Trang học phần</a>
                    </li>
                    <li class="d-flex justify-content-around">
                        <a href="?module=scoresheets&action=student_courses" class="border p-3 strengh bg-warning text-white fs-4 text-decoration-none">Xem bảng điểm</a>
                    </li>
                    <li class="d-flex justify-content-around">
                        <a href="?module=viewdetail&action=view_result" class="border p-3 strengh bg-success text-white fs-4 text-decoration-none">Trang công nhận kết quả học tập</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
<script>
    document.getElementById('menu-icon').addEventListener('click', function() {
        var sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapse');
    });
</script>

</html>

<?php
layouts('footer');
?>