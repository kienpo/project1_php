<!-- kích hoat -->
<?php
if(!defined('_Code')){
    die('Access denied...');
}

layouts('header-login');

$token = filter()['token'];
if(!empty($token)){
    // truy vấn để kiểm tra token
    $tokenQuery = oneRaw("SELECT id FROM user WHERE activeToken ='$token'");
    if(!empty($tokenQuery)){
        $userId = $tokenQuery['id'];
        $dataUpdate = [
            'status' => 1,
            'activeToken' => null
        ];

        $updateStatus = update('user', $dataUpdate, "id=$userId");

        if($updateStatus){
            setFlashData('smg','Kích hoat tài khoản thành công, bạn có thể đăng nhập ngay bây giờ.');
            setFlashData('smg_type','success');
        }else{
            setFlashData('smg','Kích hoat tài khoản không thành công, vui lòng gọi ngay quản trị viên.');
            setFlashData('smg_type','danger');
        }

    }else{
        getSmg('Liên kết không tồn tại hoặc đã hết hạn!','danger');
    }
}else{
    getSmg('Liên kết không tồn tại hoặc đã hết hạn!','danger');
}


layouts('footer-login');
?>

<h1>ATIVE</h1>