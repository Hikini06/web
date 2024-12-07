<?php
session_start();

// Kiểm tra trạng thái đăng nhập admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
    exit();
}

if (!isset($_GET['subcategory_id']) || !is_numeric($_GET['subcategory_id'])) {
    echo json_encode(['success' => false, 'message' => 'Tham số không hợp lệ.']);
    exit();
}

$subcategory_id = (int)$_GET['subcategory_id'];

require_once '../config/db-connect.php';

try {
    $sql = "SELECT id, name, description, img FROM items WHERE subcategory_id = :subcategory_id ORDER BY id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $items]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn: ' . $e->getMessage()]);
}
?>
