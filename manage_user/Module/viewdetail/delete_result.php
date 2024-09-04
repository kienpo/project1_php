<?php
if(!defined('_Code')){
    die('Access denied...');
}

// Lấy dữ liệu từ request
$filterAll = filter();

if(!empty($filterAll['result_id'])){
    $resultId = $filterAll['result_id'];
    // Kiểm tra xem kết quả có tồn tại hay không
    $resultDetail = getRaw("SELECT * FROM results WHERE result_id = $resultId");

    if($resultDetail){
        // Xóa kết quả công nhận điểm
        $deleteResult = delete('results', "result_id = $resultId");
        if($deleteResult){
            setFlashData('smg', 'Xóa kết quả thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('smg', 'Lỗi hệ thống.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('smg', 'Kết quả không tồn tại trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('smg', 'Liên kết không tồn tại.');
    setFlashData('msg_type', 'danger');
}

// Chuyển hướng về danh sách người dùng
redirect('?module=viewdetail&action=view_result');

$regexResult = checkPrivilege();
if (!$regexResult){
    echo 'Bạn không có quyền truy cập';exit;
}
?>
