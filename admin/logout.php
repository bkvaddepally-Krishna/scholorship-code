<?php
session_start();
include '../config/db.php';

if(isset($_SESSION['username'])) {
    $u = $_SESSION['username'];
    $conn->query("INSERT INTO logs (action) VALUES ('SYSTEM_LOGOUT: Admin [$u] signed out.')");
}

session_destroy();
header("Location: ../index.php");
exit();
?>