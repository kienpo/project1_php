<?php
if(!defined('_Code')){
    die('Access denied...');
}
$data = [
    'pageTitle' => 'In bảng điểm'
];
layouts('header',$data);

if(!isLogin()){
    redirect('?module=auth&action=login');
}

// Fetch student data and scores
// $students = fetchAllStudentsWithScores(); // Implement this function

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle printing logic
    // printScoreSheet($students); // Implement this function
}
?>

<h1>In bảng điểm</h1>
<form method="POST">
    <input type="submit" value="In bảng điểm">
</form>

<h2>Danh sách sinh viên</h2>
<ul>
    <?php foreach ($students as $student): ?>
        <li><?= $student['name'] ?> - <?= $student['score'] ?></li>
    <?php endforeach; ?>
</ul>

<?php
layouts('footer');
?>
