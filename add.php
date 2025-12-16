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
    <style>
        body {
            background-color: #f0f2f5;
        }

        .card {
            border-radius: 15px;
        }

        .card-header {
            background: linear-gradient(90deg, #4e54c8, #8f94fb);
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header">➕ เพิ่มสมาชิกใหม่</div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="post" class="row g-3">
                    <div class="col-12 col-md-6">
                        <label>ชื่อ-นามสกุล</label>
                        <input type="text" name="fullname" class="form-control" maxlength="100" required
                            value="<?= htmlspecialchars($fullname) ?>">
                    </div>
                    <div class="col-12 col-md-6">
                        <label>อีเมล</label>
                        <input type="email" name="email" class="form-control" maxlength="100" required
                            value="<?= htmlspecialchars($email) ?>">
                    </div>
                    <div class="col-12 col-md-6">
                        <label>สาขาที่ศึกษา</label>
                        <input type="text" name="major" class="form-control" maxlength="100" required
                            value="<?= htmlspecialchars($major) ?>">
                    </div>
                    <div class="col-12 col-md-6">
                        <label>ปีการศึกษา (พ.ศ.)</label>
                        <input type="number" name="study_year" class="form-control" min="2500" max="2600" required
                            value="<?= htmlspecialchars($study_year) ?>">
                    </div>
                    <div class="col-12 mt-3 d-flex flex-column flex-md-row gap-2">
                        <button class="btn btn-primary flex-fill shadow-lg"><i class="bi bi-save-fill"></i>
                            บันทึก</button>
                        <a href="index.php" class="btn btn-secondary flex-fill shadow-lg"><i
                                class="bi bi-arrow-left-circle-fill"></i> กลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>