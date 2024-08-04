<?php
if(!defined('_Code')){
    die('Access denied...');
}

layouts('header-login');

$token = filter()['token'];

if(!empty($token)){
    // truy vấn data kiểm tra dữ liệu
    $tokenQuery = oneRaw("SELECT id, fullname, email FROM user WHERE forgotToken='$token'");

    if(!empty($tokenQuery)){
        
        ?>
        <!-- Form đặt lại mật khẩu -->
        <div class="row">
            <div class="col-4" style="margin: 50px auto;">
                <h2 class="text-center text-uppercase">Đặt lại mật khẩu</h2>
                <?php
                    if(!empty($smg)){
                        getSmg($smg,$smg_type);
                    }
                ?>
                <form action="" method="post">
                    <div class="form-group mg-form">
                        <label for="" class="text-form-group">PassWord</label>
                        <input name="password" type="password" placeholder="Mật khẩu" class="form-group">
                        <?php
                            echo form_error('password', '<span class="error">', '</span>', $errors);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="" class="text-form-group">Repeat PassWord</label>
                        <input name="confirm-password" type="password" placeholder="Nhập lại mật khẩu" class="form-group">
                        <?php
                            echo form_error('confirm-password', '<span class="error">', '</span>', $errors);
                        ?>
                    </div>
                    <button type="submit" class="mg-btn btn btn-primary btn-black fs-4">Gửi</button>
                    <hr>
                    <p class="text-center fs-5"><a href="?module=auth&action=login" class="text-decoration-none">Đăng nhập tài khoản</a></p>
                </form>
            </div>
        </div>
        <?php
    }else{
        getSmg('Liên kết  không tồn tại hoặc đã hết hạn.', 'danger');
    }

}else{
    getSmg('Liên kết  không tồn tại hoặc đã hết hạn.', 'danger');
}

layouts('footer-login');
?>