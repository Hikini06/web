<?php
session_start();
require_once '../config/db-connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $option_id = isset($_POST['option_id']) ? intval($_POST['option_id']) : 0;

    if ($option_id <= 0 || !isset($_FILES['image'])) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit();
    }

    $image = $_FILES['image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($image['type'], $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Định dạng ảnh không được hỗ trợ']);
        exit();
    }

    // Tạo thư mục lưu ảnh nếu chưa tồn tại
    $upload_dir = './image/option-img/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Tạo tên tệp duy nhất
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $filename = 'option_' . $option_id . '_' . time() . '.' . $ext;
    $filepath = $upload_dir . $filename;

    // Di chuyển tệp đã tải lên
    if (move_uploaded_file($image['tmp_name'], $filepath)) {
        // Cập nhật đường dẫn ảnh trong cơ sở dữ liệu
        $sql = "UPDATE items_option SET img = :img WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':img', $filename, PDO::PARAM_STR);
        $stmt->bindParam(':id', $option_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'image_path' => 'image/option-img/' . $filename]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cập nhật đường dẫn ảnh không thành công']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tải lên ảnh không thành công']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
}

// Đóng kết nối
$pdo = null;
?>
