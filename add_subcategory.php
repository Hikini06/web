<?php
// add_subcategory.php

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

if (!isset($_POST['name']) || empty(trim($_POST['name'])) || !isset($_POST['category_id'])) {
    echo json_encode(['success' => false, 'message' => 'Tên subcategory hoặc category_id không hợp lệ.']);
    exit();
}

$name = trim($_POST['name']);
$category_id = intval($_POST['category_id']);

// Bao gồm kết nối cơ sở dữ liệu
require_once '../config/db-connect.php';

try {
    // Kiểm tra xem tên subcategory đã tồn tại chưa trong category này
    $sql_check = "SELECT COUNT(*) FROM subcategories WHERE name = :name AND category_id = :category_id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':name' => $name, ':category_id' => $category_id]);
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Tên subcategory đã tồn tại trong category này.']);
        exit();
    }

    // Thêm subcategory mới
    $sql_insert = "INSERT INTO subcategories (name, category_id) VALUES (:name, :category_id)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([':name' => $name, ':category_id' => $category_id]);
    
    // Lấy ID của subcategory mới thêm
    $new_id = $pdo->lastInsertId();

    echo json_encode(['success' => true, 'data' => ['id' => $new_id, 'name' => $name]]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm subcategory: ' . $e->getMessage()]);
}

$pdo = null;
?>
