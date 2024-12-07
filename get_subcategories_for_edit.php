<?php
// get_subcategories_for_edit.php
session_start();

// Kiểm tra trạng thái đăng nhập admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
    exit();
}

require_once '../config/db-connect.php';

try {
    $sql = "SELECT id, name FROM subcategories ORDER BY name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $subcategories]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn: ' . $e->getMessage()]);
}
?>
