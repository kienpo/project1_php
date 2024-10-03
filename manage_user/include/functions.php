<!-- Các hàm chung của project -->
<?php
if (!defined('_Code')) {
    die('Access denied...');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layouts($layoutName = 'header', $data = [])
{
    if (file_exists(_WEB_PATH_TEMPLATE . '/layout/' . $layoutName . '.php')) {
        require_once _WEB_PATH_TEMPLATE . '/layout/' . $layoutName . '.php';
    }
}


// Hàm gửi mail
function sendMail($to, $subject, $content)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'kienpo07102002@gmail.com';                     //SMTP username
        $mail->Password   = 'wiiz chwi zjho idio';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('kienhungdung100702@gmail.com', 'kien');
        $mail->addAddress($to);     //Add a recipient       

        //Content
        $mail->CharSet = "UTF-8";
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $sendMail = $mail->send();
        if ($sendMail) {
            return $sendMail;
        }

        // echo 'Gửi thành công!';

    } catch (Exception $e) {
        echo "Gửi email thất bại. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Kiểm tra phương thức get
function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return false;
}

// Kiểm tra phương thức post
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    return false;
}

// Hàm lọc dữ liệu
function filter()
{
    $filterArr = [];
    if (isGet()) {
        // return $_GET;
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }

    if (isPost()) {
        // return $_GET;
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }

    return $filterArr;
}

// 
function filterValue($key, $value, $type)
{
    switch ($key) {
        case 'date':
            // Expecting date in YYYY-MM-DD format
            return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) ? $value : '';
        case 'datetime':
            // Expecting datetime in YYYY-MM-DD hh:mm:ss format
            return preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value) ? $value : '';
        case 'year':
            // Expecting year in YYYY format
            return preg_match('/^\d{4}$/', $value) ? $value : '';
        default:
            return filter_input($type, $key, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}

// Kiểm tra email
function isEmail($email)
{
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

// Hàm kiểm tra số nguyên
function isNumberInt($number)
{
    $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    return $checkNumber;
}

// Hàm kiểm tra số thực
function isNumberFloat($number)
{
    $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT);
    return $checkNumber;
}

// Hàm kiểm tra sđt
function isPhone($phone)
{
    $checkZero = false;
    // ĐK 1: kí tự đầu tiên là số 0
    if ($phone[0] == 0) {
        $checkZero = true;
        $phone = substr($phone, 1);
    }

    // Ddk2: phải có 9 chữ số
    $checkNumber = false;
    if (isNumberInt($phone) && strlen($phone) == 9) {
        $checkNumber = true;
    }

    if ($checkZero && $checkNumber) {
        return true;
    }

    return false;
}

// thông báo lỗi
function getSmg($smg, $type = 'success')
{
    echo '<div class = "alert alert-' . $type . '">';
    echo $smg;
    echo '</div>';
}

// Hàm chuyển hướng
function redirect($path = 'index.php')
{
    header("Location: $path");
    exit;
}

// Hàm thông báo lỗi
function form_error($fileName, $beforeHtml = '', $afterHtml = '', $errors)
{
    return (!empty($errors[$fileName])) ? '<span class="error">' . reset($errors[$fileName]) . '</span>' : null;
}

// Hàm hiển thị dữ liệu cũ
function old($fileName, $oldData, $default = null)
{
    echo (!empty($oldData[$fileName])) ? $oldData[$fileName] : $default;
}

// Hàm kiểm tra trạng thái đăng nhập
function isLogin()
{
    $checkLogin = false;
    if (getSession('tokenlogin')) {
        $tokenLogin = getSession('tokenlogin');

        // Kiểm tra token có giống trong database
        $queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
        if (!empty($queryToken)) {
            $checkLogin = true;
        } else {
            removeSession('tokenlogin');
        }
    }
    return $checkLogin;
}

function checkPrivilege($uri = false)
{
    // Lấy URL hiện tại hoặc giá trị truyền vào
    $uri = $uri != false ? $uri : $_SERVER['REQUEST_URI'];

    // Kiểm tra nếu privileges tồn tại và là mảng
    if (isset($_SESSION['userData']['privileges']) && is_array($_SESSION['userData']['privileges'])) {
        $privileges = $_SESSION['userData']['privileges'];
        $privileges = implode("|", $privileges);
        preg_match('/' . $privileges . '/', $uri, $matches);
        return !empty($matches);
    } else {
        return false; // Không có quyền nào được thiết lập hoặc không phải là mảng
    }
}

?>