<?php
if (!defined('_Code')) {
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Thêm điểm học phần mới'
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
            $studentCheckQuery = "SELECT student_name FROM students WHERE student_id = student_id";
            $studentExists = oneRaw($studentCheckQuery, ['student_id' => $filterAll['student_Id']]);
            if (!$studentExists) {
                $errors['student_Id']['not_found'] = 'Mã sinh viên không tồn tại trong hệ thống';
            }else {
                // Retrieve the course name if it exists
                $studentName = $studentExists['student_name'];
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
    // Validate attendance_points
    if (isset($filterAll['attendance_points']) && (!is_numeric($filterAll['attendance_points']) || $filterAll['attendance_points'] < 0 || $filterAll['attendance_points'] > 10)) {
        $errors['attendance_points']['invalid'] = 'Điểm chuyên cần phải là số từ 0 đến 10';
    }

    // Validate exercise_points
    if (isset($filterAll['exercise_points']) && (!is_numeric($filterAll['exercise_points']) || $filterAll['exercise_points'] < 0 || $filterAll['exercise_points'] > 10)) {
        $errors['exercise_points']['invalid'] = 'Điểm bài tập phải là số từ 0 đến 10';
    }

    // Validate midterm_score
    if (isset($filterAll['midterm_score']) && (!is_numeric($filterAll['midterm_score']) || $filterAll['midterm_score'] < 0 || $filterAll['midterm_score'] > 10)) {
        $errors['midterm_score']['invalid'] = 'Điểm giữa kỳ phải là số từ 0 đến 10';
    }

    // Validate final_score
    if (isset($filterAll['final_score']) && (!is_numeric($filterAll['final_score']) || $filterAll['final_score'] < 0 || $filterAll['final_score'] > 10)) {
        $errors['final_score']['invalid'] = 'Điểm cuối kỳ phải là số từ 0 đến 10';
    }

    // Validate T10_point
    if (isset($filterAll['T10_point']) && (!is_numeric($filterAll['T10_point']) || $filterAll['T10_point'] < 0 || $filterAll['T10_point'] > 10)) {
        $errors['T10_point']['invalid'] = 'Điểm hệ số 10 phải là số từ 0 đến 10';
    }

    // Validate letter_grades
    if (!empty($filterAll['letter_grades']) && !preg_match('/^[A-F]{1}$/', $filterAll['letter_grades'])) {
        $errors['letter_grades']['invalid'] = 'Điểm chữ không hợp lệ';
    }

    if (empty($errors)) {
        $dataInsert = [
            'student_Id' => (int)$filterAll['student_Id'],
            'course_Id' => (int)$filterAll['course_Id'],
            'attendance_points' => isset($filterAll['attendance_points']) ? (float)$filterAll['attendance_points'] : null,
            'exercise_points' => isset($filterAll['exercise_points']) ? (float)$filterAll['exercise_points'] : null,
            'midterm_score' => isset($filterAll['midterm_score']) ? (float)$filterAll['midterm_score'] : null,
            'final_score' => isset($filterAll['final_score']) ? (float)$filterAll['final_score'] : null,
            'T10_point' => isset($filterAll['T10_point']) ? (float)$filterAll['T10_point'] : null,
            'letter_grades' => $filterAll['letter_grades'] ?? null,
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('student_courses', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm điểm học tập thành công.');
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
    redirect('?module=scoresheets&action=student_courses');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

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
    <title>Thêm điểm học tập mới</title>
</head>
<body>
    <div class="container">
        <h2>Thêm điểm học tập mới</h2>
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
                <label for="attendance_points">Điểm chuyên cần</label>
                <input type="number" name="attendance_points" class="form-control" id="attendance_points" step="0.01" value="<?= htmlspecialchars($old['attendance_points'] ?? '') ?>">
                <?php if (!empty($errors['attendance_points'])): ?>
                    <span class="text-danger"><?= $errors['attendance_points']['invalid'] ?? '' ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="exercise_points">Điểm bài tập</label>
                <input type="number" name="exercise_points" class="form-control" id="exercise_points" step="0.01" value="<?= htmlspecialchars($old['exercise_points'] ?? '') ?>">
                <?php if (!empty($errors['exercise_points'])): ?>
                    <span class="text-danger"><?= $errors['exercise_points']['invalid'] ?? '' ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="midterm_score">Điểm giữa kỳ</label>
                <input type="number" name="midterm_score" class="form-control" id="midterm_score" step="0.01" value="<?= htmlspecialchars($old['midterm_score'] ?? '') ?>">
                <?php if (!empty($errors['midterm_score'])): ?>
                    <span class="text-danger"><?= $errors['midterm_score']['invalid'] ?? '' ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="final_score">Điểm cuối kỳ</label>
                <input type="number" name="final_score" class="form-control" id="final_score" step="0.01" value="<?= htmlspecialchars($old['final_score'] ?? '') ?>">
                <?php if (!empty($errors['final_score'])): ?>
                    <span class="text-danger"><?= $errors['final_score']['invalid'] ?? '' ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="T10_point">Điểm hệ số 10</label>
                <input type="number" name="T10_point" class="form-control" id="T10_point" step="0.01" value="<?= htmlspecialchars($old['T10_point'] ?? '') ?>">
                <?php if (!empty($errors['T10_point'])): ?>
                    <span class="text-danger"><?= $errors['T10_point']['invalid'] ?? '' ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="letter_grades">Điểm chữ</label>
                <input type="text" name="letter_grades" class="form-control" id="letter_grades" value="<?= htmlspecialchars($old['letter_grades'] ?? '') ?>">
                <?php if (!empty($errors['letter_grades'])): ?>
                    <span class="text-danger"><?= $errors['letter_grades']['invalid'] ?? '' ?></span>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
        </form>
    </div>
</body>
</html>
