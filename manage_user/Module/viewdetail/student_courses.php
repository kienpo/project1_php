<?php
if (!defined('_Code')) {
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Đăng ký học phần cho sinh viên'
];
layouts('header', $data);

if (!isLogin()) {
    redirect('?module=auth&action=login');
}

if (isPost()) {
    $filterAll = filter();
    $errors = [];

    // Validate student_id
    if (empty($filterAll['student_id'])) {
        $errors['student_id']['required'] = 'Mã sinh viên bắt buộc phải nhập';
    } else {
        $student_id = $filterAll['student_id'];
        $studentCheck = $conn->query("SELECT student_id FROM students WHERE student_id = '$student_id'");
        if ($studentCheck->num_rows == 0) {
            $errors['student_id']['invalid'] = 'Mã sinh viên không tồn tại';
        } 
    }

    // Validate course_id
    if (empty($filterAll['course_id'])) {
        $errors['course_id']['required'] = 'Mã học phần bắt buộc phải nhập';
    } else {
        $course_id = $filterAll['course_id'];
        $courseCheck = $conn->query("SELECT course_id FROM courses WHERE course_id = '$course_id'");
        if ($courseCheck->num_rows == 0) {
            $errors['course_id']['invalid'] = 'Mã học phần không tồn tại';
        } 
    }

    if (empty($errors)) {
        $dataInsert = [
            'student_id' => $student_id,
            'course_id' => $course_id
        ];

        $insertStatus = insert('student_courses', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Đăng ký học phần thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $filterAll);
    }

    redirect('?module=viewdetail&action=student_courses');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký học phần cho sinh viên</title>
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
        .mb {
            padding-bottom: 46rem;
        }
        .nav-item:hover {
            background-color: #343a40;
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
                <li class="nav-item mb-3"><a class="nav-link text-white" href="?module=add_student_course&action=add_student_course">Đăng ký học phần</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <div class="container">
                <div class="row" style="margin: 50px auto;">
                    <h2 class="text-center text-uppercase">Đăng ký học phần cho sinh viên</h2>
                    <?php if (!empty($msg)): ?>
                        <div class="alert alert-<?= htmlspecialchars($msg_type) ?>">
                            <?= htmlspecialchars($msg) ?>
                        </div>
                    <?php endif; ?>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="student_id">Mã sinh viên</label>
                            <input type="text" name="student_id" class="form-control" id="student_id" value="<?= htmlspecialchars($old['student_id'] ?? '') ?>">
                            <?php if (!empty($errors['student_id'])): ?>
                                <span class="text-danger"><?= $errors['student_id']['required'] ?? '' ?></span>
                                <span class="text-danger"><?= $errors['student_id']['invalid'] ?? '' ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="course_id">Mã học phần</label>
                            <input type="text" name="course_id" class="form-control" id="course_id" value="<?= htmlspecialchars($old['course_id'] ?? '') ?>">
                            <?php if (!empty($errors['course_id'])): ?>
                                <span class="text-danger"><?= $errors['course_id']['required'] ?? '' ?></span>
                                <span class="text-danger"><?= $errors['course_id']['invalid'] ?? '' ?></span>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Đăng ký học phần</button>
                        <a href="?module=home&action=dashboard" class="btn btn-success btn-block">Quay lại</a>
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
