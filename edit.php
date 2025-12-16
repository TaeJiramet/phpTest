<?php
include 'db.php';

// รับ id จาก URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// ดึงข้อมูลสมาชิกจาก DB
$stmt = $conn->prepare("SELECT * FROM members WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

if (!$member) {
    header("Location: index.php");
    exit;
}

// ถ้ามีการส่ง form
if ($_POST) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $major = $_POST['major'];
    $study_year = $_POST['study_year'];

    // ตรวจสอบอีเมล
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "รูปแบบอีเมลไม่ถูกต้อง";
    } else {
        $stmt = $conn->prepare("UPDATE members SET fullname=?, email=?, major=?, study_year=? WHERE id=?");
        $stmt->bind_param("sssii", $fullname, $email, $major, $study_year, $id);
        $stmt->execute();

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แก้ไขสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h4>✏️ แก้ไขสมาชิก</h4>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" class="row g-3">
            <div class="col-md-6">
                <label>ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" class="form-control"
                    value="<?= htmlspecialchars($member['fullname']) ?>" required>
            </div>
            <div class="col-md-6">
                <label>อีเมล</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($member['email']) ?>"
                    required>
            </div>
            <div class="col-md-6">
                <label>สาขาที่ศึกษา</label>
                <input type="text" name="major" class="form-control" value="<?= htmlspecialchars($member['major']) ?>"
                    required>
            </div>
            <div class="col-md-6">
                <label>ปีการศึกษา (พ.ศ.)</label>
                <input type="number" name="study_year" class="form-control" value="<?= $member['study_year'] ?>"
                    required>
            </div>
            <div class="col-12 mt-3">
                <button class="btn btn-primary">บันทึกการแก้ไข</button>
                <a href="index.php" class="btn btn-secondary">กลับ</a>
            </div>
        </form>
    </div>
</body>

</html>