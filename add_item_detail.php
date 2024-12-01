<?php
// add_item_detail.php

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

if (!isset($_POST['name']) || empty(trim($_POST['name'])) || !isset($_POST['item_id'])) {
    echo json_encode(['success' => false, 'message' => 'Tên items_detail hoặc item_id không hợp lệ.']);
    exit();
}

$name = trim($_POST['name']);
$item_id = intval($_POST['item_id']);

// Bao gồm kết nối cơ sở dữ liệu
require_once '../config/db-connect.php';

try {
    // Kiểm tra xem tên items_detail đã tồn tại chưa trong item này
    $sql_check = "SELECT COUNT(*) FROM items_detail WHERE name = :name AND item_id = :item_id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':name' => $name, ':item_id' => $item_id]);
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Tên items_detail đã tồn tại trong item này.']);
        exit();
    }

    // Thêm items_detail mới
    $sql_insert = "INSERT INTO items_detail (name, item_id) VALUES (:name, :item_id)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([':name' => $name, ':item_id' => $item_id]);
    
    // Lấy ID của items_detail mới thêm
    $new_id = $pdo->lastInsertId();

    echo json_encode(['success' => true, 'data' => ['id' => $new_id, 'name' => $name]]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm items_detail: ' . $e->getMessage()]);
}

$pdo = null;
?>
