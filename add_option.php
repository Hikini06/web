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

// Kiểm tra các tham số POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không hợp lệ.']);
    exit();
}

$detail_id = isset($_POST['detail_id']) ? (int)$_POST['detail_id'] : 0;
$group_name = isset($_POST['group_name']) ? trim($_POST['group_name']) : '';
$option_name = isset($_POST['option_name']) ? trim($_POST['option_name']) : '';
$add_price = isset($_POST['add_price']) ? floatval($_POST['add_price']) : 0;

// Kiểm tra dữ liệu đầu vào
$valid_groups = ['Màu sắc', 'Số lượng', 'Tùy chọn', 'Phụ kiện'];

if ($detail_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ.']);
    exit();
}

if (!in_array($group_name, $valid_groups)) {
    echo json_encode(['success' => false, 'message' => 'Nhóm không hợp lệ.']);
    exit();
}

if ($option_name === '') {
    echo json_encode(['success' => false, 'message' => 'Tên tùy chọn không được để trống.']);
    exit();
}

try {
    // Thêm tùy chọn mới
    $sql_insert = "INSERT INTO items_option (detail_id, group_name, option_name, add_price, img) 
                   VALUES (:detail_id, :group_name, :option_name, :add_price, :img)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([
        ':detail_id' => $detail_id,
        ':group_name' => $group_name,
        ':option_name' => $option_name,
        ':add_price' => $add_price,
        ':img' => NULL // Bạn có thể cập nhật ảnh sau
    ]);

    // Lấy ID của tùy chọn vừa thêm
    $new_option_id = $pdo->lastInsertId();

    // Trả về dữ liệu tùy chọn mới
    $new_option = [
        'id' => $new_option_id,
        'group_name' => $group_name,
        'option_name' => $option_name,
        'add_price' => $add_price,
        'img' => NULL
    ];

    echo json_encode(['success' => true, 'data' => $new_option]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi thêm tùy chọn: ' . $e->getMessage()]);
}
?>
