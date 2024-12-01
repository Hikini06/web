<?php
// get_items_detail.php

header('Content-Type: application/json');

if (!isset($_GET['item_id']) || empty($_GET['item_id'])) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy item_id.']);
    exit();
}

$item_id = intval($_GET['item_id']);

require_once '../config/db-connect.php';

try {
    $sql = "SELECT id, name FROM items_detail WHERE item_id = :item_id ORDER BY name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
    $stmt->execute();
    $items_detail = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $items_detail]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn items_detail: ' . $e->getMessage()]);
}

$pdo = null;
?>
