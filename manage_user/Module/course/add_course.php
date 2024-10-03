<?php
if (!defined('_Code')) {
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Thêm học phần mới'
];
layouts('header', $data);

// if (!isLogin()) {
//     redirect('?module=auth&action=login');
// }

if (isPost()) {
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

    if (empty($errors)) {
        $dataInsert = [
            'course_code' => $filterAll['course_code'],
            'course_name' => $filterAll['course_name'],
            'credits' => (int)$filterAll['credits'],
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('courses', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm học phần thành công.');
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

    redirect('?module=course&action=view_course');
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
    <title>Thêm học phần mới</title>

</head>

<body>
    <div class="container">
        <div class="row" style="margin: 50px auto;">
            <h2 class="text-center text-uppercase">Thêm học phần mới</h2>
            <?php if (!empty($msg)): ?>
                <div class="alert alert-<?= htmlspecialchars($msg_type) ?>">
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="course_name">Tên học phần</label>
                    <input type="text" name="course_name" class="form-control" id="course_name" value="<?= htmlspecialchars($old['course_name'] ?? '') ?>">
                    <?php if (!empty($errors['course_name'])): ?>
                        <span class="text-danger"><?= $errors['course_name']['required'] ?? '' ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="course_code">Mã học phần</label>
                    <input type="text" name="course_code" class="form-control" id="course_code" value="<?= htmlspecialchars($old['course_code'] ?? '') ?>">
                    <?php if (!empty($errors['course_code'])): ?>
                        <span class="text-danger"><?= $errors['course_code']['required'] ?? '' ?></span>
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
                <button type="submit" class="btn btn-primary btn-block fs-4">Thêm học phần</button>
                <a href="?module=course&action=view_course" class="btn btn-success btn-block fs-4">Quay lại</a>
                <hr>
            </form>
        </div>
    </div>

</body>

</html>

<?php
layouts('footer');
?>