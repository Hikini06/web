<?php
// delete_item.php

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
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy ID item.']);
    exit();
}

$item_id = intval($_POST['id']);

require_once '../config/db-connect.php';

try {
    // Kiểm tra xem item có items_detail không
    $sql_check = "SELECT COUNT(*) FROM items_detail WHERE item_id = :item_id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':item_id' => $item_id]);
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Không thể xóa item vì có items_detail liên quan.']);
        exit();
    }

    // Xóa item
    $sql_delete = "DELETE FROM items WHERE id = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([':id' => $item_id]);

    echo json_encode(['success' => true, 'message' => 'Đã xóa item thành công.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa item: ' . $e->getMessage()]);
}

$pdo = null;
?>
