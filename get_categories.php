<?php
// get_categories.php

session_start();

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập.']);
    exit();
}

header('Content-Type: application/json');

require_once '../config/db-connect.php';

try {
    $sql = "SELECT id, name FROM categories ORDER BY name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $categories]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn categories: ' . $e->getMessage()]);
}

$pdo = null;
?>
