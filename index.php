<?php
include 'db.php';
$sql = "SELECT * FROM members ORDER BY id ASC";
$result = $conn->query($sql);
$deleted = isset($_GET['deleted']) && $_GET['deleted'] == 'true';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevClub Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            font-family: 'Prompt', sans-serif;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(90deg, #4361ee, #3a0ca3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .page-title {
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50%;
            height: 4px;
            background: linear-gradient(90deg, #4361ee, #3a0ca3);
            border-radius: 2px;
        }

        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: linear-gradient(90deg, #4361ee, #3a0ca3);
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }

        .table-hover tbody tr {
            transition: background-color 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }

        .btn-action {
            border-radius: 8px;
            font-weight: 500;
            padding: 6px 12px;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-add {
            background: linear-gradient(90deg, #4cc9f0, #4361ee);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(76, 201, 240, 0.3);
        }

        .btn-add:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(76, 201, 240, 0.5);
        }

        .btn-edit {
            background: linear-gradient(90deg, #f72585, #b5179e);
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(90deg, #f72585, #7209b7);
            transform: scale(1.05);
        }

        .btn-delete {
            background: linear-gradient(90deg, #ff4d6d, #ff2a5e);
            color: white;
        }

        .btn-delete:hover {
            background: linear-gradient(90deg, #ff4d6d, #ff0a54);
            transform: scale(1.05);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
        }

        .empty-state i {
            font-size: 3rem;
            color: #ced4da;
            margin-bottom: 1rem;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .counter-badge {
            background: linear-gradient(90deg, #4361ee, #3a0ca3);
            color: white;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 0.8rem;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-code-slash"></i> DevClub Members
            </a>
            <div class="d-flex align-items-center text-white">
                <i class="bi bi-people-fill me-2"></i>
                <span>ระบบจัดการสมาชิก</span>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if ($deleted): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                ลบข้อมูลสมาชิกเรียบร้อยแล้ว!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <h3 class="fw-bold text-dark page-title">
                <i class="bi bi-people-fill"></i> รายชื่อสมาชิก 
                <span class="counter-badge"><?= $result->num_rows ?></span>
            </h3>
            <a href="add.php" class="btn btn-add shadow-lg">
                <i class="bi bi-person-plus-fill"></i> เพิ่มสมาชิกใหม่
            </a>
        </div>

        <div class="card shadow-lg fade-in">
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-white">
                            <tr>
                                <th class="text-center" style="width: 80px;">รหัส</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>อีเมล</th>
                                <th>สาขา</th>
                                <th class="text-center" style="width: 120px;">ปีการศึกษา</th>
                                <th class="text-center" style="width: 180px;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr class="fade-in">
                                        <td class="text-center fw-bold"><?= $row['id'] ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                </div>
                                                <div>
                                                    <?= htmlspecialchars($row['fullname']) ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="text-decoration-none">
                                                <i class="bi bi-envelope-fill me-1 text-muted"></i>
                                                <?= htmlspecialchars($row['email']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info-emphasis">
                                                <i class="bi bi-mortarboard-fill me-1"></i>
                                                <?= htmlspecialchars($row['major']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success-emphasis">
                                                <?= $row['study_year'] ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-action btn-edit">
                                                    <i class="bi bi-pencil-square"></i> แก้ไข
                                                </a>
                                                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-action btn-delete"
                                                    onclick="return confirm('ยืนยันลบสมาชิก \n\'<?= htmlspecialchars($row['fullname']) ?>\'?')">
                                                    <i class="bi bi-trash-fill"></i> ลบ
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 empty-state">
                                        <i class="bi bi-person-x"></i>
                                        <h4 class="mt-3">ไม่มีข้อมูลสมาชิก</h4>
                                        <p class="text-muted">คลิกที่ปุ่ม "เพิ่มสมาชิกใหม่" เพื่อเพิ่มสมาชิกแรกของคุณ</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer class="text-center text-muted mt-4 mb-3">
            <small>© <?= date('Y') ?> DevClub Members System | พัฒนาด้วย ❤️</small>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add hover effect to rows
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('mouseenter', () => {
                row.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';
            });
            row.addEventListener('mouseleave', () => {
                row.style.backgroundColor = '';
            });
        });
    </script>

</body>

</html>