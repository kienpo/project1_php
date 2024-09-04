<?php
if(!defined('_Code')){
    die('Access denied...');
}

// Lấy dữ liệu từ request
$filterAll = filter();

if(!empty($filterAll['id'])){
    $scoreId = $filterAll['id'];
    // Kiểm tra xem user có tồn tại hay không
    $scoreDetail = getRaw("SELECT * FROM student_courses WHERE id = $scoreId");

    if($scoreDetail){
        // Xóa người dùng
        $deleteScore = delete('student_courses', "id = $scoreId");
        if($deleteScore){
            setFlashData('smg', 'Xóa điểm thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('smg', 'Lỗi hệ thống.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('smg', 'Người dùng không tồn tại trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('smg', 'Liên kết không tồn tại.');
    setFlashData('msg_type', 'danger');
}

// Chuyển hướng về danh sách người dùng
redirect('?module=scoresheets&action=student_courses');

$regexResult = checkPrivilege();
if (!$regexResult){
    echo 'Bạn không có quyền truy cập';exit;
}

?>
