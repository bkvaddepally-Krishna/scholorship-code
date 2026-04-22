<?php
include '../../config/db.php';

echo json_encode([
 "students"=>$conn->query("SELECT COUNT(*) c FROM students")->fetch_assoc()['c'],
 "exams"=>$conn->query("SELECT COUNT(*) c FROM exams")->fetch_assoc()['c'],
 "marks"=>$conn->query("SELECT COUNT(*) c FROM marks")->fetch_assoc()['c'],
 "logs"=>$conn->query("SELECT COUNT(*) c FROM logs")->fetch_assoc()['c']
]);
?>