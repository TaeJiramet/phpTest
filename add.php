<?php
include 'db.php';
$error = "";
$fullname = $email = $major = $study_year = "";

if ($_POST) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $major = trim($_POST['major']);
    $study_year = intval($_POST['study_year']);

    if (!preg_match("/^[a-zA-Zก-๙\s]+$/u", $fullname)) {
        $error = "กรุณากรอกชื่อ-นามสกุลให้ถูกต้อง (ตัวอักษรเท่านั้น)";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "รูปแบบอีเมลไม่ถูกต้อง";
    } else {
        $stmt = $conn->prepare("SELECT id FROM members WHERE fullname=?");
        $stmt->bind_param("s", $fullname);
        $stmt->execute();
        $result_name = $stmt->get_result();

        if ($result_name->num_rows > 0) {
            $error = "ชื่อ-นามสกุลนี้มีผู้ใช้งานแล้ว";
        } else {
            $stmt = $conn->prepare("SELECT id FROM members WHERE email=?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result_email = $stmt->get_result();

            if ($result_email->num_rows > 0) {
                $error = "อีเมลนี้มีผู้ใช้งานแล้ว";
            } else {
                $stmt = $conn->prepare("INSERT INTO members(fullname,email,major,study_year) VALUES(?,?,?,?)");
                $stmt->bind_param("sssi", $fullname, $email, $major, $study_year);
                $stmt->execute();
                header("Location: index.php");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสมาชิก DevClub</title>
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
            <div class="col-lg-8">
                <div class="card shadow-lg fade-in">
                    <div class="card-header text-center">
                        <h4 class="mb-0">
                            <i class="bi bi-person-plus-fill"></i> เพิ่มสมาชิกใหม่
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
                            <div class="col-md-6">
                                <label class="form-label">ชื่อ-นามสกุล</label>
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
                                <label class="form-label">อีเมล</label>
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
                                <label class="form-label">สาขาที่ศึกษา</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-mortarboard-fill text-primary"></i>
                                    </span>
                                    <input type="text" name="major" class="form-control border-start-0" 
                                           placeholder="เช่น วิทยาการคอมพิวเตอร์" maxlength="100" required
                                           value="<?= htmlspecialchars($major) ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">ปีการศึกษา (พ.ศ.)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-calendar-fill text-primary"></i>
                                    </span>
                                    <input type="number" name="study_year" class="form-control border-start-0" 
                                           placeholder="เช่น 2567" min="2500" max="2600" required
                                           value="<?= htmlspecialchars($study_year) ?>">
                                </div>
                            </div>
                            
                            <div class="col-12 mt-3">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                    <button type="submit" class="btn btn-submit flex-fill">
                                        <i class="bi bi-save-fill me-2"></i> บันทึกข้อมูล
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