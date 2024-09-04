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

        .nav-item:hover {
            background-color: #343a40;
            /* Change this to the color you want on hover */
        }

        .dp {
            display: flex;
            justify-content: space-around;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <nav class="bg-dark">
            <ul class="nav nav-item">
                <li class="nav-item"><a class="nav-link p-4 fs-3 text-white" href=""><i class="fa-solid fa-house"><span class="ms-3">DashBoard</span></i></a></li>
            </ul>
            <ul class="nav flex-column fs-4">
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=user&action=list">Quản lý tài khoản</a></li>
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=students&action=view">Quản lý sinh viên</a></li>
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=course&action=view_course">Quản lý học phần</a></li>
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=viewdetail&action=view_result">Quản lý kết quả công nhận điểm</a></li>
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=scoresheets&action=student_courses">Quản lý bảng điểm</a></li>
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=access&action=manage">Quản lý quyền truy cập</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <h1>Dashboard</h1>
            <div class="bg-light mb">
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
                        <a href="?module=viewdetail&action=add_result" class="border p-3 strengh bg-success text-white fs-4 text-decoration-none">Thêm kết quả học tập</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>

<?php
layouts('footer');
?>