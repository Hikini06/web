<?php
// sale.php

// Kết nối cơ sở dữ liệu bằng PDO
include '../config/db-connect.php';
require_once 'functions.php';

// Hàm kiểm tra thiết bị di động
function isMobile() {
    return preg_match('/Mobile|Android|BlackBerry|IEMobile|Silk/', $_SERVER['HTTP_USER_AGENT']);
}

// Định nghĩa các nhóm sản phẩm
$groups = [
    'a' => [
            'ids' => [1, 2, 3, 4, 5, 6, 7, 8, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30],
            'sale_title' => 'Danh sách sản phẩm nổi bật Noel 2024',
            'banner_img' => './image/banner-pc/banner-2.webp',
            'banner_h1' => 'NGÀY HỘI MUA SẮM',
            'banner_h3' => 'Siêu SALE NOEL'
        ],    
    'noel' => [
        'ids' => [340, 341, 342, 343, 344,345,346,347,348,349,350,351,88,87],
        'sale_title' => 'Danh sách sản phẩm nổi bật Noel 2024',
        'banner_img' => './image/banner-pc/banner-2.webp',
        'banner_h1' => 'NGÀY HỘI MUA SẮM',
        'banner_h3' => 'Siêu SALE NOEL'
    ],
    'set-noel' => [
        'ids' => [277,278,279,280,281,282,283,284,285,286,287,288,289,290,291,292,293,294,295,267,268,269],
        'sale_title' => 'Set quà Noel siêu cấp đáng yêu',
        'banner_img' => './image/banner-pc/banner-2.webp',
        'banner_h1' => 'NGÀY HỘI MUA SẮM',
        'banner_h3' => 'Siêu SALE NOEL'
    ],
    'capy-noel' => [
        'ids' => [341,342],
        'sale_title' => 'Capybara cho Noel 2024',
        'banner_img' => './image/banner-pc/banner-2.webp',
        'banner_h1' => 'NGÀY HỘI MUA SẮM',
        'banner_h3' => 'Siêu SALE NOEL'
    ],
    'gau-truc-noel' => [
        'ids' => [340,343,344],
        'sale_title' => 'Gấu trúc Cute mùa Giáng Sinh',
        'banner_img' => './image/banner-pc/banner-2.webp',
        'banner_h1' => 'NGÀY HỘI MUA SẮM',
        'banner_h3' => 'Siêu SALE NOEL'
    ],
    'caro-noel' => [
        'ids' => [345,346,347,348],
        'sale_title' => 'Mẫu Caro Noel siêu HOT',
        'banner_img' => './image/banner-pc/banner-2.webp',
        'banner_h1' => 'NGÀY HỘI MUA SẮM',
        'banner_h3' => 'Siêu SALE NOEL'
    ],
];

// Lấy tham số 'group' từ URL
$groupKey = isset($_GET['group']) ? $_GET['group'] : 'a'; // Mặc định là nhóm 'a'

// Kiểm tra xem nhóm có tồn tại không
if (!array_key_exists($groupKey, $groups)) {
    echo "Nhóm sản phẩm không hợp lệ.";
    exit;
}

// Lấy thông tin nhóm hiện tại
$currentGroup = $groups[$groupKey];
$productIds = $currentGroup['ids'];
$saleTitle = $currentGroup['sale_title'];
$bannerImg = $currentGroup['banner_img'];
$bannerH1 = $currentGroup['banner_h1'];
$bannerH3 = $currentGroup['banner_h3'];

// Phát hiện thiết bị di động
$mobile = isMobile();

// Xử lý phân trang
$productsPerPage = $mobile ? 10 : 16; // 10 sản phẩm mỗi trang trên di động, 16 trên máy tính
$totalProducts = count($productIds);
$totalPages = ceil($totalProducts / $productsPerPage);

// Lấy tham số 'page' từ URL
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages && $totalPages > 0) {
    $currentPage = $totalPages;
}

// Tính toán OFFSET
$offset = ($currentPage - 1) * $productsPerPage;

// Lấy các ID sản phẩm cho trang hiện tại
$currentProductIds = array_slice($productIds, $offset, $productsPerPage);

// Truy vấn cơ sở dữ liệu để lấy thông tin sản phẩm
$products = [];

