<?php
if (!defined('_Code')) {
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Thêm kết quả học tập mới'
];
layouts('header', $data);

// if (!isLogin()) {
//     redirect('?module=auth&action=login');
// }

if (isPost()) {
    $filterAll = filter();
    $errors = [];

    // Validate student_Id
    if (empty($filterAll['student_Id'])) {
        $errors['student_Id']['required'] = 'Mã sinh viên bắt buộc phải nhập';
    } else {
        if (!is_numeric($filterAll['student_Id']) || $filterAll['student_Id'] <= 0) {
            $errors['student_Id']['invalid'] = 'Mã sinh viên phải là số dương';
        } else {
            // Check if student_Id exists in the students table
            $studentCheckQuery = "SELECT * FROM students WHERE student_id = student_id";
            $studentExists = oneRaw($studentCheckQuery, ['student_id' => $filterAll['student_Id']]);
            if (!$studentExists) {
                $errors['student_Id']['not_found'] = 'Mã sinh viên không tồn tại trong hệ thống';
            }
        }
    }

    // Validate course_Id
    if (empty($filterAll['course_Id'])) {
        $errors['course_Id']['required'] = 'Mã học phần bắt buộc phải nhập';
    } else {
        if (!is_numeric($filterAll['course_Id']) || $filterAll['course_Id'] <= 0) {
            $errors['course_Id']['invalid'] = 'Mã học phần phải là số dương';
        } else {
            // Check if course_Id exists in the courses table
            $courseCheckQuery = "SELECT course_name FROM courses WHERE course_id = course_id";
            $courseExists = oneRaw($courseCheckQuery, ['course_id' => $filterAll['course_Id']]);
            if (!$courseExists) {
                $errors['course_Id']['not_found'] = 'Mã học phần không tồn tại trong hệ thống';
            } else {
                // Retrieve the course name if it exists
                $courseName = $courseExists['course_name'];
            }
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
            'student_Id' => (int)$filterAll['student_Id'],
            'course_Id' => (int)$filterAll['course_Id'],
            'marks' => (float)$filterAll['marks'],
            'approved' => (int)$filterAll['approved'],
            'create_at' => date('Y-m-d H:i:s')
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

    redirect('?module=viewdetail&action=view_result');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

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
    <title>Thêm kết quả học tập mới</title>

</head>

<body>
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
                    <label for="student_Id">Mã sinh viên</label>
                    <input type="text" name="student_Id" class="form-control" id="student_Id" value="<?= htmlspecialchars($old['student_Id'] ?? '') ?>">
                    <?php if (!empty($errors['student_Id'])): ?>
                        <span class="text-danger"><?= $errors['student_Id']['required'] ?? '' ?></span>
                        <span class="text-danger"><?= $errors['student_Id']['invalid'] ?? '' ?></span>
                        <span class="text-danger"><?= $errors['student_Id']['not_found'] ?? '' ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="course_Id">Mã học phần</label>
                    <input type="text" name="course_Id" class="form-control" id="course_Id" value="<?= htmlspecialchars($old['course_Id'] ?? '') ?>">
                    <?php if (!empty($errors['course_Id'])): ?>
                        <span class="text-danger"><?= $errors['course_Id']['required'] ?? '' ?></span>
                        <span class="text-danger"><?= $errors['course_Id']['invalid'] ?? '' ?></span>
                        <span class="text-danger"><?= $errors['course_Id']['not_found'] ?? '' ?></span>
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
                        <option value="0" <?= isset($old['approved']) && $old['approved'] === '0' ? 'selected' : '' ?>>Chưa phê duyệt</option>
                    </select>
                    <?php if (!empty($errors['approved'])): ?>
                        <span class="text-danger"><?= $errors['approved']['required'] ?? '' ?></span>
                        <span class="text-danger"><?= $errors['approved']['invalid'] ?? '' ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary btn-block fs-4">Thêm kết quả</button>
                <a href="?module=viewdetail&action=view_result" class="btn btn-success btn-block fs-4">Quay lại</a>
                <hr>
            </form>
        </div>
    </div>
</body>

</html>

<?php
layouts('footer');
?>