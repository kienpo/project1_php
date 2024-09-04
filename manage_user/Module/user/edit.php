<!-- Đăng kí tài khoản -->
<!-- Xây dựng tính năng đăng nhập -->
<?php
if(!defined('_Code')){
    die('Access denied...');
}
$filterAll = filter();
if(!empty($filterAll['id'])){
    $userId = $filterAll['id'];

    // Kiểm tra xem userId nó tòn tại trong database ko
    // Nếu tồn tại => Lấy thông tin người dùng
    // Nếu ko tồn tại => Chuyển sang trang list
    $userDetail = oneRaw("SELECT * FROM user WHERE id='$userId'");
    if(!empty($userDetail)){
        // tồn tại
        setFlashData('user-detail',$userDetail);
    }else{
        redirect('?module=user&action=list');
    }
}

$data = [
    'pageTitle' => 'Cập nhật tài khoản'
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
        $sql = "SELECT id FRoM user WHERE email = '$email' AND id <> $userId";
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

    if(!empty($filterAll['password'])){
        // Validate password
        if(empty($filterAll['password'])){
            $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
        }else{
            if(strlen($filterAll['password'] < 8)){
                $errors['password']['min'] = 'Mật khẩu phải lớn hơn hoặc bằng 8';
            }
        }
    }

    if(empty($errors)){

        $dataUpdate = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'status' => $filterAll['status'],
            'create_at' => date('Y-m-d H:i:s')
        ];

        if(!empty($filterAll['password'])){
            $dataUpdate['password'] = password_hash($filterAll['password'], PASSWORD_DEFAULT);
        }

        $condition = "id = $userId";
        $UpdateStatus = update('user', $dataUpdate, $condition);
        if($UpdateStatus){
                setFlashData('smg','Update người dùng thành công.');
                setFlashData('smg_type','success');
        }else{
            setFlashData('smg','Hệ thống đang lỗi vui lòng thử lại sau.');
            setFlashData('smg_type','danger');
        }
        
    }
    else{
        setFlashData('smg','Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type','danger');
        setFlashData('errors', $errors);
        setFlashData('old',$filterAll);
    }
    redirect('?module=user&action=edit&id=', $userId);  
}

layouts('header-login',$data);

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
$userDetailll = getFlashData('user-detail');

if(!empty($userDetailll)){
    $old = $userDetailll;
}

$regexResult = checkPrivilege();
if (!$regexResult){
    echo 'Bạn không có quyền truy cập';exit;
}

?>

<div class="container">
    <div class="row" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">Update người dùng</h2>
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
                        <label for="" class="text-form-group">Password</label>
                        <input name="password" type="password" placeholder="Mật khẩu (không nhập nếu không thay đổi mật khẩu)" class="form-group">
                        <?php
                            echo form_error('password', '<span class="error">', '</span>', $errors);
                        ?>
                    </div>

                    <div class="form-group mg-form">
                        <label for="" class="text-form-group">Repeat Password</label>
                        <input name="confirm-password" type="password" placeholder="Nhập lại mật khẩu (không nhập nếu không thay đổi mật khẩu)" class="form-group">
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
            
            <input type="hidden" name="id" value="<?php echo $userId ?>">
            <button type="submit" class="mg-btn-op btn btn-primary btn-block">Update người dùng</button>
            <a href="?module=user&action=list" class="mg-btn-op btn btn-success btn-block">Quay lại</a>
            <hr>
        </form>
    </div>
</div>

<?php
    layouts('footer-login')
?>