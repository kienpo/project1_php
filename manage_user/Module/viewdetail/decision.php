<?php
if (!defined('_Code')) {
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Thêm quyết định công nhận điểm mới'
];
layouts('header', $data);

if (!isLogin()) {
    redirect('?module=auth&action=login');
}

if (isPost()) {
    $filterAll = filter();
    $errors = [];

    // Validate decision_date
    if (empty($filterAll['decision_date'])) {
        $errors['decision_date']['required'] = 'Ngày quyết định bắt buộc phải nhập';
    } else {
        $date = DateTime::createFromFormat('Y-m-d', $filterAll['decision_date']);
        if (!$date || $date->format('Y-m-d') !== $filterAll['decision_date']) {
            $errors['decision_date']['invalid'] = 'Ngày quyết định không hợp lệ';
        }
    }

    // Validate decision_number
    if (empty($filterAll['decision_number'])) {
        $errors['decision_number']['required'] = 'Số quyết định bắt buộc phải nhập';
    }

    // Validate approved
    if (!isset($filterAll['approved'])) {
        $errors['approved']['required'] = 'Trạng thái phê duyệt bắt buộc phải nhập';
    } else {
        if ($filterAll['approved'] !== '0' && $filterAll['approved'] !== '1') {
            $errors['approved']['invalid'] = 'Trạng thái phê duyệt không hợp lệ';
        }
    }

    if (empty($errors)) {
        $dataInsert = [
            'decision_date' => $filterAll['decision_date'],
            'decision_number' => $filterAll['decision_number'],
            'approved' => (int)$filterAll['approved']
        ];

        $insertStatus = insert('decisions', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm quyết định công nhận điểm thành công.');
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
    <title>Thêm quyết định công nhận điểm mới</title>
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
                <li class="nav-item mb-3"><a class="nav-link text-white" href="?module=add_decision&action=add_decision">Thêm quyết định</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <div class="container">
                <div class="row" style="margin: 50px auto;">
                    <h2 class="text-center text-uppercase">Thêm quyết định công nhận điểm mới</h2>
                    <?php if (!empty($msg)): ?>
                        <div class="alert alert-<?= htmlspecialchars($msg_type) ?>">
                            <?= htmlspecialchars($msg) ?>
                        </div>
                    <?php endif; ?>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="decision_date">Ngày quyết định</label>
                            <input type="date" name="decision_date" class="form-control" id="decision_date" value="<?= htmlspecialchars($old['decision_date'] ?? '') ?>">
                            <?php if (!empty($errors['decision_date'])): ?>
                                <span class="text-danger"><?= $errors['decision_date']['required'] ?? '' ?></span>
                                <span class="text-danger"><?= $errors['decision_date']['invalid'] ?? '' ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="decision_number">Số quyết định</label>
                            <input type="text" name="decision_number" class="form-control" id="decision_number" value="<?= htmlspecialchars($old['decision_number'] ?? '') ?>">
                            <?php if (!empty($errors['decision_number'])): ?>
                                <span class="text-danger"><?= $errors['decision_number']['required'] ?? '' ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="approved">Trạng thái phê duyệt</label>
                            <select name="approved" class="form-control" id="approved">
                                <option value="1" <?= isset($old['approved']) && $old['approved'] === '1' ? 'selected' : '' ?>>Phê duyệt</option>
                                <option value="0" <?= isset($old['approved']) && $old['approved'] === '0' ? 'selected' : '' ?>>Không phê duyệt</option>
                            </select>
                            <?php if (!empty($errors['approved'])): ?>
                                <span class="text-danger"><?= $errors['approved']['required'] ?? '' ?></span>
                                <span class="text-danger"><?= $errors['approved']['invalid'] ?? '' ?></span>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Thêm quyết định</button>
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
