<?php
if(!defined('_Code')){
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
$ListUser = getRaw("
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
    JOIN
        results r ON r.student_Id = sc.student_Id AND r.course_Id = sc.course_Id
    ORDER BY 
        sc.update_at
");

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

$regexResult = checkPrivilege();
if (!$regexResult){
    echo 'Bạn không có quyền truy cập';exit;
}
layouts('footer');
?>
