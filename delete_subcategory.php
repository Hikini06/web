<?php
// delete_subcategory.php

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
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy ID subcategory.']);
    exit();
}

$subcategory_id = intval($_POST['id']);

require_once '../config/db-connect.php';

try {
    // Kiểm tra xem subcategory có items không
    $sql_check = "SELECT COUNT(*) FROM items WHERE subcategory_id = :subcategory_id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':subcategory_id' => $subcategory_id]);
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Không thể xóa subcategory vì có items liên quan.']);
        exit();
    }

    // Xóa subcategory
    $sql_delete = "DELETE FROM subcategories WHERE id = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([':id' => $subcategory_id]);

    echo json_encode(['success' => true, 'message' => 'Đã xóa subcategory thành công.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa subcategory: ' . $e->getMessage()]);
}

$pdo = null;
?>
