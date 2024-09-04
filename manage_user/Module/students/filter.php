<?php
if(!defined('_Code')){
    die('Access denied...');
}

$data = [
    'pageTitle' => 'Lọc Danh Sách Sinh Viên'
];
layouts('header', $data);

// if(!isLogin()){
//     redirect('?module=auth&action=login');
// }

$per_page_record = 5;  // Number of entries to show in a page
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1; // Get the current page number
$start_from = ($page - 1) * $per_page_record; // Calculate the starting record index

// Get the search filter value
$searchStatus = isset($_POST['searchStatus']) ? $_POST['searchStatus'] : '';

// Query to count the total number of records based on filter
$count_query = "
    SELECT COUNT(*) 
    FROM results sc
    JOIN students s ON sc.student_Id = s.student_id
    JOIN courses c ON sc.course_Id = c.course_id
";

// If a search filter is applied, modify the count query
if ($searchStatus !== '') {
    $count_query .= " WHERE sc.approved = ?";
}

$stmt = $conn->prepare($count_query);

if ($searchStatus !== '') {
    $stmt->execute([$searchStatus]);
} else {
    $stmt->execute();
}

$total_records = $stmt->fetchColumn();

// Main query to fetch paginated data
$query = "
    SELECT 
        sc.*, 
        s.student_name, 
        s.student_code,
        c.course_name 
    FROM 
        results sc
    JOIN 
        students s 
    ON 
        sc.student_Id = s.student_id 
    JOIN 
        courses c 
    ON 
        sc.course_Id = c.course_id 
";

// Apply search filter
if ($searchStatus !== '') {
    $query .= " WHERE sc.approved = :searchStatus";
}

// Apply pagination limits
$query .= " ORDER BY sc.update_at LIMIT :start_from, :per_page_record";

$stmt = $conn->prepare($query);

// Bind the parameters
if ($searchStatus !== '') {
    $stmt->bindValue(':searchStatus', $searchStatus, PDO::PARAM_INT);
}
$stmt->bindValue(':start_from', $start_from, PDO::PARAM_INT);
$stmt->bindValue(':per_page_record', $per_page_record, PDO::PARAM_INT);

$stmt->execute();
$ListUser = $stmt->fetchAll(PDO::FETCH_ASSOC);

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');

$regexResult = checkPrivilege();
if (!$regexResult){
    echo 'Bạn không có quyền truy cập';exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .container-fluid {
            display: flex;
            margin-top: -16px;
            height: 100vh;
        }

        nav {
            width: 340px;
            margin-left: -12px;
            padding-bottom: 260px;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .mb {
            padding-bottom: 46rem;
        }

        .strengh {
            width: 20rem;
            height: 10rem;
            margin-top: 3rem;
        }

        .nav-item:hover {
            background-color: #343a40;
            /* Change this to the color you want on hover */
        }

        .dp {
            display: flex;
            justify-content: space-around;
        }

        .pagination {
            display: inline-block;
        }

        .pagination a {
            font-weight: bold;
            font-size: 18px;
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid black;
        }

        .pagination a.active {
            background-color: pink;
        }

        .pagination a:hover:not(.active) {
            background-color: skyblue;
        }
    </style>
</head>
<body>
<div class="container">
    <hr>
    <h2>Kết quả công nhận điểm</h2>
    
    <!-- Bộ lọc -->
    <form action="?module=students&action=filter" method="post">
        <div class="form-group fs-4">
            <label for="searchStatus">Trạng thái công nhận điểm</label>
            <select class="form-control fs-5" name="searchStatus" id="searchStatus">
                <option value="">Tất cả</option>
                <option value="1" <?php echo $searchStatus === '1' ? 'selected' : ''; ?>>Đã công nhận</option>
                <option value="0" <?php echo $searchStatus === '0' ? 'selected' : ''; ?>>Chưa công nhận</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary fs-5">Lọc kết quả</button>
    </form>

    <!-- Bảng kết quả -->
    <div id="resultTable">
        <table class="table table-bordered table-font fs-5">
            <thead>
                <tr>
                    <th>Stt</th>
                    <th>Tên sinh viên</th>
                    <th>Mã sinh viên</th>
                    <th>Môn học phần</th>
                    <th>Điểm</th>
                    <th>Trạng thái công nhận điểm</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(!empty($ListUser)):
                        $count = ($page - 1) * $per_page_record + 1;
                        foreach($ListUser as $item):
                ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo htmlspecialchars($item['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['student_code']); ?></td>
                    <td><?php echo htmlspecialchars($item['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['marks']); ?></td>
                    <td><?php echo $item['approved'] == 1 ? '<button class="btn btn-success btn-sm">Đã công nhận</button>' 
                    : '<button class="btn btn-danger btn-sm">Chưa công nhận</button>'; ?></td>
                </tr>    
                <?php
                        endforeach;
                    else:
                ?>
                    <tr>
                        <td colspan="8">
                            <div class="alert alert-danger text-center">Không có kết quả công nhận điểm nào</div>
                        </td>
                    </tr>
                <?php    
                    endif;
                ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php
            $query = "SELECT COUNT(*) FROM results";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $item = $stmt->fetch(PDO::FETCH_NUM);
            $total_records = $item[0];

            echo "</br>";
            // Number of pages required.   
            $total_pages = ceil($total_records / $per_page_record);
            $pagLink = "";

            if ($page >= 2) {
                echo "<a href='?module=students&action=filter&page=" . ($page - 1) . "'>Prev</a>";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    $pagLink .= "<a class='active' href='?module=students&action=filter&page=" . $i . "'>" . $i . "</a>";
                } else {
                    $pagLink .= "<a href='?module=students&action=filter&page=" . $i . "'>" . $i . "</a>";
                }
            }
            echo $pagLink;

            if ($page < $total_pages) {
                echo "<a href='?module=students&action=filter&page=" . ($page + 1) . "'>Next</a>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>

<?php
layouts('footer');
?>
