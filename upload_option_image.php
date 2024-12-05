<?php
require_once '../config/db-connect.php'; // Đường dẫn tới file kết nối DB
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['option_id']) && isset($_FILES['image'])) {
    $option_id = (int)$_POST['option_id'];
    $image = $_FILES['image'];

    // Kiểm tra lỗi tải lên
    if ($image['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Lỗi tải lên ảnh.']);
        exit;
    }

    // Kiểm tra định dạng ảnh
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($image['type'], $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Chỉ cho phép tải lên các định dạng JPEG, PNG, GIF.']);
        exit;
    }

    // Đặt tên ảnh mới để tránh trùng lặp
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $new_filename = 'option_' . $option_id . '_' . time() . '.' . $ext;
    $upload_dir = './image/option-img/'; // Đường dẫn tới thư mục lưu ảnh
    $upload_path = $upload_dir . $new_filename;

    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Di chuyển tệp ảnh tới thư mục đích
    if (move_uploaded_file($image['tmp_name'], $upload_path)) {
        // Cập nhật đường dẫn ảnh vào cơ sở dữ liệu
        $relative_path = 'image/option-img/' . $new_filename;
        try {
            $query = "UPDATE items_option SET img = :img WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':img', $relative_path, PDO::PARAM_STR);
            $stmt->bindParam(':id', $option_id, PDO::PARAM_INT);
            $stmt->execute();
            echo json_encode(['success' => true, 'image_path' => $relative_path]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật CSDL: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể di chuyển tệp ảnh.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Thiếu tham số hoặc phương thức không hợp lệ.']);
}
?>
