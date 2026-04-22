<?php
include __DIR__ . '/../../config/db.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'ASC';
$search = $conn->real_escape_string($_GET['search'] ?? '');

$allowed = ['id','ms_no','name','phone','email','class_name'];
if(!in_array($sort,$allowed)) $sort = 'id';
$order = ($order == 'DESC') ? 'DESC' : 'ASC';

// Count total for pagination
$count_sql = "SELECT COUNT(*) as total FROM students s";
if($search){
    $count_sql .= " WHERE s.name LIKE '%$search%' OR s.ms_no LIKE '%$search%' OR s.email LIKE '%$search%'";
}
$total_result = $conn->query($count_sql);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

/* DATA FETCH - Including Email Field */
$sql = "SELECT s.*, c.class_name 
FROM students s 
LEFT JOIN classes c ON s.class_id = c.id";

if($search){
    $sql .= " WHERE s.name LIKE '%$search%' 
              OR s.ms_no LIKE '%$search%' 
              OR s.email LIKE '%$search%' 
              OR s.phone LIKE '%$search%'";
}

$sql .= " ORDER BY $sort $order LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students List | Matrix ERP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body{background:#06130a; color:white; font-family:'Segoe UI', sans-serif; margin: 20px;}
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .search-box { background: #0f2a1a; border: 1px solid #14532d; padding: 8px; border-radius: 5px; color: white; width: 250px; }
        
        table{width:100%; border-collapse:collapse; background: #0f2a1a; font-size: 14px;}
        th,td{padding:12px; border:1px solid #14532d; text-align: left;}
        th{background:#14532d; color: #22c55e; text-transform: uppercase; font-size: 11px; letter-spacing: 1px;}
        tr:hover{background:rgba(34,197,94,0.05);}
        
        .email-link { color: #888; text-decoration: none; font-size: 13px; }
        .email-link:hover { color: #22c55e; }
        .btn-edit { color: #22c55e; border: 1px solid #22c55e; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px; }
        
        .pagination { margin-top: 20px; display: flex; gap: 5px; justify-content: center; }
        .pagination a { padding: 7px 14px; border: 1px solid #14532d; background: #0f2a1a; color: #22c55e; text-decoration: none; border-radius: 4px; }
        .pagination a.active { background: #22c55e; color: #000; font-weight: bold; }
    </style>
</head>
<body>

<div class="header-flex">
    <h2><i class="bi bi-person-lines-fill"></i> Student Records</h2>
    <form method="GET">
        <input type="text" name="search" class="search-box" placeholder="Search name, MS, or Email..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" style="background:#22c55e; border:none; padding:8px 15px; border-radius:5px; font-weight:bold; cursor:pointer;">FILTER</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>MS No</th>
            <th>Student Name</th>
            <th>Email Address</th>
            <th>Phone</th>
            <th>Class</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td style="color: #22c55e; font-family: monospace;"><?= $row['ms_no'] ?></td>
            <td style="font-weight: bold;"><?= strtoupper($row['name']) ?></td>
            <td>
                <?php if(!empty($row['email'])): ?>
                    <a href="mailto:<?= $row['email'] ?>" class="email-link">
                        <i class="bi bi-envelope-at"></i> <?= strtolower($row['email']) ?>
                    </a>
                <?php else: ?>
                    <span style="color:#444;">--</span>
                <?php endif; ?>
            </td>
            <td><?= $row['phone'] ?></td>
            <td><span style="background:#14532d; padding:2px 8px; border-radius:10px; font-size:11px;"><?= $row['class_name'] ?></span></td>
            <td>
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">EDIT</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div class="pagination">
    <?php for($i=1; $i<=$total_pages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= $search ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>

</body>
</html>