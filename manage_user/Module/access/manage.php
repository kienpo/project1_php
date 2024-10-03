<?php
if (!defined('_Code')) {
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Quản lý quyền truy cập'
];
layouts('header', $data);

// if(!isLogin()){
//     redirect('?module=auth&action=login');
// }

$regexResult = checkPrivilege();
if (!$regexResult) {
    echo 'Bạn không có quyền truy cập';
    exit;
}

?>
<style>
    /* General Sidebar Styling */
    .flex-shrink-0 {
        height: 100vh;
        border-right: 1px solid #dee2e6;
    }

    .btn-toggle {
        font-weight: 600;
        color: #495057;
        padding: 0.75rem 1rem;
        width: 100%;
        text-align: left;
        font-size: 1.5rem;
    }

    .btn-toggle:hover,
    .btn-toggle:focus {
        background-color: #ced4da;
        color: #212529;
    }

    .btn-toggle-nav a {
        padding: 0.5rem 1.5rem;
        color: #495057;
        transition: color 0.2s ease;
        font-size: 1.3rem;
    }

    .btn-toggle-nav a:hover {
        color: #007bff;
    }

    .btn-toggle-nav a.active {
        background-color: #007bff;
        color: #fff;
    }

    .collapse.show {
        background-color: #f1f3f5;
    }

    .collapse {
        padding-left: 0.75rem;
    }

    .list-unstyled {
        padding-left: 0;
    }

    .border-bottom {
        border-bottom: 1px solid #dee2e6;
    }

    .link-body-emphasis {
        color: #212529;
    }

    .link-body-emphasis:hover {
        color: #007bff;
        text-decoration: none;
    }

    #sidebar.show {
        display: block;
    }

    .menu-icon {
        font-size: 1.8rem;
        cursor: pointer;
    }
</style>

