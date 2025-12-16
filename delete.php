<?php
include 'db.php';

// รับ id จาก URL
$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("DELETE FROM members WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// กลับไปหน้า index.php
header("Location: index.php");
exit;
?>