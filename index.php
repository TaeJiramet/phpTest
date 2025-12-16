<?php
include 'db.php';
$sql = "SELECT * FROM members ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevClub Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }

        .navbar {
            background: linear-gradient(90deg, #4e54c8, #8f94fb);
        }

        .card {
            border-radius: 15px;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-custom {
            min-width: 80px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">DevClub Members</a>
        </div>
    </nav>

    <div class="container">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-2">
            <h3 class="fw-bold text-primary">📋 รายชื่อสมาชิก</h3>
            <a href="add.php" class="btn btn-success btn-lg shadow-lg">
                <i class="bi bi-person-plus-fill"></i> เพิ่มสมาชิกใหม่
            </a>
        </div>

        <div class="card shadow-lg">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-primary text-white">
                            <tr>
                                <th>รหัส</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>อีเมล</th>
                                <th>สาขา</th>
                                <th>ปีการศึกษา</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['fullname']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($row['major']) ?></td>
                                        <td><?= $row['study_year'] ?></td>
                                        <td class="d-flex flex-column flex-md-row gap-2">
                                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm btn-custom">
                                                <i class="bi bi-pencil-square"></i> แก้ไข
                                            </a>
                                            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm btn-custom"
                                                onclick="return confirm('ยืนยันลบสมาชิกนี้?')">
                                                <i class="bi bi-trash-fill"></i> ลบ
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-3">ไม่มีข้อมูลสมาชิก</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</body>

</html>