if (!empty($currentProductIds)) {
    // Tạo chuỗi dấu hỏi cho mỗi ID
    $placeholders = rtrim(str_repeat('?,', count($currentProductIds)), ',');

    $sql = "SELECT * FROM items_detail WHERE id IN ($placeholders)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($currentProductIds);
        $products = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Lỗi truy vấn cơ sở dữ liệu: " . $e->getMessage());
        echo "Đã xảy ra lỗi. Vui lòng thử lại sau.";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiệm hoa MiMi</title>
    <base href="https://tiemhoamimi.com/">
    <!-- <base href="http://localhost/web-dm-lum/web/"> -->
    <link rel="icon" href="./image/mimi-logo-vuong.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('sale.css'); ?>">
</head>
<body>
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Banner -->
    <div class="banner-cont">
        <div class="banner-img">
            <img src="<?php echo htmlspecialchars($bannerImg); ?>" alt="Banner <?php echo htmlspecialchars($groupKey); ?>">
        </div>
        <div class="banner-text">
            <h1><?php echo htmlspecialchars($bannerH1); ?></h1>
            <h3><?php echo htmlspecialchars($bannerH3); ?></h3>
        </div>
    </div>

    <!-- Title -->
    <div class="sale-title">
        <h1><?php echo htmlspecialchars($saleTitle); ?></h1>
    </div>

    <!-- ITEMS -->
    <div class="sale-item-cont"> 
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <a href="chi-tiet-san-pham/<?php echo urlencode($product['id']); ?>/">
                    <div class="product-item">
                        <!-- Hiển thị hình ảnh, tên và giá sản phẩm -->
                        <img src="https://tiemhoamimi.com/image/upload/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo number_format($product['price'], 0, ',', '.') . 'đ'; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có sản phẩm nào trong nhóm này.</p>
        <?php endif; ?>
    </div>

    <!-- Phân trang -->
    <?php if ($totalPages >1): ?>
        <ul class="pagination">
            <?php
                // Luôn hiển thị trang đầu tiên
                if ($totalPages >=1) {
                    if ($currentPage ==1){
                        echo '<li><span class="active">1</span></li>';
                    }
                    else{
                        echo '<li><a href="danh-sach/' . urlencode($groupKey) . '/trang/1/">1</a></li>';
                    }
                }

                if ($totalPages >5) {
                    if ($currentPage >3) {
                        // Hiển thị dấu "..."
                        echo '<li><span>...</span></li>';
                    }

                    // Xác định phạm vi hiển thị các trang
                    if ($currentPage <=3) {
                        $start =2;
                        $end =4;
                    }
                    elseif ($currentPage >= $totalPages -2) {
                        $start = $totalPages -3;
                        $end = $totalPages -1;
                    }
                    else{
                        $start = $currentPage -1;
                        $end = $currentPage +1;
                    }

                    // Đảm bảo phạm vi không vượt quá giới hạn
                    $start = max(2, $start);
                    $end = min($totalPages -1, $end);

                    // Hiển thị các trang trong phạm vi
                    for ($i=$start; $i <= $end; $i++) { 
                        if ($i == $currentPage) {
                            echo '<li><span class="active">' . $i . '</span></li>';
                        }
                        else{
                            echo '<li><a href="danh-sach/' . urlencode($groupKey) . '/trang/' . $i . '/">' . $i . '</a></li>';
                        }
                    }

                    if ($currentPage < $totalPages -2) {
                        // Hiển thị dấu "..."
                        echo '<li><span>...</span></li>';
                    }
                }
                else{
                    // totalPages <=5, show pages 2 to totalPages -1
                    for ($i=2; $i <= $totalPages -1; $i++) { 
                        if ($i == $currentPage) {
                            echo '<li><span class="active">' . $i . '</span></li>';
                        }
                        else{
                            echo '<li><a href="danh-sach/' . urlencode($groupKey) . '/trang/' . $i . '/">' . $i . '</a></li>';
                        }
                    }
                }

                // Luôn hiển thị trang cuối cùng nếu tổng số trang >1
                if ($totalPages >1){
                    if ($currentPage == $totalPages){
                        echo '<li><span class="active">' . $totalPages . '</span></li>';
                    }
                    else{
                        echo '<li><a href="danh-sach/' . urlencode($groupKey) . '/trang/' . $totalPages . '/">' . $totalPages . '</a></li>';
                    }
                }
            ?>
        </ul>
    <?php endif; ?>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
