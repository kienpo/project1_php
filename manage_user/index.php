<?php
session_start();
require_once('config.php');
require_once('./include/connect.php');

// excel
require_once('./include/vendor/autoload.php');

// thư viện phpmailer
require_once('./include/phpmailer/Exception.php');
require_once('./include/phpmailer/PHPMailer.php');
require_once('./include/phpmailer/SMTP.php');

require_once('./include/functions.php');
require_once('./include/database.php');
require_once('./include/session.php');

// setFlashData('msg','Cài đặt thành công!');
// echo getFlashData('msg');

$subject='kienpo marketing check mail';
$content='Nội dụng của email!!!';
sendMail('kienhungdung100702@gmail.com',$subject,$content);

$module = _Module;
$action = _Action;

if(!empty($_GET['module'])){
    if(is_string($_GET['module'])){
        $module = trim($_GET['module']);
    }
}

if(!empty($_GET['action'])){
    if(is_string($_GET['action'])){
        $action = trim($_GET['action']);
    }
}


$path = 'module/'. $module.'/' . $action .'.php';
if(file_exists($path)){
    require_once ($path);
}else{
    require_once 'Module/error/404.php';
}

