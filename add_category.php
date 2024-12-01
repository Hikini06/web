<?php
// add_category.php

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

if (!isset($_POST['name']) || empty(trim($_POST['name']))) {
    echo json_encode(['success' => false, 'message' => 'Tên category không được để trống.']);
    exit();
}

$name = trim($_POST['name']);

// Bao gồm kết nối cơ sở dữ liệu
require_once '../config/db-connect.php';

try {
    // Kiểm tra xem tên category đã tồn tại chưa
    $sql_check = "SELECT COUNT(*) FROM categories WHERE name = :name";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':name' => $name]);
    if ($stmt_check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Tên category đã tồn tại.']);
        exit();
    }

    // Thêm category mới
    $sql_insert = "INSERT INTO categories (name) VALUES (:name)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([':name' => $name]);
    
    // Lấy ID của category mới thêm
    $new_id = $pdo->lastInsertId();

    echo json_encode(['success' => true, 'data' => ['id' => $new_id, 'name' => $name]]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm category: ' . $e->getMessage()]);
}

$pdo = null;
?>
