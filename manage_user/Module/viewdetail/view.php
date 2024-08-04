<?php
if(!defined('_Code')){
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Danh sách sinh viên'
];

layouts('header',$data);


// Kiểm tra trạng thái đăng nhập
if(!isLogin()){
    redirect('?module=auth&action=login');
}

// truy vấn vào bảng user
$ListUser = getRaw("SELECT * FROM students ORDER BY update_at");

// echo '<pre>';
// print_r($ListUser);
// echo '</pre>'

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
// $errors = getFlashData('errors');
// $old = getFlashData('old');

?>

<div class="container">
    <hr>
    <h2>Quản Lý Sinh Viên</h2>
    <?php
            if(!empty($smg)){
                getSmg($smg,$smg_type);
            }
        ?>
    <table class="table table-bodered">
        <thead>
            <th>Stt</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Mã số sinh viên</th>
            <th>Ngày sinh</th>
            <th>Khoa</th>
            <th>Khóa học</th>
            <th width = "5%">Sửa</th>
            <th width = "5%">Xóa</th>
        </thead>
        <tbody>
            <?php
                if(!empty($ListUser)):
                    $count = 0;
                    foreach($ListUser as $item):
                        $count++;
            ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $item['student_name']; ?></td>
                <td><?php echo $item['student_email']; ?></td>
                <td><?php echo $item['student_code']; ?></td>
                <td><?php echo $item['date']; ?></td>
                <td><?php echo $item['department']; ?></td>
                <td><?php echo $item['course']; ?></td>        
                <td><a href="<?php echo _WEB_HOST;?>?module=students&action=update&student_id=<?php echo $item['student_id'];?>"class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
                <td><a href="<?php echo _WEB_HOST;?>?module=viewdetail&action=view<?php echo $item['student_id'];?>" onclick = "return confirm('Bạn có chắc muốn xóa?')" class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i></a></td>
            </tr>    
            <?php
                    endforeach;
                else:
                ?>
                    <tr>
                        <td colspan="7">
                            <div class="alert alert-danger text-center">Không có người dùng nào</div>
                        </td>
                    </tr>
                <?php    
                endif;
            ?>
        </tbody>
    </table>
</div>

<?php
layouts('footer');
?>
