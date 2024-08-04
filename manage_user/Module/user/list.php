<?php
if(!defined('_Code')){
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Danh sách người dùng'
];

layouts('header',$data);


// Kiểm tra trạng thái đăng nhập
if(!isLogin()){
    redirect('?module=auth&action=login');
}

// truy vấn vào bảng user
$ListUser = getRaw("SELECT * FROM user ORDER BY update_at");

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
    <h2>Quản Lý Người Dùng</h2>
    <p>
        <a href="?module=user&action=add" class="btn btn-success btn-sm">Thêm người dùng <i class="fa-solid fa-plus"></i></a>
    </p>
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
            <th>Số điện thoại</th>
            <th>Trạng thái</th>
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
                <td><?php echo $item['fullname']; ?></td>
                <td><?php echo $item['email']; ?></td>
                <td><?php echo $item['phone']; ?></td>
                <td><?php echo $item['status'] == 1 ? '<button class="btn btn-success btn-sm">Đã kích hoat </button>' 
                : '<button class="btn btn-danger btn-sm">Chưa kích hoat </button>'; ?></td>
                <td><a href="<?php echo _WEB_HOST;?>?module=user&action=edit&id=<?php echo $item['id'];?>"class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
                <td><a href="<?php echo _WEB_HOST;?>?module=user&action=delete&id=<?php echo $item['id'];?>" onclick = "return confirm('Bạn có chắc muốn xóa?')" class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i></a></td>
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
