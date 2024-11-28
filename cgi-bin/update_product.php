<?php
// update_product.php

header('Content-Type: application/json');

session_start();

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
    exit();
}

// Kiểm tra phương thức yêu cầu
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không hợp lệ.']);
    exit();
}

// Kiểm tra dữ liệu
if (!isset($_POST['id'], $_POST['field'], $_POST['value'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu.']);
    exit();
}

$id = $_POST['id'];
$field = $_POST['field'];
$value = $_POST['value'];

// Kiểm tra trường hợp có thể chỉnh sửa
$allowed_fields = ['name', 'description', 'price'];
if (!in_array($field, $allowed_fields)) {
    echo json_encode(['success' => false, 'message' => 'Trường không hợp lệ.']);
    exit();
}

// Kiểm tra và xử lý giá trị
if ($field === 'price') {
    if (!is_numeric($value) || $value < 0) {
        echo json_encode(['success' => false, 'message' => 'Giá không hợp lệ.']);
        exit();
    }
    $value = floatval($value);
} else {
    $value = trim($value);
    if (empty($value)) {
        echo json_encode(['success' => false, 'message' => ucfirst($field) . ' không được để trống.']);
        exit();
    }
}

require_once '../config/db-connect.php';

try {
    // Sử dụng prepared statement để tránh SQL Injection
    $sql = "UPDATE items_detail SET $field = :value WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':value', $value);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Cập nhật thành công.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()]);
}
?>
