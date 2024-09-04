<?php
// function checkPrivilege(){
//     $privileges = array(
//         "\?module=students&action=add",
//         "\?module=scoresheets&action=student_courses",
//         "\?module=scoresheets&action=print",
//         '\?module=scoresheets&action=edit_scoresheets&id=[0-9]*',  // [0-9]* cho phép id có thể có số hoặc không
//         '\?module=scoresheets&action=delete_score&id=[0-9]*', 
//     );
//     $privileges = implode("|", $privileges);
//     preg_match('/'.$privileges.'/', 'http://localhost/php/manage_user/?module=scoresheets&action=edit_scoresheets&id=', $matches);
//     return !empty($matches);
// }
// $regResult = checkPrivilege();
// var_dump($regResult);exit;
?>