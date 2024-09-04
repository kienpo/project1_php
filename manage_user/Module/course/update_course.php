<?php
if(!defined('_Code')){
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Cập nhật điểm học phần'
];
layouts('header',$data);

// if(!isLogin()){
//     redirect('?module=auth&action=login');
// }

$filterAll = filter();
// $courseId = null;

if(!empty($filterAll['course_id'])){
    $courseId = $filterAll['course_id'];

    // Kiểm tra xem courseId nó tòn tại trong database ko
    // Nếu tồn tại => Lấy thông tin người dùng
    // Nếu ko tồn tại => Chuyển sang trang list
    $courseDetail = oneRaw("SELECT * FROM courses WHERE course_id='$courseId'");
    if(!empty($courseDetail)){
        // tồn tại
        setFlashData('courses-detail',$courseDetail);
    }else{
        redirect('?module=home&action=dashboard');
    }
}
if(isPost()){
    $filterAll = filter();
    $errors = [];

    // Validate course_code
    if (empty($filterAll['course_code'])) {
        $errors['course_code']['required'] = 'Mã học phần bắt buộc phải nhập';
    }

    // Validate course_name
    if (empty($filterAll['course_name'])) {
        $errors['course_name']['required'] = 'Tên học phần bắt buộc phải nhập';
    }
    // Validate credits
    if (empty($filterAll['credits'])) {
        $errors['credits']['required'] = 'Số tín chỉ bắt buộc phải nhập';
    } else {
        if (!is_numeric($filterAll['credits']) || $filterAll['credits'] <= 0) {
            $errors['credits']['invalid'] = 'Số tín chỉ phải là số dương';
        }
    } 
    if(empty($errors)){

        $dataUpdate = [
            'course_code' => $filterAll['course_code'],
            'course_name' => $filterAll['course_name'],
            'credits' => (int)$filterAll['credits'],
            'create_at' => date('Y-m-d H:i:s')
        ];


        $condition = "course_id = $courseId";
        $UpdateStatus = update('courses', $dataUpdate, $condition);
        if($UpdateStatus){
                setFlashData('smg','Update học phần thành công.');
                setFlashData('smg_type','success');
        }else{
            setFlashData('smg','Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type','danger');
        }
        
    }
    else{
        setFlashData('smg','Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type','danger');
        setFlashData('errors', $errors);
        setFlashData('old',$filterAll);
    }
    redirect('?module=course&action=update_course&course_id='.$courseId);  
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
// $old = getFlashData('old');
$courseDetailll = getFlashData('courses-detail');

// if(!empty($courseDetailll)){
//     $old = $courseDetailll;
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
    <title>Document</title>
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
                <li class="nav-item"><a class="nav-link p-4 fs-3 text-white" href="?module=home&action=dashboard"><i class="fa-solid fa-house"><span class="ms-3">DashBoard</span></i></a></li>
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
                    <h2 class="text-center text-uppercase">Cập nhật thông tin học phần</h2>
                    <?php
                        if(!empty($smg)){
                            getSmg($smg,$smg_type);
                        }
                    ?>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="course_code">Mã học phần</label>
                            <input type="text" name="course_code" class="form-control" id="course_code" value="<?= htmlspecialchars($old['course_code'] ?? '') ?>">
                            <?php if (!empty($errors['course_code'])): ?>
                                <span class="text-danger"><?= $errors['course_code']['required'] ?? '' ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="course_name">Tên học phần</label>
                            <input type="text" name="course_name" class="form-control" id="course_name" value="<?= htmlspecialchars($old['course_name'] ?? '') ?>">
                            <?php if (!empty($errors['course_name'])): ?>
                                <span class="text-danger"><?= $errors['course_name']['required'] ?? '' ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="credits">Số tín chỉ</label>
                            <input type="number" name="credits" class="form-control" id="credits" value="<?= htmlspecialchars($old['credits'] ?? '') ?>">
                            <?php if (!empty($errors['credits'])): ?>
                                <span class="text-danger"><?= $errors['credits']['required'] ?? '' ?></span>
                                <span class="text-danger"><?= $errors['credits']['invalid'] ?? '' ?></span>
                            <?php endif; ?>
                        </div>                           
                  

                        <input type="hidden" name="course_id" value="<?php echo $courseId ?>">
            
                        <button type="submit" class="mg-btn-op btn btn-primary btn-block">Cập nhật thông tin học phần</button>
                        <a href="?module=course&action=view_course" class="mg-btn-op btn btn-success btn-block">Quay lại</a>
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