<body>
    <div class="container-fluid d-flex">
        <div class="menu-icon" style="margin-top: -16px;">
            <i class="fa-solid fa-bars" id="menu-icon"></i>
        </div>
        <div class="flex-shrink-0 p-3 sidebar" id="sidebar" style="width: 280px; margin-top: -16px;">
            <ul class="list-unstyled ps-0">
                <li class="mb-1 pb-2">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                        Trang home
                    </button>
                    <div class="collapse" id="home-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="?module=home&action=dashboard" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Dashboard</a></li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1 pb-2">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                        Quản lý tài khoản
                    </button>
                    <div class="collapse" id="account-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="?module=user&action=list" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Trang người dùng</a></li>
                            <li><a href="?module=user&action=add" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Thêm người dùng</a></li>
                            <li><a href="?module=user&action=edit" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cập nhật người dùng</a></li>
                            <li><a href="?module=user&action=delete" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Xóa người dùng</a></li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1 pb-2">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#student-collapse" aria-expanded="false">
                        Quản lý sinh viên
                    </button>
                    <div class="collapse" id="student-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="?module=students&action=view" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Trang sinh viên</a></li>
                            <li><a href="?module=students&action=add" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Thêm sinh viên</a></li>
                            <li><a href="?module=students&action=update" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cập nhật sinh viên</a></li>
                            <li><a href="?module=students&action=delete" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Xóa sinh viên</a></li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1 pb-2">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#section-collapse" aria-expanded="false">
                        Quản lý học phần
                    </button>
                    <div class="collapse" id="section-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="?module=course&action=view_course" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Trang học phần</a></li>
                            <li><a href="?module=course&action=add_course" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Thêm học phần</a></li>
                            <li><a href="?module=course&action=update_course" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cập nhật học phần</a></li>
                            <li><a href="?module=course&action=delete_course" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Xóa học phần</a></li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1 pb-2">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#transcript-collapse" aria-expanded="false">
                        Quản lý bảng điểm
                    </button>
                    <div class="collapse" id="transcript-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="?module=scoresheets&action=student_courses" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Trang bảng điểm</a></li>
                            <li><a href="?module=scoresheets&action=add_score" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Thêm bảng điểm</a></li>
                            <li><a href="?module=scoresheets&action=edit_scoresheets" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cập nhật bảng điểm</a></li>
                            <li><a href="?module=scoresheets&action=delete_score" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Xóa bảng điểm</a></li>
                            <li><a href="?module=scoresheets&action=print" class="link-body-emphasis d-inline-flex text-decoration-none rounded">In bảng điểm</a></li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#recognize-collapse" aria-expanded="false">
                        Kết quả công nhận điểm
                    </button>
                    <div class="collapse" id="recognize-collapse" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="?module=viewdetail&action=view_result" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Trang kết quả công nhận điểm</a></li>
                            <li><a href="?module=viewdetail&action=add_result" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Thêm kết quả công nhận điểm</a></li>
                            <li><a href="?module=viewdetail&action=edit_result" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cập nhật kết quả công nhận điểm</a></li>
                            <li><a href="?module=viewdetail&action=delete_result" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Xóa kết quả công nhận điểm</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

        <div class="container main-content py-4 border border-secondary-subtle rounded-4 bg-light">
            <h2 class="mb-4">Phân quyền quản trị</h2>
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['user_id'])) {
                $data = $_POST;

                $InsertString = "";
                $stmt = $conn->prepare("DELETE FROM user_privilege WHERE `user_id` = " . $data['user_id']);
                $deleteOldPrivilege = $stmt->execute();

                foreach ($data['privilege'] as $Insert_privilege) {
                    $InsertString .= !empty($InsertString) ? "," : "";
                    $InsertString .= "(NULL, " . $data['user_id'] . ", " . $Insert_privilege . ", '2024-09-05 18:20:25.000000', '2024-09-05 18:20:25.000000')";
                }

                $stmt = $conn->prepare("INSERT INTO `user_privilege` (`id`, `user_id`, `privilege_id`, `created_at`, `updated_at`) VALUES " . $InsertString);
                $Insert_privilege = $stmt->execute();

                if (!$Insert_privilege) {
                    $error = "Phân quyền không thành công. Vui lòng thử lại!";
                }
            ?>
                <?php if (!empty($error)) { ?>
                    echo $error;
                <?php } else { ?>
                    <span class="fs-4">Phân quyền thành công. <a href="?module=user&action=list" class="text-decoration-none fs-4">Quay lại danh sách người dùng</a></span>
                <?php } ?>
            <?php } else { ?>
                <?php
                $stmt = $conn->prepare("SELECT * FROM `privilege`");
                $stmt->execute();
                $privileges = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt = $conn->prepare("SELECT * FROM `privilege_group` ORDER BY `privilege_group`.`position` ASC");
                $stmt->execute();
                $privilege_Group = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt = $conn->prepare("SELECT * FROM `user_privilege` WHERE `user_id` = " . $_GET['id']);
                $stmt->execute();
                $Current_privileges = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $Current_privileges_List = array();
                if (!empty($Current_privileges)) {
                    foreach ($Current_privileges as $Current_privilege) {
                        $Current_privileges_List[] = $Current_privilege['privilege_id'];
                    }
                }
                ?>
                <hr>
                <div id="content-box">
                    <form action="?module=access&action=manage&save" method="post" class="form-group fs-4">
                        <div class="d-flex justify-content-between align-items-center flex-row-reverse">
                            <?php if (checkPrivilege('\?module=access&action=manage&save')) { ?>
                                <input type="submit" class="btn btn-primary fs-5" title="lưu quyền" />
                            <?php } ?>
                        </div>
                        <?php if (checkPrivilege('\?module=access&action=manage&id=[0-9]*')) { ?>
                            <input type="hidden" name="user_id" value="<?= $_GET['id'] ?>">
                        <?php } ?>

                        <?php foreach ($privilege_Group as $group) { ?>
                            <div class="group-name">
                                <h3 class="mb-3"><?= $group['name'] ?></h3>
                                <ul class="list-unstyled d-flex justify-content-between">
                                    <?php foreach ($privileges as $privilege) { ?>
                                        <?php if ($privilege['group_id'] == $group['id']) { ?>
                                            <li class="form-check">
                                                <input type="checkbox"
                                                    <?php if (in_array($privilege['id'], $Current_privileges_List)) { ?>
                                                    checked=""
                                                    <?php } ?>
                                                    value="<?= $privilege['id'] ?>" id="privilege_<?= $privilege['id'] ?>" name="privilege[]" class="form-check-input">
                                                <label for="privilege_<?= $privilege['id'] ?>" class="form-check-label"><?= $privilege['name'] ?></label>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
    <script>
        document.getElementById('menu-icon').addEventListener('click', function() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapse');
        });
    </script>
</body>

<?php
layouts('footer');
?>