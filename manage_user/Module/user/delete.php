<?php
if(!defined('_Code')){
    die('Access denied...');
}
// Kiểm tra id có tồn tại => tiến hành xóa
// Xóa dữ liệu bằng logintoken

$filterAll = filter();
if(!empty($filterAll['id'])){
    $userId = $filterAll['id'];
    $userDetail = getRaw("SELECT * FROM user WHERE id = $userId");

    if($userDetail > 0){
        // thực hiện xóa
        $deleteToken = delete('tokenlogin',"user_Id = $userId");
        if($deleteToken){
            // Xóa user
            $deleteUser = delete('user', "id = $userId");
            if($deleteUser){
                setFlashData('smg', 'Xóa người dùng thành công.');
                setFlashData('msg_type', 'success');
            }else{
                setFlashData('smg', 'Lỗi hệ thống.');
                setFlashData('msg_type', 'danger');
            }
        }
    }else{
        setFlashData('smg', 'Người dùng không tồn tại trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
}else{
    setFlashData('smg', 'Liên kết không tồn tại.');
    setFlashData('msg_type', 'danger');
}

// redirect('?module=user&action=list')
?>