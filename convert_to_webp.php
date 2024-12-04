<?php
// Thiết lập thời gian thực thi không giới hạn
set_time_limit(0);

// Đường dẫn thư mục gốc chứa ảnh
$inputDir = 'image'; // Thư mục gốc
$outputDir = 'img-webp'; // Thư mục lưu file WebP

// Tạo thư mục đích nếu chưa tồn tại
if (!is_dir($outputDir)) {
    if (!mkdir($outputDir, 0777, true)) {
        die("Không thể tạo thư mục đích: $outputDir");
    }
}

// Các định dạng ảnh hỗ trợ
$supportedFormats = ['jpg', 'jpeg', 'png'];

// Mảng lưu trữ log lỗi
$errors = [];

/**
 * Hàm chuyển đổi ảnh sang WebP
 *
 * @param string $sourcePath Đường dẫn file gốc
 * @param string $destinationPath Đường dẫn file WebP
 * @return bool
 */
function convertToWebp($sourcePath, $destinationPath) {
    global $errors;

    // Lấy phần mở rộng file
    $fileExtension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

    // Tạo đối tượng hình ảnh dựa trên định dạng
    switch ($fileExtension) {
        case 'jpg':
        case 'jpeg':
            $image = @imagecreatefromjpeg($sourcePath);
            break;
        case 'png':
            $image = @imagecreatefrompng($sourcePath);
            break;
        default:
            $image = false;
    }

    if (!$image) {
        $errors[] = "Không thể tạo đối tượng hình ảnh từ: $sourcePath";
        return false;
    }

    // Chuyển đổi sang WebP và lưu file
    if (!@imagewebp($image, $destinationPath, 80)) {
        $errors[] = "Không thể lưu file WebP: $destinationPath";
        imagedestroy($image);
        return false;
    }

    // Giải phóng bộ nhớ
    imagedestroy($image);
    return true;
}

/**
 * Hàm duyệt qua tất cả các thư mục và file đệ quy
 *
 * @param string $currentInputDir Đường dẫn thư mục hiện tại
 * @param string $currentOutputDir Đường dẫn thư mục đích hiện tại
 */
function processFolder($currentInputDir, $currentOutputDir) {
    global $supportedFormats, $errors;

    // Mở thư mục
    $dir = opendir($currentInputDir);
    if (!$dir) {
        $errors[] = "Không thể mở thư mục: $currentInputDir";
        return;
    }

    while (($file = readdir($dir)) !== false) {
        // Bỏ qua các mục "." và ".."
        if ($file === '.' || $file === '..') {
            continue;
        }

        $sourcePath = $currentInputDir . DIRECTORY_SEPARATOR . $file;
        $destinationPath = $currentOutputDir . DIRECTORY_SEPARATOR . $file;

        if (is_dir($sourcePath)) {
            // Nếu là thư mục, tạo thư mục đích và duyệt đệ quy
            if (!is_dir($destinationPath)) {
                if (!mkdir($destinationPath, 0777, true)) {
                    $errors[] = "Không thể tạo thư mục: $destinationPath";
                    continue;
                }
            }
            // Gọi đệ quy để xử lý thư mục con
            processFolder($sourcePath, $destinationPath);
        } else {
            // Nếu là file, kiểm tra định dạng và chuyển đổi
            $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($fileExtension, $supportedFormats)) {
                // Tạo tên file WebP
                $webpFileName = pathinfo($file, PATHINFO_FILENAME) . '.webp';
                $webpDestinationPath = $currentOutputDir . DIRECTORY_SEPARATOR . $webpFileName;

                // Chuyển đổi ảnh
                if (convertToWebp($sourcePath, $webpDestinationPath)) {
                    echo "Chuyển đổi thành công: $sourcePath -> $webpDestinationPath<br>";
                } else {
                    echo "Lỗi khi chuyển đổi: $sourcePath<br>";
                }
            } else {
                // Bỏ qua các định dạng không hỗ trợ
                echo "Bỏ qua file không hỗ trợ: $sourcePath<br>";
            }
        }
    }

    closedir($dir);
}

// Bắt đầu chuyển đổi từ thư mục gốc
processFolder($inputDir, $outputDir);

// Hiển thị kết quả
echo "<hr>";
echo "Hoàn tất chuyển đổi!<br>";
if (!empty($errors)) {
    echo "<strong>Có một số lỗi xảy ra:</strong><br>";
    foreach ($errors as $error) {
        echo "- $error<br>";
    }
} else {
    echo "Tất cả ảnh đã được chuyển đổi thành công!";
}
?>
