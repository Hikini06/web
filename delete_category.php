<?php
// delete_category.php

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
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy ID category.']);
    exit();
}

$category_id = intval($_POST['id']);

require_once '../config/db-connect.php';

try {
    // Kiểm tra xem category có subcategories không
    $sql_check = "SELECT COUNT(*) FROM subcategories WHERE category_id = :category_id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':category_id' => $category_id]);
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Không thể xóa category vì có subcategories liên quan.']);
        exit();
    }

    // Xóa category
    $sql_delete = "DELETE FROM categories WHERE id = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([':id' => $category_id]);

    echo json_encode(['success' => true, 'message' => 'Đã xóa category thành công.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa category: ' . $e->getMessage()]);
}

$pdo = null;
?>
