<?php
include 'db.php';
include 'utils.php';

$error = "";
$fullname = $email = $phone = $gender = $birth_date = $emergency_contact = $emergency_phone = $tshirt_size = $distance = "";

if ($_POST) {
    $fullname = sanitize_input($_POST['fullname']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $gender = sanitize_input($_POST['gender']);
    $birth_date = sanitize_input($_POST['birth_date']);
    $emergency_contact = sanitize_input($_POST['emergency_contact']);
    $emergency_phone = sanitize_input($_POST['emergency_phone']);
    $tshirt_size = sanitize_input($_POST['tshirt_size']);
    $distance = sanitize_input($_POST['distance']);

    // Validate inputs
    if (empty($fullname) || !validate_name($fullname)) {
        $error = "กรุณากรอกชื่อ-นามสกุลให้ถูกต้อง (ตัวอักษรเท่านั้น)";
    } elseif (empty($email) || !validate_email($email)) {
        $error = "รูปแบบอีเมลไม่ถูกต้อง";
    } elseif (empty($phone) || !validate_phone($phone)) {
        $error = "กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง (เริ่มต้นด้วย 0 ตามด้วยตัวเลข 9 หลัก)";
    } elseif (empty($gender)) {
        $error = "กรุณาเลือกเพศ";
    } elseif (empty($birth_date) || !validate_date($birth_date)) {
        $error = "กรุณากรอกวันเดือนปีเกิดให้ถูกต้อง";
    } elseif (empty($emergency_contact)) {
        $error = "กรุณากรอกชื่อผู้ติดต่อฉุกเฉิน";
    } elseif (empty($emergency_phone) || !validate_phone($emergency_phone)) {
        $error = "กรุณากรอกเบอร์โทรศัพท์ฉุกเฉินให้ถูกต้อง (เริ่มต้นด้วย 0 ตามด้วยตัวเลข 9 หลัก)";
    } elseif (empty($tshirt_size)) {
        $error = "กรุณาเลือกไซส์เสื้อ";
    } elseif (empty($distance)) {
        $error = "กรุณาเลือกระยะทางแข่งขัน";
    } elseif (email_exists_excluding_id($conn, $email)) {
        $error = "อีเมลนี้มีผู้ใช้งานแล้ว";
    } else {
        $stmt = $conn->prepare("INSERT INTO runners(fullname, email, phone, gender, birth_date, emergency_contact, emergency_phone, tshirt_size, distance) VALUES(?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssssss", $fullname, $email, $phone, $gender, $birth_date, $emergency_contact, $emergency_phone, $tshirt_size, $distance);
        if ($stmt->execute()) {
            redirect('index.php');
        } else {
            $error = "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียนวิ่งมาราธอน - Bangkok Marathon 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            font-family: 'Prompt', sans-serif;
            min-height: 100vh;
            padding-bottom: 2rem;
        }

        .navbar {
            background: linear-gradient(90deg, #4361ee, #3a0ca3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-header {
            background: linear-gradient(90deg, #4361ee, #3a0ca3);
            color: white;
            font-weight: 600;
            padding: 1.2rem 1.5rem;
            border-bottom: none;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        .btn-submit {
            background: linear-gradient(90deg, #4cc9f0, #4361ee);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(76, 201, 240, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(76, 201, 240, 0.5);
        }

        .btn-back {
            background: linear-gradient(90deg, #6c757d, #495057);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.5);
        }

        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
        }

        .input-group {
            position: relative;
        }
        
        .section-divider {
            border-top: 2px dashed #dee2e6;
            margin: 1.5rem 0;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-arrow-left-circle-fill me-2"></i> กลับสู่หน้าหลัก
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg fade-in">
                    <div class="card-header text-center">
                        <h4 class="mb-0">
                            <i class="bi bi-person-plus-fill"></i> ลงทะเบียนวิ่งมาราธอน
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?= $error ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" class="row g-4">
                            <!-- Personal Information Section -->
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="bi bi-person-bounding-box me-2"></i>ข้อมูลส่วนตัว</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person-fill text-primary"></i>
                                    </span>
                                    <input type="text" name="fullname" class="form-control border-start-0" 
                                           placeholder="เช่น สุนทร คำพา" maxlength="100" required
                                           value="<?= htmlspecialchars($fullname) ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">อีเมล <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-envelope-fill text-primary"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control border-start-0" 
                                           placeholder="เช่น sunthon@example.com" maxlength="100" required
                                           value="<?= htmlspecialchars($email) ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-telephone-fill text-primary"></i>
                                    </span>
                                    <input type="tel" name="phone" class="form-control border-start-0" 
                                           placeholder="เช่น 0812345678" maxlength="10" required
                                           value="<?= htmlspecialchars($phone) ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">เพศ <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="" <?= $gender == '' ? 'selected' : '' ?>>เลือกเพศ</option>
                                    <option value="ชาย" <?= $gender == 'ชาย' ? 'selected' : '' ?>>ชาย</option>
                                    <option value="หญิง" <?= $gender == 'หญิง' ? 'selected' : '' ?>>หญิง</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">วันเดือนปีเกิด <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-calendar-fill text-primary"></i>
                                    </span>
                                    <input type="date" name="birth_date" class="form-control border-start-0" 
                                           required value="<?= htmlspecialchars($birth_date) ?>">
                                </div>
                            </div>
                            
                            <hr class="section-divider">
                            
                            <!-- Emergency Contact Section -->
                            <div class="col-12">
                                <h5 class="text-warning mb-3"><i class="bi bi-exclamation-triangle me-2"></i>ข้อมูลติดต่อฉุกเฉิน</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">ชื่อผู้ติดต่อฉุกเฉิน <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person-lines-fill text-warning"></i>
                                    </span>
                                    <input type="text" name="emergency_contact" class="form-control border-start-0" 
                                           placeholder="ชื่อ-นามสกุลผู้ติดต่อ" maxlength="100" required
                                           value="<?= htmlspecialchars($emergency_contact) ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">เบอร์โทรฉุกเฉิน <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-telephone-fill text-warning"></i>
                                    </span>
                                    <input type="tel" name="emergency_phone" class="form-control border-start-0" 
                                           placeholder="เช่น 0812345678" maxlength="10" required
                                           value="<?= htmlspecialchars($emergency_phone) ?>">
                                </div>
                            </div>
                            
                            <hr class="section-divider">
                            
                            <!-- Race Details Section -->
                            <div class="col-12">
                                <h5 class="text-success mb-3"><i class="bi bi-shoe-prints me-2"></i>รายละเอียดการแข่งขัน</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">ระยะทางแข่งขัน <span class="text-danger">*</span></label>
                                <select name="distance" class="form-select" required>
                                    <option value="" <?= $distance == '' ? 'selected' : '' ?>>เลือกระยะทาง</option>
                                    <option value="Full Marathon (42km)" <?= $distance == 'Full Marathon (42km)' ? 'selected' : '' ?>>Full Marathon (42km)</option>
                                    <option value="Half Marathon (21km)" <?= $distance == 'Half Marathon (21km)' ? 'selected' : '' ?>>Half Marathon (21km)</option>
                                    <option value="Fun Run (5km)" <?= $distance == 'Fun Run (5km)' ? 'selected' : '' ?>>Fun Run (5km)</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">ไซส์เสื้อ <span class="text-danger">*</span></label>
                                <select name="tshirt_size" class="form-select" required>
                                    <option value="" <?= $tshirt_size == '' ? 'selected' : '' ?>>เลือกไซส์เสื้อ</option>
                                    <option value="S" <?= $tshirt_size == 'S' ? 'selected' : '' ?>>S</option>
                                    <option value="M" <?= $tshirt_size == 'M' ? 'selected' : '' ?>>M</option>
                                    <option value="L" <?= $tshirt_size == 'L' ? 'selected' : '' ?>>L</option>
                                    <option value="XL" <?= $tshirt_size == 'XL' ? 'selected' : '' ?>>XL</option>
                                    <option value="XXL" <?= $tshirt_size == 'XXL' ? 'selected' : '' ?>>XXL</option>
                                </select>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                    <button type="submit" class="btn btn-submit flex-fill">
                                        <i class="bi bi-save-fill me-2"></i> ลงทะเบียนวิ่งมาราธอน
                                    </button>
                                    <a href="index.php" class="btn btn-back flex-fill">
                                        <i class="bi bi-x-circle-fill me-2"></i> ยกเลิก
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>