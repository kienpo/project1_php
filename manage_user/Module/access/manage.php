<?php
if(!defined('_Code')){
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Quản lý quyền truy cập'
];
layouts('header',$data);

if(!isLogin()){
    redirect('?module=auth&action=login');
}

// Fetch user accounts
// $users = fetchAllUsers(); // Implement this function

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle access rights updates
    $userId = $_POST['user_id'];
    $role = $_POST['role'];
    // Update user role in database
    // updateUserRole($userId, $role); // Implement this function
}
?>

<h1>Quản lý quyền truy cập</h1>
<form method="POST">
    <label for="user_id">Chọn người dùng:</label><br>
    <select id="user_id" name="user_id">
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
        <?php endforeach; ?>
    </select><br>
    
    <label for="role">Vai trò:</label><br>
    <select id="role" name="role">
        <option value="student">Sinh viên</option>
        <option value="lecturer">Giảng viên</option>
        <option value="admin">Quản trị viên</option>
    </select><br>
    
    <input type="submit" value="Cập nhật quyền truy cập">
</form>

<?php
layouts('footer');
?>
