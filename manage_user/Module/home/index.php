<?php
if(!defined('_Code')){
    die('Access denied...');
}

$module = isset($_GET['module']) ? $_GET['module'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($module) {
    case 'students':
        switch ($action) {
            case 'add':
                include 'modules/students/add.php';
                break;
            case 'update':
                include 'modules/students/update.php';
                break;
            case 'delete':
                include 'modules/students/delete.php';
                break;
            case 'filter':
                include 'modules/students/filter.php';
                break;
            default:
                include 'modules/students/list.php';
                break;
        }
        break;
    case 'grades':
        switch ($action) {
            case 'approve':
                include 'modules/grades/approve.php';
                break;
            case 'update':
                include 'modules/grades/update.php';
                break;
            default:
                include 'modules/grades/list.php';
                break;
        }
        break;
    case 'scoresheets':
        switch ($action) {
            case 'print':
                include 'modules/scoresheets/print.php';
                break;
            default:
                include 'modules/scoresheets/list.php';
                break;
        }
        break;
    case 'access':
        switch ($action) {
            case 'manage':
                include 'modules/access/manage.php';
                break;
            default:
                include 'modules/access/list.php';
                break;
        }
        break;
    default:
        include 'modules/dashboard.php';
        break;
}
?>
