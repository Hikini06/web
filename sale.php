<?php
// sale.php

// Kết nối cơ sở dữ liệu bằng PDO
include '../config/db-connect.php';

// Định nghĩa các nhóm sản phẩm
$groups = [
    'a' => [1, 2, 3, 4, 5, 6, 7, 8, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30 ],
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

// Xử lý phân trang
$productsPerPage = 16; // Số sản phẩm mỗi trang
$totalProducts = count($productIds);
$totalPages = ceil($totalProducts / $productsPerPage);

// Lấy tham số 'page' từ URL
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages) {
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
    <!-- <base href="https://tiemhoamimi.com/"> -->
    <base href="http://localhost/web-dm-lum/web/">

    <link rel="icon" href="./image/mimi-logo-vuong.png" type="image/png">
    
    <!-- Font chữ -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="sale.css">

</head>
<body>
    <!-- Header -->
    <?php include 'header.php'; ?>

    <div class="sale-item-cont"> 
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <!-- Hiển thị hình ảnh, tên và giá sản phẩm -->
                    <img src="https://tiemhoamimi.com/image/upload/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo number_format($product['price'], 0, ',', '.') . ' VNĐ'; ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có sản phẩm nào trong nhóm này.</p>
        <?php endif; ?>
    </div>

    <!-- Phân trang -->
    <?php if ($totalPages > 1): ?>
        <ul class="pagination">
            <!-- Nút Trang Trước -->
            <?php if ($currentPage > 1): ?>
                <li><a href="sale.php?group=<?php echo urlencode($groupKey); ?>&page=<?php echo $currentPage - 1; ?>">&laquo; Trước</a></li>
            <?php else: ?>
                <li><span class="disabled">&laquo; Trước</span></li>
            <?php endif; ?>

            <!-- Các Trang -->
            <?php
                // Hiển thị tối đa 5 trang trong thanh phân trang
                $range = 2; // Số trang hiển thị bên trái và bên phải của trang hiện tại
                $start = max(1, $currentPage - $range);
                $end = min($totalPages, $currentPage + $range);

                if ($start > 1) {
                    echo '<li><a href="sale.php?group=' . urlencode($groupKey) . '&page=1">1</a></li>';
                    if ($start > 2) {
                        echo '<li><span>...</span></li>';
                    }
                }

                for ($i = $start; $i <= $end; $i++):
                    if ($i == $currentPage):
                        echo '<li><span class="active">' . $i . '</span></li>';
                    else:
                        echo '<li><a href="sale.php?group=' . urlencode($groupKey) . '&page=' . $i . '">' . $i . '</a></li>';
                    endif;
                endfor;

                if ($end < $totalPages) {
                    if ($end < $totalPages - 1) {
                        echo '<li><span>...</span></li>';
                    }
                    echo '<li><a href="sale.php?group=' . urlencode($groupKey) . '&page=' . $totalPages . '">' . $totalPages . '</a></li>';
                }
            ?>

            <!-- Nút Trang Sau -->
            <?php if ($currentPage < $totalPages): ?>
                <li><a href="sale.php?group=<?php echo urlencode($groupKey); ?>&page=<?php echo $currentPage + 1; ?>">Sau &raquo;</a></li>
            <?php else: ?>
                <li><span class="disabled">Sau &raquo;</span></li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Script -->
    <script src="header.js"></script>
</body>
</html>
