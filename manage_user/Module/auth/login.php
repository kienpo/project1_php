<!-- Xây dựng tính năng đăng nhập -->
<?php
if(!defined('_Code')){
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Đăng nhập tài khoản'
];

layouts('header-login',$data);


// if(isLogin()){
//     redirect('?module=home&action=dashboard');
// }

if(isPost()){
    $filterAll = filter();
    if(!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))){
        // Kiểm tra đăng nhập
        $email = $filterAll['email'];
        $password = $filterAll['password'];

        // truy vấn lấy thông tin
        $userQuery = oneRaw("SELECT password, id FROM user WHERE email = '$email'");
        if(!empty($userQuery)){
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];
            if(password_verify($password, $passwordHash)){
                
                // tạo token login
                $tokenLogin = sha1(uniqid(). time());

                // insert vào bảng tokenlogin
                $dataInsert = [
                    'user_id' => $userId,
                    'token' => $tokenLogin,
                    'create_at' => date('Y-m-d H:i:s')
                ];

                $insertStatus = insert('tokenlogin', $dataInsert);

                if($insertStatus){
                    // insert thành công
                    // Lưu cái tokenlogin vào session
                    setSession('tokenlogin', $tokenLogin);

                    redirect('?module=home&action=dashboard');
                }else{
                    setFlashData('smg', 'Không thể đăng nhập, vui lòng gọi lại sau.');
                    setFlashData('smg_type', 'danger');
                }

                redirect('?module=home&action=dashboard');
            }else{
                setFlashData('smg', 'Mật khẩu không chính xác.');
                setFlashData('smg_type', 'danger');
            }

        }else{
            setFlashData('smg', 'Email không tồn tại.');
            setFlashData('smg_type', 'danger');
        }


    }else{
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
        <h2 class="text-center text-uppercase">Đăng nhập Admin</h2>
        <?php
            if(!empty($smg)){
                getSmg($smg, $smg_type);
            }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="" class="text-form-group">Email</label>
                <input name="email" type="email" placeholder="Địa chỉ email" class="form-group">
            </div>
            <div class="form-group mg-form">
                <label for="" class="text-form-group">PassWord</label>
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