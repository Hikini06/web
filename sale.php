<?php
// sale.php

// Kết nối cơ sở dữ liệu bằng PDO
include '../config/db-connect.php';

// Hàm kiểm tra thiết bị di động
function isMobile() {
    return preg_match('/Mobile|Android|BlackBerry|IEMobile|Silk/', $_SERVER['HTTP_USER_AGENT']);
}

// Định nghĩa các nhóm sản phẩm
$groups = [
    'a' => [1, 2, 3, 4, 5, 6, 7, 8, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30],
    'b' => [4, 6, 7, 9, 10, 12],
    // Bạn có thể thêm các nhóm khác ở đây
];

// Lấy tham số 'group' từ URL
$groupKey = isset($_GET['group']) ? $_GET['group'] : 'a'; // Mặc định là nhóm 'a'

// Kiểm tra xem nhóm có tồn tại không
if (!array_key_exists($groupKey, $groups)) {
    echo "Nhóm sản phẩm không hợp lệ.";
    exit;
}

// Lấy danh sách ID sản phẩm của nhóm
$productIds = $groups[$groupKey];

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
    <title>Tiệm hoa MiMi - Nhóm <?php echo htmlspecialchars(strtoupper($groupKey)); ?></title>
    <base href="https://tiemhoamimi.com/">
    <!-- <base href="http://localhost/web_dm_lum/"> -->

    <link rel="icon" href="./image/mimi-logo-vuong.png" type="image/png">
    
    <!-- Font chữ -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="sale.css">
</head>
<body>
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Banner -->
     <div class="banner-cont">
        <div class ="banner-img"><img src="./image/banner-pc/banner-2.jpg" alt=""></div>
        <div class="banner-text">
            <h1>NGÀY HỘI MUA SẮM</h1>
            <h3>Siêu SALE NOEL 2024</h3>
        </div>
     </div>

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

    <!-- Script -->
    <!-- <script src="header.js"></script> -->
</body>
</html>
