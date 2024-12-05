<?php
require_once '../config/db-connect.php'; // Đường dẫn tới file kết nối DB
header('Content-Type: application/json');

if (isset($_GET['detail_id']) && is_numeric($_GET['detail_id'])) {
    $detail_id = (int)$_GET['detail_id'];
    try {
        $query = "SELECT * FROM items_option WHERE detail_id = :detail_id ORDER BY FIELD(group_name, 'Màu sắc', 'Số lượng', 'Tùy chọn', 'Phụ kiện')";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':detail_id', $detail_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $options]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Thiếu tham số hoặc tham số không hợp lệ.']);
}
?>
