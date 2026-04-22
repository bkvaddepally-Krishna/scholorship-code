<?php
include '../../config/auth.php';
include '../../config/db.php';

// Handle Deletion
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM exams WHERE id = $id");
    header("Location: create.php?msg=Deleted");
}

// Handle Creation
if($_POST && isset($_POST['create'])){
    $name = $_POST['name'];
    $date = $_POST['date'];
    $conn->query("INSERT INTO exams(name, exam_date) VALUES('$name', '$date')");
    header("Location: create.php?msg=Success");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Exams | DPSS ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #06130a; color: #22c55e; font-family: 'Segoe UI', sans-serif; }
        .glass-card { 
            background: #0f2a1a; 
            border: 1px solid #14532d; 
            border-radius: 15px; 
            padding: 25px; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.5);
        }
        .form-control { 
            background: #06130a; 
            border: 1px solid #14532d; 
            color: white; 
        }
        .form-control:focus { 
            background: #0b2e1a; 
            border-color: #22c55e; 
            color: white; 
            box-shadow: none; 
        }
        .table { color: #cbd5e1; border-color: #14532d; }
        .btn-green { background: #22c55e; color: #06130a; font-weight: bold; border: none; }
        .btn-green:hover { background: #16a34a; color: white; }
        .badge-date { background: #14532d; color: #22c55e; border: 1px solid #22c55e; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="glass-card">
                <h4 class="mb-4"><i class="bi bi-plus-circle"></i> Create Exam</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label class="small text-muted">EXAM NAME</label>
                        <input name="name" class="form-control" placeholder="e.g. Merit Test Phase 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">EXAM DATE</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <button name="create" class="btn btn-green w-100 py-2 mt-2">INITIALIZE EXAM</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="glass-card">
                <h4 class="mb-4"><i class="bi bi-list-task"></i> Scheduled Exams</h4>
                <table class="table table-hover">
                    <thead>
                        <tr style="color: #22c55e; border-bottom: 2px solid #14532d;">
                            <th>ID</th>
                            <th>Exam Name</th>
                            <th>Exam Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = $conn->query("SELECT * FROM exams ORDER BY exam_date DESC");
                        while($row = $res->fetch_assoc()):
                        ?>
                        <tr style="border-bottom: 1px solid #14532d;">
                            <td><span class="text-muted">#<?= $row['id'] ?></span></td>
                            <td><b class="text-white"><?= strtoupper($row['name']) ?></b></td>
                            <td><span class="badge badge-date"><?= date('d M Y', strtotime($row['exam_date'])) ?></span></td>
                            <td class="text-end">
                                <a href="edit_exam.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-info me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Secure Delete: Are you sure?')" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>