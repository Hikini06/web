<?php
// get_items.php

header('Content-Type: application/json');

if (!isset($_GET['subcategory_id']) || empty($_GET['subcategory_id'])) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy subcategory_id.']);
    exit();
}

$subcategory_id = intval($_GET['subcategory_id']);

require_once '../config/db-connect.php';

try {
    $sql = "SELECT id, name FROM items WHERE subcategory_id = :subcategory_id ORDER BY name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $items]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn items: ' . $e->getMessage()]);
}

$pdo = null;
?>
