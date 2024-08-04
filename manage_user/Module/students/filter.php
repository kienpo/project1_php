<?php
if (!defined('_Code')) {
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Lọc Danh Sách Sinh Viên Được Công Nhận Điểm'
];
layouts('header', $data);

if (!isLogin()) {
    redirect('?module=auth&action=login');
}

// Fetching filter and search parameters from the request
$filter = filter();
$search = $filter['search'] ?? '';
$decision_id = $filter['decision_id'] ?? '';
$class = $filter['class'] ?? '';
$department = $filter['department'] ?? '';
$course = $filter['course'] ?? '';
$sort = $filter['sort'] ?? 'student_name';
$order = $filter['order'] ?? 'ASC';

// Building the SQL query
$sql = "SELECT students.*, results.approved, decisions.decision_date
        FROM students 
        JOIN results ON students.student_id = results.student_id 
        JOIN decisions ON results.approved = decisions.approved
        WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (students.student_name LIKE '%$search%' OR students.student_code LIKE '%$search%')";
}

if (!empty($decision_id)) {
    $sql .= " AND results.approved = '$decision_id'";
}

if (!empty($class)) {
    $sql .= " AND students.class = '$class'";
}

if (!empty($department)) {
    $sql .= " AND students.department = '$department'";
}

if (!empty($course)) {
    $sql .= " AND results.course_id = '$course'";
}

$sql .= " ORDER BY $sort $order";

$students = getRaw($sql);

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['pageTitle'] ?></title>
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
                <li class="nav-item mb-3"><a class="nav-link text-white" href="?module=students&action=filter">Lọc danh sách sinh viên</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <div class="container">
                <div class="row" style="margin: 50px auto;">
                    <h2 class="text-center text-uppercase"><?= $data['pageTitle'] ?></h2>
                    <?php if (!empty($msg)): ?>
                        <div class="alert alert-<?= htmlspecialchars($msg_type) ?>">
                            <?= htmlspecialchars($msg) ?>
                        </div>
                    <?php endif; ?>
                    <form action="" method="get">
                        <input type="hidden" name="module" value="students">
                        <input type="hidden" name="action" value="filter">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="search">Tìm kiếm sinh viên</label>
                                <input type="text" name="search" class="form-control" id="search" value="<?= htmlspecialchars($search) ?>" placeholder="Tên hoặc mã sinh viên">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="decision_id">Quyết định công nhận</label>
                                <select name="decision_id" class="form-control" id="decision_id">
                                    <option value="">-- Chọn --</option>
                                    <!-- Assuming decisions table has decision_id and decision_name columns -->
                                    <?php foreach (getRaw("SELECT * FROM decisions") as $decision): ?>
                                        <option value="<?= $decision['decision_id'] ?>" <?= $decision_id == $decision['decision_id'] ? 'selected' : '' ?>>
                                            <?= $decision['decision_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="class">Lớp</label>
                                <input type="text" name="class" class="form-control" id="class" value="<?= htmlspecialchars($class) ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="department">Khoa</label>
                                <input type="text" name="department" class="form-control" id="department" value="<?= htmlspecialchars($department) ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="course">Khóa học</label>
                                <input type="text" name="course" class="form-control" id="course" value="<?= htmlspecialchars($course) ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="sort">Sắp xếp theo</label>
                                <select name="sort" class="form-control" id="sort">
                                    <option value="student_name" <?= $sort == 'student_name' ? 'selected' : '' ?>>Tên sinh viên</option>
                                    <option value="student_code" <?= $sort == 'student_code' ? 'selected' : '' ?>>Mã sinh viên</option>
                                    <option value="approved" <?= $sort == 'approved' ? 'selected' : '' ?>>Trạng thái công nhận</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="order">Thứ tự</label>
                                <select name="order" class="form-control" id="order">
                                    <option value="ASC" <?= $order == 'ASC' ? 'selected' : '' ?>>Tăng dần</option>
                                    <option value="DESC" <?= $order == 'DESC' ? 'selected' : '' ?>>Giảm dần</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Lọc</button>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mt-4">
                            <thead>
                                <tr>
                                    <th>Mã sinh viên</th>
                                    <th>Tên sinh viên</th>
                                    <th>Lớp</th>
                                    <th>Khoa</th>
                                    <th>Khóa học</th>
                                    <th>Điểm</th>
                                    <th>Quyết định công nhận</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($students)): ?>
                                    <?php foreach ($students as $student): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($student['student_code']) ?></td>
                                            <td><?= htmlspecialchars($student['student_name']) ?></td>
                                            <td><?= htmlspecialchars($student['class']) ?></td>
                                            <td><?= htmlspecialchars($student['department']) ?></td>
                                            <td><?= htmlspecialchars($student['course']) ?></td>
                                            <td><?= htmlspecialchars($student['grade']) ?></td>
                                            <td><?= htmlspecialchars($student['decision_name']) ?></td>
                                            <td><a href="?module=students&action=detail&id=<?= $student['student_id'] ?>" class="btn btn-info">Xem chi tiết</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Không tìm thấy sinh viên nào</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (!empty($students)): ?>
                        <div class="mt-4">
                            <strong>Tổng số sinh viên: <?= count($students) ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
layouts('footer');
?>
