<?php
if (!defined('_Code')) {
    die('Access denied...');
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$data = [
    'pageTitle' => 'In bảng điểm'
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

// Lấy thông tin người dùng từ session
if (isset($_SESSION['userData'])) {
    $userData = $_SESSION['userData'];

    // Kiểm tra xem user_id và fullname có tồn tại trong mảng hay không
    if (isset($userData['user_id']) && isset($userData['fullname'])) {
        $userId = $userData['user_id'];
        $fullname = $userData['fullname'];

        // Khởi tạo câu truy vấn chung cho admin và sinh viên
        $query = "
            SELECT 
                sc.*, 
                c.course_name,
                s.student_name
            FROM 
                student_courses sc
            JOIN 
                courses c ON sc.course_Id = c.course_id
            JOIN 
                students s ON sc.student_Id = s.student_id
            WHERE 1 ";

        // Nếu user không phải là admin, thêm điều kiện để chỉ hiển thị bảng điểm của sinh viên liên kết
        if ($fullname !== 'admin') {
            $query .= "AND s.studentId = :userId "; // studentId liên kết với id của user
        }
        // Chuẩn bị truy vấn
        $stmt = $conn->prepare($query);

        // Nếu user không phải là admin, gán giá trị cho biến `:userId`
        if ($fullname !== 'admin') {
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        }
        // Thực thi truy vấn
        $stmt->execute();

        // Lấy danh sách kết quả
        $ListUser = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Thông tin user không hợp lệ.";
    }
} else {
    echo "Không tìm thấy thông tin user trong session.";
}

// echo "<pre>";
// print_r($ListUser);
// echo "</pre>";

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('IN BẢNG ĐIỂM');

// Set column headers
$sheet->setCellValue('A1', 'Họ và tên');
$sheet->setCellValue('B1', 'Tên học phần');
$sheet->setCellValue('C1', 'Điểm CC');
$sheet->setCellValue('D1', 'Điểm bài tập');
$sheet->setCellValue('E1', 'Điểm giữa kỳ');
$sheet->setCellValue('F1', 'Điểm cuối kỳ');
$sheet->setCellValue('G1', 'Điểm T10');
$sheet->setCellValue('H1', 'Điểm chữ');

// Populate data rows
$numRow = 2;
foreach ($ListUser as $row) {
    $sheet->setCellValue('A' . $numRow, $row['student_name']);
    $sheet->setCellValue('B' . $numRow, $row['course_name']);
    $sheet->setCellValue('C' . $numRow, $row['attendance_points']);
    $sheet->setCellValue('D' . $numRow, $row['exercise_points']);
    $sheet->setCellValue('E' . $numRow, $row['midterm_score']);
    $sheet->setCellValue('F' . $numRow, $row['final_score']);
    $sheet->setCellValue('G' . $numRow, $row['T10_point']);
    $sheet->setCellValue('H' . $numRow, $row['letter_grades']);
    $numRow++;
}

// Set headers for file download
ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Bang_Diem_' . time() . '.xlsx"');

// Write the file to output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

layouts('footer');
