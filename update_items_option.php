<?php
session_start();
require_once '../config/db-connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $field = isset($_POST['field']) ? $_POST['field'] : '';
    $value = isset($_POST['value']) ? $_POST['value'] : '';

    // Kiểm tra và chuẩn hóa dữ liệu
    if ($id <= 0 || empty($field) || $value === '') {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit();
    }

    // Xác định trường cần cập nhật
    $allowed_fields = ['option_name', 'add_price'];
    if (!in_array($field, $allowed_fields)) {
        echo json_encode(['success' => false, 'message' => 'Trường không hợp lệ']);
        exit();
    }

    // Chuẩn bị câu lệnh SQL
    $sql = "UPDATE items_option SET {$field} = :value WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Bind tham số
    if ($field === 'add_price') {
        $stmt->bindParam(':value', $value, PDO::PARAM_STR); // Sử dụng PARAM_STR để hỗ trợ số thập phân
    } else {
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    }
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cập nhật không thành công']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
}

// Đóng kết nối
$pdo = null;
?>
