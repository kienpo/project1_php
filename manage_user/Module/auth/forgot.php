<!-- Xây dựng tính năng đăng nhập -->
<?php
if(!defined('_Code')){
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Quên mật khẩu'
];

layouts('header-login',$data);


// if(isLogin()){
//     redirect('?module=home&action=dashboard');
// }

if(isPost()){
   $filterAll = filter();
   if(!empty($filterAll['email'])){
        $email = $filterAll['email'];

        $queryUser = oneRaw("SELECT id FROM user WHERE email = '$email'");
        if(!empty($queryUser)){
            $userId = $queryUser['id'];

            // tạo forgottoken
            $forgotToken = sha1(uniqid().time());
            
            $dataUpdate = [
                'forgotToken' => $forgotToken
            ];

            $dataStatus = update('user', $dataUpdate, "id=$userId");

            if($updateStatus){
                // tạo cái link reset
                $linkReset = _WEB_HOST.'?module=auth&action=reset&token='.$forgotToken;

                // Gửi mail cho ngừi dùng
                $subject = 'Yêu cầu khôi phục mật khẩu.';
                $content = 'Chào bạn.</br>';
                $content .= 'Chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn. 
                Vui lòng click link sau để gửi lại mật khẩu: </br>';
                $content .= $linkReset. '</br>';
                $content .= 'Trân trọng cảm ơn';

                $sendMail = sendMail($email,$subject,$content);
                // var_dump($sendMail);
                // die();
                if($sendMail){
                    setFlashData('smg','Vui lòng kiểm tra email để xem hướng dẫn đặt lại mật khẩu!');
                    setFlashData('smg_type', 'success');
                }else{
                    setFlashData('smg','Lỗi hệ thống vui lòng thử lại sau!(email)');
                    setFlashData('smg_type', 'danger');
                }
            }else{
                setFlashData('smg','Lỗi hệ thống vui lòng thử lại sau!');
                setFlashData('smg_type', 'danger');
            }

        }else{
            setFlashData('smg','Địa chỉ email không tồn tại trong hệ thống!');
            setFlashData('smg_type', 'danger');
        }

    }else{
          setFlashData('smg','Vui lòng nhập địa chỉ email');
          setFlashData('smg_type', 'danger');
    }

//    redirect('?module=auth&action=forgot');
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');

?>

<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">Quên Mật Khẩu</h2>
        <?php
            if(!empty($smg)){
                getSmg($smg, $smg_type);
            }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="" class="text-form-group">Email</label>
                <input name="email" type="email" placeholder="Địa chỉ email" class="form-group">
            </div> 

            <button type="submit" class="mg-btn btn btn-primary btn-black fs-4">Gửi</button>
            <hr>
            <p class="text-center fs-5"><a href="?module=auth&action=login" class="text-decoration-none">Đăng nhập</a></p>
            <p class="text-center fs-5"><a href="?module=auth&action=register" class="text-decoration-none">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>

<?php
    layouts('footer-login')
?>