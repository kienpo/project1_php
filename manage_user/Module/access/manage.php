<?php
if(!defined('_Code')){
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Quản lý quyền truy cập'
];
layouts('header',$data);

if(!isLogin()){
    redirect('?module=auth&action=login');
}


?>



<?php
layouts('footer');
?>
