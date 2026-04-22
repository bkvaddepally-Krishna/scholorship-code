<?php
include __DIR__ . '/../../config/db.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'ASC';
$search = $_GET['search'] ?? '';

$allowed = ['id','ms_no','name','phone','class_name'];
if(!in_array($sort,$allowed)) $sort = 'id';
$order = ($order == 'DESC') ? 'DESC' : 'ASC';

/* COUNT */
$count_sql = "SELECT COUNT(*) as total 
FROM students s 
LEFT JOIN classes c ON s.class_id = c.id";

if($search){
    $count_sql .= " WHERE s.name LIKE '%$search%' 
                    OR s.ms_no LIKE '%$search%' 
                    OR s.phone LIKE '%$search%'";
}

$total = $conn->query($count_sql)->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);

/* DATA */
$sql = "SELECT s.*, c.class_name 
FROM students s 
LEFT JOIN classes c ON s.class_id = c.id";

if($search){
    $sql .= " WHERE s.name LIKE '%$search%' 
              OR s.ms_no LIKE '%$search%' 
              OR s.phone LIKE '%$search%'";
}

$sql .= " ORDER BY $sort $order LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

$students = [];

while($row = $result->fetch_assoc()){
    $students[] = $row;
}

echo json_encode([
    "students"=>$students,
    "total_pages"=>$total_pages
]);
?>