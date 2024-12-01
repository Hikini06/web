<?php
// get_subcategories.php

header('Content-Type: application/json');

if (!isset($_GET['category_id']) || empty($_GET['category_id'])) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy category_id.']);
    exit();
}

$category_id = intval($_GET['category_id']);

require_once '../config/db-connect.php';

try {
    $sql = "SELECT id, name FROM subcategories WHERE category_id = :category_id ORDER BY name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->execute();
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $subcategories]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn subcategories: ' . $e->getMessage()]);
}

$pdo = null;
?>
