<?php
include '../config/auth.php';
include '../config/db.php';

/* ================= SEARCH ================= */
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT s.*, c.class_name 
        FROM students s 
        LEFT JOIN classes c ON s.class_id = c.id";

if (!empty($search)) {
    $sql .= " WHERE s.name LIKE ? 
              OR s.ms_no LIKE ? 
              OR s.phone LIKE ? 
              OR c.class_name LIKE ?";
}

$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $like = "%$search%";
    $stmt->bind_param("ssss", $like, $like, $like, $like);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#06130a;
            color:white;
            font-family:Segoe UI;
        }

        .container-box{
            margin:30px;
            background:#0f2a1a;
            padding:20px;
            border-radius:15px;
            box-shadow:0 0 20px rgba(0,0,0,0.4);
        }

        h2{
            color:#22c55e;
        }

        .form-control{
            background:#0b1f13;
            border:1px solid #14532d;
            color:white;
        }

        table{
            background:#0b1f13;
            border-radius:10px;
            overflow:hidden;
        }

        thead{
            background:#14532d;
            position:sticky;
            top:0;
            z-index:1;
        }

        th, td{
            padding:12px;
            border-bottom:1px solid #14532d;
            color:white;
        }

        tr:hover{
            background:#14532d;
        }

        .btn-sm{
            font-size:12px;
        }

        /* Mobile */
        @media(max-width:768px){
            .container-box{
                margin:10px;
                padding:15px;
            }

            table{
                font-size:13px;
            }
        }
    </style>
</head>

<body>

<div class="container-box">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>👨‍🎓 View Students</h2>
        <a href="add.php" class="btn btn-success">+ Add Student</a>
    </div>

    <!-- SEARCH -->
    <form method="GET" class="mb-3">
        <input type="text" name="search" class="form-control"
               placeholder="Search by Name / MS No / Phone / Class"
               value="<?= htmlspecialchars($search) ?>">
    </form>

    <div style="overflow-x:auto;">
    <table class="table table-dark table-hover">

        <thead>
            <tr>
                <th>ID</th>
                <th>MS No</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Class</th>
                <th width="160">Actions</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['ms_no']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['class_name']) ?></td>

                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>"
                           onclick="return confirm('Delete this student?')"
                           class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No students found</td>
            </tr>
        <?php endif; ?>
        </tbody>

    </table>
    </div>

</div>

</body>
</html>