<?php
if(!defined('_Code')){
    die('Access denied...');
}
// Kiểm tra id có tồn tại => tiến hành xóa
// Xóa dữ liệu bằng logintoken

$filterAll = filter();
if(!empty($filterAll['student_id'])){
    $studentId = $filterAll['student_id'];
    $studentsDetail = getRaw("SELECT * FROM students WHERE student_id = $studentId");

    if($studentsDetail > 0){
        // thực hiện xóa
        $deleteToken = delete('tokenstudents',"tokenStudents_Id = $studentId");
        if($deleteToken){
            // Xóa user
            $deleteStudent = delete('students', "student_id = $studentId");
            if($deleteStudent){
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