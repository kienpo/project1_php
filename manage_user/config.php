<!-- Các hằng của project -->

<?php
const _Module = 'home';
const _Action = 'dashboard';

const _Code = true;

// thiết lập host
define('_WEB_HOST','http://'. $_SERVER['HTTP_HOST'] .'/php/manage_user');

define('_WEB_HOST_TEMPLATE', _WEB_HOST.'/template');

// thiết lập path
define('_WEB_PATH', __DIR__);
define('_WEB_PATH_TEMPLATE', _WEB_PATH.'/template'); 

// <!-- thông tin kết nối -->
const _HOST = '127.0.0.1';
const _DB = 'kienpo';
const _USER = 'root';
const _PASS = '';
