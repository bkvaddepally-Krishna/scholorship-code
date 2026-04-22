<?php
include '../config/db.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    // This query flips the switch that the RESULTS.php page is looking for
    $conn->query("UPDATE exams SET status = 'published' WHERE id = $id");
    
    header("Location: manage_exams.php?msg=Results Published Successfully");
}
?>