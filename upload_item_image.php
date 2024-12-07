<?php
session_start();
// Kiểm tra trạng thái đăng nhập admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
    exit();
}

if (!isset($_POST['item_id']) || !is_numeric($_POST['item_id']) || !isset($_FILES['image'])) {
    echo json_encode(['success' => false, 'message' => 'Tham số không hợp lệ.']);
    exit();
}

$item_id = (int)$_POST['item_id'];
$image = $_FILES['image'];

// Kiểm tra lỗi tải lên
if ($image['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Lỗi tải lên ảnh.']);
    exit();
}

// Kiểm tra loại tệp (chỉ cho phép ảnh)
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($image['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Chỉ cho phép tải lên các định dạng ảnh JPEG, PNG, GIF.']);
    exit();
}

// Tạo tên tệp mới để tránh trùng lặp
$extension = pathinfo($image['name'], PATHINFO_EXTENSION);
$new_filename = 'item_' . $item_id . '_' . time() . '.' . $extension;

// Đường dẫn lưu trữ
$upload_dir = __DIR__ . './image/items-img/';
$upload_path = $upload_dir . $new_filename;

// Di chuyển tệp đã tải lên
if (!move_uploaded_file($image['tmp_name'], $upload_path)) {
    echo json_encode(['success' => false, 'message' => 'Không thể lưu ảnh.']);
    exit();
}

// Cập nhật đường dẫn ảnh vào cơ sở dữ liệu
$image_path = './image/items-img/' . $new_filename;

require_once '../config/db-connect.php';

try {
    $sql = "UPDATE items SET img = :img WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':img', $new_filename, PDO::PARAM_STR);
    $stmt->bindParam(':id', $item_id, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode(['success' => true, 'image_path' => './' . $image_path, 'message' => 'Tải lên ảnh thành công.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật ảnh: ' . $e->getMessage()]);
}
?>
