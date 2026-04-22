<?php
/**
 * ATTENDANCE SAVE PROCESSOR v3.5
 */
include '../../config/auth.php';
include '../../config/db.php';

if (isset($_POST['status']) && is_array($_POST['status'])) {
    $class_id = $_POST['class_id'];
    $date = $_POST['date'];
    $success = 0;

    foreach ($_POST['status'] as $student_id => $status) {
        $student_id = intval($student_id);
        $status = $conn->real_escape_string($status);

        // Check if record exists
        $check = $conn->query("SELECT id FROM attendance WHERE student_id = $student_id AND date = '$date'");

        if ($check->num_rows > 0) {
            $query = "UPDATE attendance SET status = '$status' WHERE student_id = $student_id AND date = '$date'";
        } else {
            $query = "INSERT INTO attendance (student_id, status, date) VALUES ($student_id, '$status', '$date')";
        }
        
        if ($conn->query($query)) { $success++; }
    }

    // Log action (No 'user' column)
    $msg = "Attendance saved for $success students (Class ID: $class_id)";
    $conn->query("INSERT INTO logs (action, created_at) VALUES ('$msg', NOW())");

    header("Location: mark_attendance.php?class_id=$class_id&date=$date&msg=success");
    exit();
}