<!-- Xây dựng tính năng đăng nhập -->
<?php
if (!defined('_Code')) {
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Đăng nhập tài khoản'
];
layouts('header-login', $data);

if (isLogin()) {
    redirect('?module=home&action=dashboard');
}

if (isPost()) {
    $filterAll = filter();
    if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
        // Kiểm tra đăng nhập
        $email = $filterAll['email'];
        $password = $filterAll['password'];

        // truy vấn lấy thông tin
        $userQuery = oneRaw("SELECT id, email, fullname, password FROM user WHERE email = '$email'");
        if (!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];
            $fullname = $userQuery['fullname'];
            if (password_verify($password, $passwordHash)) {

                // tạo token login
                $tokenLogin = sha1(uniqid() . time());

                // insert vào bảng tokenlogin
                $dataInsert = [
                    'user_id' => $userId,
                    'token' => $tokenLogin,
                    'create_at' => date('Y-m-d H:i:s')
                ];

                $insertStatus = insert('tokenlogin', $dataInsert);

                if ($insertStatus) {
                    // Lưu thông tin người dùng vào session
                    $userData = [
                        'user_id' => $userId,
                        'fullname' => $fullname,
                        'email' => $email,
                        'tokenlogin' => $tokenLogin,
                    ];
                    $userId = $userData['user_id'];
                    // $userPrivilege = mysqli_query($conn, "SELECT * FROM `user_privilege` INNER JOIN `privilege` ON user_privilege.privilege_id = privilege.id WHERE user_privilege.user_id = 11");
                    $stmt = $conn->prepare(
                        "SELECT * 
                        FROM `user_privilege` 
                        INNER JOIN `privilege` 
                        ON user_privilege.privilege_id = privilege.id 
                        WHERE user_privilege.user_id = :user_id"
                    );
                    $stmt->execute(['user_id' => $userId]);
                    $userPrivilege = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($userPrivilege)) {
                        $userData['privileges'] = array();
                        foreach ($userPrivilege as $privileges) {
                            $userData['privileges'][] = $privileges['url_match'];
                        }
                    };

                    // Lưu userData vào session
                    setSession('userData', $userData);
                    // $_SESSION['userData']['privileges'] = $userData;

                    // redirect('?module=home&action=dashboard');
                } else {
                    setFlashData('smg', 'Không thể đăng nhập, vui lòng đăng nhập lại sau.');
                    setFlashData('smg_type', 'danger');
                }
                redirect('?module=home&action=dashboard');
            } else {
                setFlashData('smg', 'Mật khẩu không chính xác.');
                setFlashData('smg_type', 'danger');
            }
        } else {
            setFlashData('smg', 'Email không tồn tại.');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', 'Vui lòng nhập email và mật khẩu.');
        setFlashData('smg_type', 'danger');
    }
    redirect('?module=auth&action=login');
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');

?>
<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <br><br>
        <!-- <h2 class="text-center text-uppercase">Đăng nhập Admin</h2> -->
        <div class="d-flex justify-content-center">
            <img src=" <?php echo _WEB_HOST_TEMPLATE; ?> /image/vku.png" alt="">
        </div>
        <br><br>
        <?php
        if (!empty($smg)) {
            getSmg($smg, $smg_type);
        }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <!-- <label for="" class="text-form-group">Email</label> -->
                <input name="email" type="email" placeholder="Địa chỉ email" class="form-group">
            </div>
            <div class="form-group mg-form">
                <!-- <label for="" class="text-form-group">PassWord</label> -->
                <input name="password" type="password" placeholder="Mật khẩu" class="form-group">
            </div>

            <button type="submit" class="mg-btn btn btn-primary btn-black fs-4">Đăng nhập</button>
            <hr>
            <p class="text-center fs-5"><a href="?module=auth&action=forgot" class="text-decoration-none">Quên mật khẩu</a></p>
            <p class="text-center fs-5"><a href="?module=auth&action=register" class="text-decoration-none">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>

<?php
layouts('footer-login')
?>