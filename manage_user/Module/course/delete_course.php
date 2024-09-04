<?php
if(!defined('_Code')){
    die('Access denied...');
}

// Kiểm tra id có tồn tại => tiến hành xóa
// Xóa dữ liệu bằng logintoken

$filterAll = filter();
if(!empty($filterAll['course_id'])){
    $courseId = $filterAll['course_id'];
    $courseDetail = getRaw("SELECT * FROM courses WHERE course_id = $courseId");

    if($courseDetail > 0){
        // thực hiện xóa
        $deleteToken = delete('tokencourse',"course_Id = $courseId");
        if($deleteToken){
            // Xóa học phần
            $deleteCourse = delete('courses', "course_id = $courseId");
            if($deleteCourse){
                setFlashData('smg', 'Xóa học phần thành công.');
                setFlashData('msg_type', 'success');
            }else{
                setFlashData('smg', 'Lỗi hệ thống.');
                setFlashData('msg_type', 'danger');
            }
        }
    }else{
        setFlashData('smg', 'Học phần không tồn tại trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
}else{
    setFlashData('smg', 'Liên kết không tồn tại.');
    setFlashData('msg_type', 'danger');
}

redirect('?module=course&action=view_course');

$regexResult = checkPrivilege();
if (!$regexResult){
    echo 'Bạn không có quyền truy cập';exit;
}
?>