<?php
include '../../config/db.php';

$res = $conn->query("
SELECT date,
SUM(CASE WHEN status='P' THEN 1 ELSE 0 END) as present,
SUM(CASE WHEN status='A' THEN 1 ELSE 0 END) as absent
FROM attendance
GROUP BY date
ORDER BY date ASC
");

$data = [];

while($row = $res->fetch_assoc()){
    $total = $row['present'] + $row['absent'];

    $percentage = $total > 0 ? ($row['present'] / $total) * 100 : 0;

    $data[] = [
        "date"=>$row['date'],
        "value"=>round($percentage,2)
    ];
}

echo json_encode($data);
?>