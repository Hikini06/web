<?php
// upload_image.php

header('Content-Type: application/json');

session_start();

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
    exit();
}

// Kiểm tra phương thức yêu cầu
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không hợp lệ.']);
    exit();
}

// Kiểm tra tệp ảnh
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi tải lên tệp ảnh.']);
    exit();
}

// Kiểm tra ID sản phẩm
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ.']);
    exit();
}

$id = $_POST['id'];
$image = $_FILES['image'];

// Kiểm tra loại tệp
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($image['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Chỉ chấp nhận các định dạng JPG, PNG, GIF.']);
    exit();
}

// Kiểm tra kích thước tệp (ví dụ: tối đa 2MB)
$max_size = 2 * 1024 * 1024; // 2MB
if ($image['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'Kích thước tệp ảnh không được vượt quá 2MB.']);
    exit();
}

// Tạo tên tệp mới để tránh trùng lặp
$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
$new_filename = uniqid('img_', true) . '.' . strtolower($ext);

// Đường dẫn lưu tệp
$upload_dir = __DIR__ . '/image/upload/';
$upload_path = $upload_dir . $new_filename;

// Đảm bảo thư mục upload tồn tại
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Di chuyển tệp đã tải lên
if (!move_uploaded_file($image['tmp_name'], $upload_path)) {
    echo json_encode(['success' => false, 'message' => 'Không thể lưu tệp ảnh.']);
    exit();
}

require_once 'db-connect.php';

try {
    // Lấy tên tệp ảnh cũ để xóa nếu cần
    $sql_old = "SELECT img FROM items_detail WHERE id = :id";
    $stmt_old = $pdo->prepare($sql_old);
    $stmt_old->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_old->execute();
    $old_img = $stmt_old->fetchColumn();

    // Cập nhật đường dẫn ảnh mới vào cơ sở dữ liệu
    $sql = "UPDATE items_detail SET img = :img WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':img', $new_filename, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Xóa tệp ảnh cũ nếu cần
    if ($old_img && file_exists($upload_dir . $old_img)) {
        unlink($upload_dir . $old_img);
    }

    echo json_encode(['success' => true, 'filename' => $new_filename, 'message' => 'Ảnh đã được cập nhật thành công.']);
} catch (PDOException $e) {
    // Xóa tệp đã tải lên nếu có lỗi
    if (file_exists($upload_path)) {
        unlink($upload_path);
    }
    echo json_encode(['success' => false, 'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()]);
}
?>
