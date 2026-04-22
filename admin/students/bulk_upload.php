<?php
/**
 * BULK_DATA_IMPORT_v2.4 - Email & SQL Matched
 * Developer: Ns TECH
 */
include '../../config/auth.php';
include '../../config/db.php';

// --- PART 1: DOWNLOAD SAMPLE CSV LOGIC ---
if (isset($_GET['download_sample'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=dpss_student_sample.csv');
    $output = fopen('php://output', 'w');
    
    // Headers including Email
    fputcsv($output, [
        'MS_NO', 'NAME', 'EMAIL', 'PHONE', 'CLASS_NAME', 'FATHER_NAME', 
        'MOTHER_NAME', 'PREVIOUS_SCHOOL', 'DOB', 'ADDRESS', 
        'LAST_TOTAL', 'LAST_PERCENTAGE'
    ]);
    
    // Sample Row
    fputcsv($output, [
        'MST2026001', 'JOHN DOE', 'john.doe@example.com', '9848012345', '1st GRADE', 'S. DOE', 
        'M. DOE', 'GREEN VALLEY SCHOOL', '15-08-2015', 'SIDDIPET', 
        '450.00', '90.00'
    ]);
    exit();
}

// --- PART 2: IMPORT LOGIC ---
$message = "";

function getClassId($conn, $className) {
    $className = mysqli_real_escape_string($conn, trim($className));
    if(empty($className)) return 0;
    $res = $conn->query("SELECT id FROM classes WHERE class_name = '$className' LIMIT 1");
    return ($res->num_rows > 0) ? $res->fetch_assoc()['id'] : 0;
}

if (isset($_POST['upload'])) {
    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($_FILES["file"]["tmp_name"], "r");
        fgetcsv($file); // Skip Header row

        $count = 0; $error_count = 0; $duplicate_count = 0;

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            if(empty($column[0])) continue;

            $ms_no   = mysqli_real_escape_string($conn, trim($column[0]));
            $name    = mysqli_real_escape_string($conn, trim($column[1]));
            $email   = mysqli_real_escape_string($conn, trim($column[2]));
            $phone   = mysqli_real_escape_string($conn, trim($column[3]));
            $class_id = getClassId($conn, $column[4]);
            $fName   = mysqli_real_escape_string($conn, $column[5]);
            $mName   = mysqli_real_escape_string($conn, $column[6]);
            $pSchool = mysqli_real_escape_string($conn, $column[7]);
            $dob     = (!empty($column[8])) ? date('Y-m-d', strtotime(str_replace('/', '-', $column[8]))) : NULL;
            $address = mysqli_real_escape_string($conn, $column[9]);
            $total   = !empty($column[10]) ? (float)$column[10] : 0.00;
            $percent = !empty($column[11]) ? (float)$column[11] : 0.00;

            if($class_id == 0) { $error_count++; continue; }

            // Check Duplicate MS_NO
            $check = $conn->query("SELECT id FROM students WHERE ms_no = '$ms_no'");
            if($check->num_rows > 0) { $duplicate_count++; continue; }

            // INSERT QUERY with Email
            $sql = "INSERT INTO students (
                ms_no, name, email, phone, class_id, father_name, mother_name, 
                previous_school, dob, address, last_total, last_percentage
            ) VALUES (
                '$ms_no', '$name', '$email', '$phone', '$class_id', '$fName', '$mName', 
                '$pSchool', '$dob', '$address', '$total', '$percent'
            )";
            
            if ($conn->query($sql)) { $count++; } else { $error_count++; }
        }
        fclose($file);
        $message = "<div class='alert alert-success border-success bg-dark text-success'>
                    SYNC_LOG: $count Added | $duplicate_count Duplicates Ignored | $error_count Errors.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulk Import | Matrix Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #06130a; color: #22c55e; font-family: 'Consolas', monospace; }
        .matrix-card { background: #0f2a1a; border: 1px solid #14532d; border-radius: 15px; }
        .form-control { background: #000; border: 1px solid #14532d; color: #fff; }
        .btn-matrix { background: #22c55e; color: #000; font-weight: bold; border: none; }
        .btn-matrix:hover { background: #fff; transform: translateY(-2px); }
        .btn-sample { color: #22c55e; border: 1px solid #22c55e; text-decoration: none; padding: 10px 20px; border-radius: 8px; transition: 0.3s; font-size: 0.9rem; }
        .btn-sample:hover { background: rgba(34,197,94,0.1); color: #fff; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="matrix-card p-5 mx-auto" style="max-width: 850px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0 text-uppercase"><i class="bi bi-envelope-at-fill"></i> Data_Sync_Email_v2</h3>
            <a href="?download_sample=1" class="btn-sample"><i class="bi bi-download"></i> GET_SAMPLE_FILE</a>
        </div>

        <?= $message ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4 py-5 text-center" style="border: 2px dashed #14532d; border-radius: 15px; background: rgba(34,197,94,0.02);">
                <i class="bi bi-cloud-arrow-up" style="font-size: 3.5rem; opacity: 0.5;"></i>
                <input type="file" name="file" class="form-control w-75 mx-auto mt-4" accept=".csv" required>
                <div class="mt-3 text-muted small">Mapping Protocol: MS_NO | NAME | <strong>EMAIL</strong> | PHONE ...</div>
            </div>

            <button name="upload" class="btn btn-matrix w-100 py-3 rounded-3 shadow-lg">
                <i class="bi bi-cpu"></i> EXECUTE_IMPORT_COMMAND
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="../dashboard.php" class="text-success text-decoration-none small"><i class="bi bi-arrow-left"></i> RETURN_TO_SYSTEM_CORE</a>
        </div>
    </div>
</div>

</body>
</html>