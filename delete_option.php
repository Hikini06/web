<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Kiểm tra quyền admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập.']);
    exit();
}

// Bao gồm tệp kết nối cơ sở dữ liệu
require_once '../config/db-connect.php';

// Kiểm tra các tham số POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không hợp lệ.']);
    exit();
}

$option_id = isset($_POST['option_id']) ? (int)$_POST['option_id'] : 0;

if ($option_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID tùy chọn không hợp lệ.']);
    exit();
}

try {
    // Xoá tùy chọn
    $sql_delete = "DELETE FROM items_option WHERE id = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([':id' => $option_id]);

    if ($stmt_delete->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Xoá tùy chọn thành công.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Tùy chọn không tồn tại.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi xoá tùy chọn: ' . $e->getMessage()]);
}
?>
