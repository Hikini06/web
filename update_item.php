<?php
// update_item.php
session_start();

// Kiểm tra trạng thái đăng nhập admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
    exit();
}

if (!isset($_POST['id']) || !is_numeric($_POST['id']) ||
    !isset($_POST['field']) || !in_array($_POST['field'], ['name', 'description']) ||
    !isset($_POST['value'])) {
    echo json_encode(['success' => false, 'message' => 'Tham số không hợp lệ.']);
    exit();
}

$id = (int)$_POST['id'];
$field = $_POST['field'];
$value = trim($_POST['value']);

require_once '../config/db-connect.php';

try {
    $sql = "UPDATE items SET {$field} = :value WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Cập nhật thành công.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật: ' . $e->getMessage()]);
}
?>
