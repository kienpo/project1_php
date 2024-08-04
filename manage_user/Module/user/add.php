<!-- Đăng kí tài khoản -->
<!-- Xây dựng tính năng đăng nhập -->
<?php
if(!defined('_Code')){
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Đăng ký tài khoản'
];

// $kq = getRows('SELECT * FROM user');
 
if(isPost()){
    $filterAll = filter();
    $errors = [];

    // fullname validate
    if(empty($filterAll['fullname'])){
        $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
    }else{
        if(strlen($filterAll['fullname'] < 5)){
            $errors['fullname']['min'] = 'Họ tên phải có ít nhất 5 kí tự';  
        }
    }

    // email validate
    if(empty($filterAll['email'])){
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    }else{
        $email = $filterAll['email'];
        $sql = "SELECT id FRoM user WHERE email = '$email'";
        if(getRows($sql) > 0){
            $errors['email']['unique'] = 'Email đã tồn tại';
        }
    }

    // validate phone
    if(empty($filterAll['phone'])){
        $errors['phone']['required'] = 'Số điện thoại bắt buộc phải nhập';
    }else{
        if(!isPhone($filterAll['phone'])){
            $errors['phone']['isPhone'] = 'Số điện thoại không hợp lệ';
        }
    }

    // Validate password
    if(empty($filterAll['password'])){
        $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
    }else{
        if(strlen($filterAll['password'] < 8)){
            $errors['password']['min'] = 'Mật khẩu phải lớn hơn hoặc bằng 8';
        }
    }

    // validate confirm password
    if(empty($filterAll['confirm-password'])){
        $errors['confirm-password']['required'] = 'Bạn phải nhập lại mật khẩu';
    }else{
        if($filterAll['password'] != $filterAll['confirm-password']){
            $errors['confirm-password']['match'] = 'Mật khẩu bạn nhập lại không đúng';
        }
    }

    if(empty($errors)){

        $dataInsert = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
            'status' => $filterAll['status'],
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('user', $dataInsert);
        if($insertStatus){
                setFlashData('smg','Thêm người dùng mới thành công.');
                setFlashData('smg_type','success');
                redirect('?module=user&action=list');
        }else{
            setFlashData('smg','Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type','danger');
            redirect('?module=user&action=add');
        }
        
    }
    else{
        setFlashData('smg','Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type','danger');
        setFlashData('errors', $errors);
        setFlashData('old',$filterAll);
        redirect('?module=user&action=add');  
    }
}


layouts('header-login',$data);

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

// echo '<pre>';
// print_r($old);
// echo '</pre>';

?>

<div class="container">
    <div class="row" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">Thêm người dùng</h2>
        <?php
            if(!empty($smg)){
                getSmg($smg,$smg_type);
            }
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <div class="form-group mg-form">
                        <label for="" class="text-form-group">Họ tên</label>
                        <input name="fullname" type="fullname" placeholder="Họ tên" class="form-group" value="<?php 
                            echo old('fullname',$old);
                        ?>">
                        <?php
                            echo form_error('fullname', '<span class="error">', '</span>', $errors);
                        ?>
                    </div>
                    
                    <div class="form-group mg-form">
                        <label for="" class="text-form-group">Email</label>
                        <input name="email" type="email" placeholder="Địa chỉ email" class="form-group" value="<?php 
                            echo old('email',$old);
                        ?>">
                        <?php
                            echo form_error('email', '<span class="error">', '</span>', $errors);
                        ?>
                    </div>

                    <div class="form-group mg-form">
                        <label for="" class="text-form-group">Số điện thoại</label>
                        <input name="phone" type="sđt" placeholder="Số điện thoại" class="form-group" value="<?php 
                            echo old('phone',$old);
                        ?>">
                        <?php
                            echo form_error('phone', '<span class="error">', '</span>', $errors);
                        ?>
                    </div>
                </div>

                <div class="col">
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

                    <div class="form-group mg-form">
                        <label for="text-form-group">Trạng thái</label>
                        <select name="status" id="" class="form-control">
                            <option value="0" <?php echo (old('status', $old) == 0 ? 'select' : false) ?>>Chưa kích hoat</option>
                            <option value="1" <?php echo (old('status', $old) == 1 ? 'select' : false) ?>>Đã kích hoat</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="mg-btn-op btn btn-primary btn-block">Thêm người dùng</button>
            <a href="?module=user&action=list" class="mg-btn-op btn btn-success btn-block">Quay lại</a>
            <hr>
        </form>
    </div>
</div>

<?php
    layouts('footer-login')
?>