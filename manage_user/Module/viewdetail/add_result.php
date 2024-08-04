<?php
if (!defined('_Code')) {
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Thêm kết quả học tập mới'
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
        if (!is_numeric($filterAll['student_id']) || $filterAll['student_id'] <= 0) {
            $errors['student_id']['invalid'] = 'Mã sinh viên phải là số dương';
        }
    }

    // Validate course_id
    if (empty($filterAll['course_id'])) {
        $errors['course_id']['required'] = 'Mã học phần bắt buộc phải nhập';
    } else {
        if (!is_numeric($filterAll['course_id']) || $filterAll['course_id'] <= 0) {
            $errors['course_id']['invalid'] = 'Mã học phần phải là số dương';
        }
    }

    // Validate marks
    if (empty($filterAll['marks'])) {
        $errors['marks']['required'] = 'Điểm số bắt buộc phải nhập';
    } else {
        if (!is_numeric($filterAll['marks']) || $filterAll['marks'] < 0 || $filterAll['marks'] > 10) {
            $errors['marks']['invalid'] = 'Điểm số phải là số từ 0 đến 10';
        }
    }

    // Validate approved
    if (!isset($filterAll['approved'])) {
        $errors['approved']['required'] = 'Trạng thái công nhận điểm bắt buộc phải nhập';
    } else {
        if ($filterAll['approved'] !== '0' && $filterAll['approved'] !== '1') {
            $errors['approved']['invalid'] = 'Trạng thái công nhận điểm không hợp lệ';
        }
    }

    if (empty($errors)) {
        $dataInsert = [
            'student_id' => (int)$filterAll['student_id'],
            'course_id' => (int)$filterAll['course_id'],
            'marks' => (float)$filterAll['marks'],
            'approved' => (int)$filterAll['approved']
        ];

        $insertStatus = insert('results', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm kết quả học tập thành công.');
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

    redirect('?module=viewdetail&action=view');
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
    <title>Thêm kết quả học tập mới</title>
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
                <li class="nav-item mb-3"><a class="nav-link text-white" href="?module=add_course&action=add_course">Thêm học phần</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <div class="container">
                <div class="row" style="margin: 50px auto;">
                    <h2 class="text-center text-uppercase">Thêm kết quả học tập mới</h2>
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
                        <div class="form-group">
                            <label for="marks">Điểm số</label>
                            <input type="number" name="marks" class="form-control" id="marks" step="0.01" value="<?= htmlspecialchars($old['marks'] ?? '') ?>">
                            <?php if (!empty($errors['marks'])): ?>
                                <span class="text-danger"><?= $errors['marks']['required'] ?? '' ?></span>
                                <span class="text-danger"><?= $errors['marks']['invalid'] ?? '' ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="approved">Trạng thái công nhận điểm</label>
                            <select name="approved" class="form-control" id="approved">
                                <option value="1" <?= isset($old['approved']) && $old['approved'] === '1' ? 'selected' : '' ?>>Phê duyệt</option>
                                <option value="0" <?= isset($old['approved']) && $old['approved'] === '0' ? 'selected' : '' ?>>Không phê duyệt</option>
                            </select>
                            <?php if (!empty($errors['approved'])): ?>
                                <span class="text-danger"><?= $errors['approved']['required'] ?? '' ?></span>
                                <span class="text-danger"><?= $errors['approved']['invalid'] ?? '' ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">Thêm kết quả</button>
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
