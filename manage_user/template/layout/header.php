<?php
if(!defined('_Code')){
    die('Access denied...');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo !empty($data['pageTitle']) ? $data['pageTitle']: 'Quản lý người dùng' ?></title>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?> /css/style.css?ver=1">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?> /css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- <link rel="" href="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" > -->
    <!-- <script>
        document.querySelector('.logout-container').addEventListener('click', function() {
            window.location.href = '?module=auth&action=logOut';
        });
    </script> -->
    <style>
      .logoDashboard{
        margin-left: -20rem;
        margin-right: -18rem;
      }
    </style>
</head>
<body>
    
</body>
</html>

<header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start logoDashboard">
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="#" class="nav-link px-2 link-secondary fs-4 ">KT&DBCLGD</a></li>
          <span class="border-end mx-2" style="height: 2rem;margin-top: 10px;"></span>
          <li><a href="#" class="nav-link px-2 link-body-emphasis fs-4">Admin</a></li>
        </ul>

        <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
          <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
        </form>

        <div class="logout-container d-inline-flex align-items-center btn btn-danger ms-2">
          <i class="fa-solid fa-right-from-bracket"></i>
          <a href="?module=auth&action=logOut" class="text-white text-decoration-none ms-2">Đăng xuất</a>
        </div>
      </div>
    </div>
    
  </header>