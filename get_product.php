<?php
session_start();

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập.']);
    exit();
}

// Bao gồm tệp kết nối cơ sở dữ liệu
require_once '../config/db-connect.php';

// Lấy các tham số từ yêu cầu GET
$category_id = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$subcategory_id = isset($_GET['subcategory_id']) && is_numeric($_GET['subcategory_id']) ? (int)$_GET['subcategory_id'] : null;
$item_id = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? (int)$_GET['item_id'] : null;
$items_detail_id = isset($_GET['items_detail_id']) && is_numeric($_GET['items_detail_id']) ? (int)$_GET['items_detail_id'] : null;

// Xây dựng truy vấn với các điều kiện tương ứng
$sql = "SELECT
            items_detail.id,
            items_detail.name,
            items_detail.description,
            items_detail.img,
            items_detail.price
        FROM items_detail
        INNER JOIN items ON items_detail.item_id = items.id
        INNER JOIN subcategories ON items.subcategory_id = subcategories.id
        INNER JOIN categories ON subcategories.category_id = categories.id
        WHERE 1=1";

$params = [];

// Thêm điều kiện cho category_id
if ($category_id) {
    $sql .= " AND categories.id = :category_id";
    $params[':category_id'] = $category_id;
}

// Thêm điều kiện cho subcategory_id
if ($subcategory_id) {
    $sql .= " AND subcategories.id = :subcategory_id";
    $params[':subcategory_id'] = $subcategory_id;
}

// Thêm điều kiện cho item_id
if ($item_id) {
    $sql .= " AND items.id = :item_id";
    $params[':item_id'] = $item_id;
}

// Thêm điều kiện cho items_detail_id
if ($items_detail_id) {
    $sql .= " AND items_detail.id = :items_detail_id";
    $params[':items_detail_id'] = $items_detail_id;
}

$sql .= " ORDER BY items_detail.id DESC";

// Chuẩn bị và thực thi truy vấn
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Trả về dữ liệu dưới dạng JSON
echo json_encode(['success' => true, 'data' => $products]);
