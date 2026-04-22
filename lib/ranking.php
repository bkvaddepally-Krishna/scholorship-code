<?php
function rank($conn,$eid){
 return $conn->query("SELECT student_id,SUM(marks) t FROM marks WHERE exam_id=$eid GROUP BY student_id ORDER BY t DESC");
}
?>