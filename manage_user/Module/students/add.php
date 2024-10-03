<?php
if (!defined('_Code')) {
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Thêm sinh viên'
];
layouts('header', $data);

// if(!isLogin()){
//     redirect('?module=auth&action=login');
// }

if (isPost()) {
    $filterAll = filter();
    $errors = [];

    // fullname validate
    if (empty($filterAll['student_name'])) {
        $errors['student_name']['required'] = 'Họ tên bắt buộc phải nhập';
    } else {
        if (strlen($filterAll['student_name']) < 5) {
            $errors['student_name']['min'] = 'Họ tên phải có ít nhất 5 kí tự';
        }
    }

    // email validate
    if (empty($filterAll['student_email'])) {
        $errors['student_email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        $email = $filterAll['student_email'];
        $sql = "SELECT student_id FRoM students WHERE student_email = '$email'";
        if (getRows($sql) > 0) {
            $errors['student_email']['unique'] = 'Email đã tồn tại';
        }
    }


    // Validate 
    if (empty($filterAll['student_code'])) {
        $errors['student_code']['required'] = 'Mã số sinh viên bắt buộc phải nhập';
    } else {
        if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]+$/', $filterAll['student_code'])) {
            $errors['student_code']['invalid'] = 'Mã số sinh viên phải chứa cả chữ và số';
        }
    }

    // Validate date
    if (empty($filterAll['date'])) {
        $errors['date']['required'] = 'Ngày sinh bắt buộc phải nhập';
    } else {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $filterAll['date'])) {
            $errors['date']['invalid'] = 'Ngày sinh phải có dạng: YYYY-MM-DD';
        }
    }

    if (empty($filterAll['department'])) {
        $errors['department']['required'] = 'Khoa bắt buộc phải nhập';
    } else {
        if (strlen($filterAll['department']) <= 5) {
            $errors['department']['min'] = 'Khoa phải có nhiều hơn 5 kí tự';
        }
    }

    if (empty($filterAll['course'])) {
        $errors['course']['required'] = 'Khóa học bắt buộc phải nhập';
    } else {
        if (!preg_match('/^\d{4}-\d{4}$/', $filterAll['course'])) {
            $errors['course']['invalid'] = 'Khóa học phải có dạng: YYYY-YYYY';
        }
    }


    if (empty($errors)) {

        $dataInsert = [
            'student_name' => $filterAll['student_name'],
            'student_email' => $filterAll['student_email'],
            'student_code' => $filterAll['student_code'],
            'date' => $filterAll['date'],
            'department' => $filterAll['department'],
            'course' => $filterAll['course'],
            'studentId' => $filterAll['studentId'],
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('students', $dataInsert);

        if ($insertStatus) {
            setFlashData('smg', 'Thêm sinh viên mới thành công.');
            setFlashData('smg_type', 'success');
            redirect('?module=students&action=view');
        } else {
            setFlashData('smg', 'Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type', 'danger');
            redirect('?module=students&action=add');
        }
    } else {
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $filterAll);
        redirect('?module=students&action=add');
    }
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
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
    <title>Document</title>

</head>

<body>
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
                        <div class="form-group mg-form">
                            <label for="studentId" class="text-form-group">Tài khoản người dùng</label>
                            <select name="studentId" class="form-control">
                                <option value="">Chọn tài khoản người dùng</option>
                                <?php
                                // Fetch the list of users from the user table
                                $userQuery = "SELECT id, fullname FROM user";
                                $stmt = $conn->prepare($userQuery); // Assuming `getRows` fetches the result as an array
                                $stmt->execute();
                                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($users as $user) {
                                    echo "<option value='" . $user['id'] . "'>" . $user['fullname'] . "</option>";
                                }
                                ?>
                            </select>
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

                <button type="submit" class="mg-btn-op btn btn-primary btn-block fs-4">Thêm sinh viên</button>
                <a href="?module=students&action=view" class="mg-btn-op btn btn-success btn-block fs-4">Quay lại</a>
                <hr>
            </form>
        </div>
    </div>
</body>

</html>

<?php
layouts('footer');
?>