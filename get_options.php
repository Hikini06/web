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

// Kiểm tra xem detail_id có được cung cấp không
if (!isset($_GET['detail_id']) || !is_numeric($_GET['detail_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ.']);
    exit();
}

$detail_id = (int)$_GET['detail_id'];

try {
    $sql = "SELECT id, group_name, option_name, add_price, img FROM items_option WHERE detail_id = :detail_id ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':detail_id' => $detail_id]);
    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $options]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn dữ liệu: ' . $e->getMessage()]);
}
?>
