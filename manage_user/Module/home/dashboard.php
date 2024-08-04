<?php
if(!defined('_Code')){
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Trang dashboard'
];

layouts('header',$data);

// Kiểm tra trạng thái đăng nhập
if(!isLogin()){
    redirect('?module=auth&action=login');
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
        .mb{
            padding-bottom: 46rem;
        }
        .strengh{
            width: 20rem;
            height: 10rem;
            margin-top: 3rem;
        }
        .nav-item:hover {
            background-color: #343a40; /* Change this to the color you want on hover */
        }
        .dp{
            display: flex;
            justify-content: space-around;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <nav class="bg-dark">
            <ul class="nav nav-item">
                <li class="nav-item"><a class="nav-link p-4 fs-3 text-white" href=""><i class="fa-solid fa-house"><span class="ms-2">DashBoard</span></i></a></li>
            </ul>
            <ul class="nav flex-column fs-4">
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=students&action=add">Thêm mới sinh viên</a></li>
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=students&action=update">Cập nhật thông tin sinh viên</a></li>
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=students&action=delete">Xóa sinh viên</a></li>
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=grades&action=approve">Xét duyệt điểm học phần</a></li>
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=students&action=filter">Lọc danh sách sinh viên</a></li>
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=grades&action=update">Cập nhật điểm học phần</a></li>
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=scoresheets&action=print">In ấn bảng điểm</a></li>
                <li class="nav-item mb-3 "><a class="nav-link text-white" href="?module=access&action=manage">Quản lý quyền truy cập</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <h1>Dashboard</h1>
            <ul class="bg-light mb list-unstyled dp">
                <li class="d-flex justify-content-around">
                    <a href="?module=viewdetail&action=view" class="border p-3 strengh bg-primary text-white fs-4 text-decoration-none">Danh sách sinh viên</a>
                </li>
                <li class="d-flex justify-content-around">
                    <a href="?module=viewdetail&action=add_course" class="border p-3 strengh bg-danger text-white fs-4 text-decoration-none">Thêm học phần</a>
                </li>
                <li class="d-flex justify-content-around">
                    <a href="?module=viewdetail&action=decision" class="border p-3 strengh bg-warning text-white fs-4 text-decoration-none">Thêm quyết định</a>
                </li>
                <li class="d-flex justify-content-around">
                    <a href="?module=viewdetail&action=add_result" class="border p-3 strengh bg-success text-white fs-4 text-decoration-none">Thêm kết quả học tập</a>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>

<?php
layouts('footer');
?>