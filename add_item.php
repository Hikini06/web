<?php
// add_item.php

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

if (!isset($_POST['name']) || empty(trim($_POST['name'])) || !isset($_POST['subcategory_id'])) {
    echo json_encode(['success' => false, 'message' => 'Tên item hoặc subcategory_id không hợp lệ.']);
    exit();
}

$name = trim($_POST['name']);
$subcategory_id = intval($_POST['subcategory_id']);

// Bao gồm kết nối cơ sở dữ liệu
require_once '../config/db-connect.php';

try {
    // Kiểm tra xem tên item đã tồn tại chưa trong subcategory này
    $sql_check = "SELECT COUNT(*) FROM items WHERE name = :name AND subcategory_id = :subcategory_id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':name' => $name, ':subcategory_id' => $subcategory_id]);
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Tên item đã tồn tại trong subcategory này.']);
        exit();
    }

    // Thêm item mới
    $sql_insert = "INSERT INTO items (name, subcategory_id) VALUES (:name, :subcategory_id)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([':name' => $name, ':subcategory_id' => $subcategory_id]);
    
    // Lấy ID của item mới thêm
    $new_id = $pdo->lastInsertId();

    echo json_encode(['success' => true, 'data' => ['id' => $new_id, 'name' => $name]]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm item: ' . $e->getMessage()]);
}

$pdo = null;
?>
