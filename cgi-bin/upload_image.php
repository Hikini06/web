<?php
session_start();

// Kiểm tra quyền truy cập
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập.']);
    exit();
}

// Bao gồm tệp kết nối cơ sở dữ liệu
require_once '../config/db-connect.php';

// Kiểm tra yêu cầu POST và tệp ảnh
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id']) && isset($_FILES['image'])) {
        $product_id = $_POST['product_id'];
        $image = $_FILES['image'];

        // Kiểm tra lỗi tải lên
        if ($image['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Lỗi tải lên ảnh.']);
            exit();
        }

        // Kiểm tra loại tệp (cho phép jpg, jpeg, png, gif)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($image['type'], $allowed_types)) {
            echo json_encode(['success' => false, 'message' => 'Chỉ cho phép tải lên các định dạng JPG, JPEG, PNG, GIF.']);
            exit();
        }

        // Kiểm tra kích thước tệp (ví dụ: tối đa 5MB)
        $max_size = 5 * 1024 * 1024; // 5MB
        if ($image['size'] > $max_size) {
            echo json_encode(['success' => false, 'message' => 'Kích thước ảnh vượt quá giới hạn cho phép (5MB).']);
            exit();
        }

        // Tạo thư mục upload nếu chưa tồn tại
        $upload_dir = 'image/upload/';
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                echo json_encode(['success' => false, 'message' => 'Không thể tạo thư mục upload.']);
                exit();
            }
        }

        // Tạo tên tệp duy nhất để tránh trùng lặp
        $file_ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid('img_', true) . '.' . $file_ext;
        $destination = $upload_dir . $new_filename;

        // Di chuyển tệp vào thư mục upload
        if (move_uploaded_file($image['tmp_name'], $destination)) {
            // Cập nhật đường dẫn ảnh trong cơ sở dữ liệu
            try {
                $sql = "UPDATE items_detail SET img = :img WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':img' => $new_filename, ':id' => $product_id]);

                echo json_encode(['success' => true, 'image_path' => '/' . $destination]);
                exit();
            } catch (PDOException $e) {
                // Xóa tệp nếu có lỗi trong cơ sở dữ liệu
                if (file_exists($destination)) {
                    unlink($destination);
                }
                echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật cơ sở dữ liệu: ' . $e->getMessage()]);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể di chuyển tệp ảnh.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không được hỗ trợ.']);
    exit();
}
?>
