<?php
include 'db.php';
include 'utils.php';

// รับ id และ action จาก URL
$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if (!$id || !is_numeric($id)) {
    redirect('index.php');
}

// ดึงข้อมูลผู้สมัครจาก DB
$runner = get_runner_by_id($conn, $id);

if (!$runner) {
    redirect('index.php');
}

// ถ้าผู้ใช้ยืนยันการลบ
if ($action === 'confirm') {
    $stmt = $conn->prepare("DELETE FROM runners WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        redirect('index.php?deleted=true');
    } else {
        redirect('index.php?error=delete_failed');
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการลบผู้สมัครวิ่งมาราธอน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            font-family: 'Prompt', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }

        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-header {
            background: linear-gradient(90deg, #ff4d6d, #ff2a5e);
            color: white;
            font-weight: 600;
            padding: 1.2rem 1.5rem;
            border-bottom: none;
        }

        .btn-confirm {
            background: linear-gradient(90deg, #ff4d6d, #ff2a5e);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 77, 109, 0.3);
        }

        .btn-confirm:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 77, 109, 0.5);
        }

        .btn-cancel {
            background: linear-gradient(90deg, #6c757d, #495057);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.5);
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .member-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
        }
        
        .info-item {
            margin-bottom: 0.75rem;
        }
        
        .info-label {
            font-weight: 500;
            color: #495057;
        }
        
        .info-value {
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg fade-in">
                    <div class="card-header text-center">
                        <h4 class="mb-0">
                            <i class="bi bi-exclamation-triangle-fill"></i> ยืนยันการลบผู้สมัครวิ่งมาราธอน
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-x text-danger" style="font-size: 4rem;"></i>
                            <h5 class="mt-3">คุณแน่ใจหรือไม่ที่จะลบผู้สมัครคนนี้?</h5>
                            <p class="text-muted">การกระทำนี้ไม่สามารถยกเลิกได้</p>
                        </div>
                        
                        <div class="member-card">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <span class="info-label">ชื่อ-นามสกุล:</span><br>
                                        <span class="info-value"><?= htmlspecialchars($runner['fullname']) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <span class="info-label">อีเมล:</span><br>
                                        <span class="info-value"><?= htmlspecialchars($runner['email']) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <span class="info-label">เบอร์โทรศัพท์:</span><br>
                                        <span class="info-value"><?= htmlspecialchars($runner['phone']) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <span class="info-label">เพศ:</span><br>
                                        <span class="info-value"><?= htmlspecialchars($runner['gender']) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <span class="info-label">วันเกิด:</span><br>
                                        <span class="info-value"><?= date('d/m/Y', strtotime($runner['birth_date'])) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <span class="info-label">ระยะทาง:</span><br>
                                        <span class="info-value"><?= htmlspecialchars($runner['distance']) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <span class="info-label">ไซส์เสื้อ:</span><br>
                                        <span class="info-value"><?= htmlspecialchars($runner['tshirt_size']) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <span class="info-label">ผู้ติดต่อฉุกเฉิน:</span><br>
                                        <span class="info-value"><?= htmlspecialchars($runner['emergency_contact']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                            <a href="delete.php?id=<?= $id ?>&action=confirm" class="btn btn-confirm flex-fill">
                                <i class="bi bi-trash-fill me-2"></i> ยืนยันการลบ
                            </a>
                            <a href="index.php" class="btn btn-cancel flex-fill">
                                <i class="bi bi-x-circle-fill me-2"></i> ยกเลิก
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>