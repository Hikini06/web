<?php
// delete_item_detail.php

session_start();

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập.']);
    exit();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không hợp lệ.']);
    exit();
}

if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy ID items_detail.']);
    exit();
}

$item_detail_id = intval($_POST['id']);

require_once '../config/db-connect.php';

try {
    // Xóa items_detail
    $sql_delete = "DELETE FROM items_detail WHERE id = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([':id' => $item_detail_id]);

    echo json_encode(['success' => true, 'message' => 'Đã xóa items_detail thành công.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa items_detail: ' . $e->getMessage()]);
}

$pdo = null;
?>
