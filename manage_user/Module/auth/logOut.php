<!-- đăng xuất -->
<?php
if(!defined('_Code')){
    die('Access denied...');
}

if(isLogin()){
    $token = getSession('tokenlogin');
    delete('tokenlogin', "token = '$token'");
    removeSession('tokenlogin');
    redirect('?module=auth&action=login');
}
?>
