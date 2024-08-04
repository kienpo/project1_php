<?php
if(!defined('_Code')){
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Thêm sinh viên'
];
layouts('header',$data);

if(!isLogin()){
    redirect('?module=auth&action=login');
}

if(isPost()){
    $filterAll = filter();
    $errors = [];

    // fullname validate
    if(empty($filterAll['student_name'])){
        $errors['student_name']['required'] = 'Họ tên bắt buộc phải nhập';
    }else{
        if(strlen($filterAll['student_name']) < 5){
            $errors['student_name']['min'] = 'Họ tên phải có ít nhất 5 kí tự';  
        }
    }

    // email validate
    if(empty($filterAll['student_email'])){
        $errors['student_email']['required'] = 'Email bắt buộc phải nhập';
    }else{
        $email = $filterAll['student_email'];
        $sql = "SELECT student_id FRoM students WHERE student_email = '$email'";
        if(getRows($sql) > 0){
            $errors['student_email']['unique'] = 'Email đã tồn tại';
        }
    }


    // Validate 
    if(empty($filterAll['student_code'])){
        $errors['student_code']['required'] = 'Mã số sinh viên bắt buộc phải nhập';
    }else{
        if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]+$/', $filterAll['student_code'])) {
            $errors['student_code']['invalid'] = 'Mã số sinh viên phải chứa cả chữ và số';
        }
    }

    // Validate date
    if(empty($filterAll['date'])){
        $errors['date']['required'] = 'Ngày sinh bắt buộc phải nhập';
    }else{
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $filterAll['date'])) {
            $errors['date']['invalid'] = 'Ngày sinh phải có dạng: YYYY-MM-DD';
        }
    }

    if(empty($filterAll['department'])){
        $errors['department']['required'] = 'Khoa bắt buộc phải nhập';
    }else{
        if(strlen($filterAll['department']) <= 5){
            $errors['department']['min'] = 'Khoa phải có nhiều hơn 5 kí tự';  
        }
    }

    if(empty($filterAll['course'])){
        $errors['course']['required'] = 'Khóa học bắt buộc phải nhập';
    }else{
        if (!preg_match('/^\d{4}-\d{4}$/', $filterAll['course'])) {
            $errors['course']['invalid'] = 'Khóa học phải có dạng: YYYY-YYYY';
        }
    }

   
    if(empty($errors)){

        $dataInsert = [
            'student_name' => $filterAll['student_name'],
            'student_email' => $filterAll['student_email'],
            'student_code' => $filterAll['student_code'],
            'date' => $filterAll['date'],
            'department'=> $filterAll['department'],
            'course' => $filterAll['course'],
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('students', $dataInsert);

        if($insertStatus){
                setFlashData('smg','Thêm sinh viên mới thành công.');
                setFlashData('smg_type','success');
                redirect('?module=viewdetail&action=view');
        }else{
            setFlashData('smg','Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type','danger');
            redirect('?module=students&action=add');
        }
        
    }
    else{
        setFlashData('smg','Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type','danger');
        setFlashData('errors', $errors);
        setFlashData('old',$filterAll);
        redirect('?module=students&action=add');  
    }
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        .nav-item:hover {
            background-color: #343a40; /* Change this to the color you want on hover */
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
            <div class="container">
                <div class="row" style="margin: 50px auto;">
                    <h2 class="text-center text-uppercase">Thêm sinh viên mới</h2>
                    <?php
                        // if(!empty($smg)){
                        //     getSmg($smg,$smg_type);
                        // }
                    ?>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col">
                                <div class="form-group mg-form">
                                    <label for="" class="text-form-group">Họ tên</label>
                                    <input name="student_name" type="student_name" placeholder="Họ tên" class="form-group" />
                                </div>
                    
                                <div class="form-group mg-form">
                                    <label for="" class="text-form-group">Email</label>
                                    <input name="student_email" type="student_email" placeholder="Địa chỉ email" class="form-group">
                        
                                </div>
                                <div class="form-group mg-form">
                                    <label for="" class="text-form-group">Mã số sinh viên</label>
                                    <input name="student_code" type="" placeholder="MSSV" class="form-group"> 
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group mg-form">
                                    <label for="" class="text-form-group">Ngày sinh</label>
                                    <input name="date" type="date" placeholder="" class="form-group">
                                </div>

                                <div class="form-group mg-form">
                                    <label for="" class="text-form-group">Khoa</label>
                                    <input name="department" type="department" placeholder="Nhập tên khoa" class="form-group">
                                </div>

                                <div class="form-group mg-form">
                                    <label for="" class="text-form-group">Khóa học</label>
                                    <input name="course" type="course" placeholder="Nhập khóa học" class="form-group">
                                </div>
                    
                            </div>
                        </div>
            
                        <button type="submit" class="mg-btn-op btn btn-primary btn-block">Thêm sinh viên</button>
                        <a href="?module=home&action=dashboard" class="mg-btn-op btn btn-success btn-block">Quay lại</a>
                        <hr>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
layouts('footer');
?>
