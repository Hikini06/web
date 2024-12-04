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

        // Kiểm tra loại tệp (cho phép jpg, jpeg, png)
        // Lưu ý: GIF không hỗ trợ chuyển đổi sang WebP bằng GD
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($image['type'], $allowed_types)) {
            echo json_encode(['success' => false, 'message' => 'Chỉ cho phép tải lên các định dạng JPG, JPEG, PNG.']);
            exit();
        }

        // Kiểm tra kích thước tệp (ví dụ: tối đa 10MB để đảm bảo sau nén dưới 5MB)
        $max_size = 10 * 1024 * 1024; // 10MB
        if ($image['size'] > $max_size) {
            echo json_encode(['success' => false, 'message' => 'Kích thước ảnh vượt quá giới hạn cho phép (10MB).']);
            exit();
        }

        // Tạo thư mục upload nếu chưa tồn tại
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/image/upload/';
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                echo json_encode(['success' => false, 'message' => 'Không thể tạo thư mục upload.']);
                exit();
            }
        }

        // Lấy phần mở rộng tệp
        $file_ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        // Xử lý chuyển đổi và nén ảnh bằng GD
        switch ($file_ext) {
            case 'jpg':
            case 'jpeg':
                $source_image = imagecreatefromjpeg($image['tmp_name']);
                break;
            case 'png':
                $source_image = imagecreatefrompng($image['tmp_name']);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Định dạng ảnh không được hỗ trợ.']);
                exit();
        }

        if (!$source_image) {
            echo json_encode(['success' => false, 'message' => 'Không thể tạo đối tượng hình ảnh từ tệp tải lên.']);
            exit();
        }

        // Thiết lập chất lượng ban đầu
        $quality = 80;
        $max_webp_size = 5 * 1024 * 1024; // 5MB

        // Tạo tên tệp WebP duy nhất
        $new_filename = uniqid('img_', true) . '.webp';
        $destination = $upload_dir . $new_filename;

        // Chuyển đổi và nén ảnh thành WebP
        do {
            // Xóa tệp nếu đã tồn tại
            if (file_exists($destination)) {
                unlink($destination);
            }

            // Chuyển đổi sang WebP
            if (!imagewebp($source_image, $destination, $quality)) {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi chuyển đổi ảnh sang WebP.']);
                exit();
            }

            clearstatcache(true, $destination);
            $current_size = filesize($destination);

            // Giảm chất lượng nếu kích thước vẫn lớn hơn giới hạn
            if ($current_size > $max_webp_size && $quality > 10) {
                $quality -= 10;
            } else {
                break;
            }
        } while ($current_size > $max_webp_size && $quality > 10);

        // Giải phóng bộ nhớ
        imagedestroy($source_image);

        // Kiểm tra kích thước cuối cùng
        if ($current_size > $max_webp_size) {
            // Xóa tệp WebP nếu không đạt yêu cầu
            if (file_exists($destination)) {
                unlink($destination);
            }
            echo json_encode(['success' => false, 'message' => 'Không thể nén ảnh dưới 5MB. Vui lòng tải lại với tệp nhỏ hơn hoặc chất lượng thấp hơn.']);
            exit();
        }

        // Cập nhật đường dẫn ảnh trong cơ sở dữ liệu
        try {
            $sql = "UPDATE items_detail SET img = :img WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':img' => $new_filename, ':id' => $product_id]);

            echo json_encode(['success' => true, 'image_path' => '/image/upload/' . $new_filename]);
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
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không được hỗ trợ.']);
    exit();
}
?>
