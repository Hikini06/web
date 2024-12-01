<?php
session_start();

// Bật hiển thị lỗi trong quá trình phát triển (có thể tắt trong môi trường sản xuất)
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
$search = isset($_GET['search']) ? trim($_GET['search']) : null; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1; // Tham số trang
$limit = 50; // Số sản phẩm mỗi trang
$offset = ($page - 1) * $limit; // Tính toán offset

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

// Thêm điều kiện tìm kiếm
if ($search) {
    $sql .= " AND items_detail.name LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

try {
    // Tạo truy vấn đếm tổng số sản phẩm
    $count_sql = "SELECT COUNT(*) FROM items_detail
                  INNER JOIN items ON items_detail.item_id = items.id
                  INNER JOIN subcategories ON items.subcategory_id = subcategories.id
                  INNER JOIN categories ON subcategories.category_id = categories.id
                  WHERE 1=1";

    $count_params = [];

    // Thêm các điều kiện tương tự
    if ($category_id) {
        $count_sql .= " AND categories.id = :category_id";
        $count_params[':category_id'] = $category_id;
    }

    if ($subcategory_id) {
        $count_sql .= " AND subcategories.id = :subcategory_id";
        $count_params[':subcategory_id'] = $subcategory_id;
    }

    if ($item_id) {
        $count_sql .= " AND items.id = :item_id";
        $count_params[':item_id'] = $item_id;
    }

    if ($items_detail_id) {
        $count_sql .= " AND items_detail.id = :items_detail_id";
        $count_params[':items_detail_id'] = $items_detail_id;
    }

    if ($search) {
        $count_sql .= " AND items_detail.name LIKE :search";
        $count_params[':search'] = '%' . $search . '%';
    }

    // Thực thi truy vấn đếm
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($count_params);
    $total_products = $count_stmt->fetchColumn();
    $total_pages = ceil($total_products / $limit);

    // Thêm ORDER BY, LIMIT và OFFSET vào truy vấn chính
    $sql .= " ORDER BY items_detail.id DESC LIMIT :limit OFFSET :offset";

    // Chuẩn bị truy vấn
    $stmt = $pdo->prepare($sql);

    // Bind các tham số khác
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    // Bind :limit và :offset với kiểu dữ liệu là PDO::PARAM_INT
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    // Thực thi truy vấn
    $stmt->execute();

    // Lấy kết quả
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Trả về dữ liệu dưới dạng JSON
    echo json_encode([
        'success' => true,
        'data' => $products,
        'total_pages' => $total_pages
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn: ' . $e->getMessage()]);
    exit();
}
?>
