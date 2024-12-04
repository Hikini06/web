<?php
// functions.php

/**
 * Hàm để thêm filemtime vào URL của asset (CSS, JS, hình ảnh, ...)
 *
 * @param string $path Đường dẫn tới file asset tương đối từ thư mục hiện tại của functions.php.
 * @return string URL của asset với tham số version.
 */
function asset($path) {
    // Loại bỏ dấu '/' đầu tiên nếu có để tránh lỗi đường dẫn
    $path = ltrim($path, '/');

    // Tạo đường dẫn tuyệt đối tới file asset dựa trên vị trí của functions.php
    $fullPath = __DIR__ . '/' . $path;

    // Kiểm tra xem file có tồn tại không
    if (file_exists($fullPath)) {
        $version = filemtime($fullPath);
        // Trả về đường dẫn tương đối với tham số phiên bản
        return $path . '?v=' . $version;
    }

    // Nếu file không tồn tại, trả về đường dẫn gốc
    return $path;
}
?>
