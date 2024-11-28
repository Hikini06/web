<?php
// Kết nối cơ sở dữ liệu
include '../config/db-connect.php';

try {
    // Xác định số sản phẩm mỗi trang
    $productsPerPage = 16;

    // Lấy số trang hiện tại từ tham số GET, mặc định là 1
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

    // Tính toán offset cho truy vấn SQL
    $offset = ($page - 1) * $productsPerPage;

    // Truy vấn tổng số sản phẩm
    $totalProductsQuery = "SELECT COUNT(*) as total FROM items_detail";
    $stmt = $pdo->query($totalProductsQuery);
    $totalProductsRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalProducts = $totalProductsRow['total'];

    // Tính toán tổng số trang
    $totalPages = ceil($totalProducts / $productsPerPage);
    // Hàm phát hiện thiết bị di động
    function isMobile() {
        return preg_match('/Mobile|Android|BlackBerry|IEMobile|Silk|Kindle|Opera Mini/i', $_SERVER['HTTP_USER_AGENT']);
    }

    // Đặt số sản phẩm mỗi trang dựa trên thiết bị
    $productsPerPage = isMobile() ? 10 : 16;

    // Cập nhật các biến liên quan đến pagination
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $productsPerPage;

    // Truy vấn để lấy các sản phẩm, sắp xếp các nhóm ngẫu nhiên
    $productQuery = "
        SELECT t.*
        FROM items_detail t
        JOIN (
            SELECT item_id, RAND() as rand_order
            FROM items_detail
            GROUP BY item_id
        ) item_random ON t.item_id = item_random.item_id
        ORDER BY item_random.rand_order, t.id
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $pdo->prepare($productQuery);
    $stmt->bindValue(':limit', $productsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}

?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm - Tiệm hoa mimi</title>
    <!-- Font chữ -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="header.css">
</head>
<body>
    <!-- Header -->
    <?php include 'header.php'; ?>

    <div class="product-title"> 
      <h2>Danh sách toàn bộ sản phẩm</h2>
    </div>
    <!-- Danh sách sản phẩm -->
    <section class="product-all-cont">
        <div class="products-container">
            <?php foreach ($products as $product): ?>
                <a href="product-detail.php?id=<?= htmlspecialchars($product['id']) ?>">
                    <div class="product">
                        <img src="image/upload/<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <span><?= htmlspecialchars($product['name']) ?></span>
                        <p><?= number_format($product['price'], 0, ',', '.') ?>đ</p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Phân trang -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="pagination-btn"><i class="fa-solid fa-chevron-left"></i></a>
            <?php endif; ?>

            <!-- Luôn hiển thị trang 1 -->
            <a href="?page=1" class="pagination-btn <?= $page == 1 ? 'active' : '' ?>">1</a>

            <!-- Khi ở trang đầu, hiển thị trang 2 và 3 -->
            <?php if ($page == 1): ?>
                <?php if ($totalPages >= 2): ?>
                    <a href="?page=2" class="pagination-btn">2</a>
                <?php endif; ?>
                <?php if ($totalPages >= 3): ?>
                    <a href="?page=3" class="pagination-btn">3</a>
                <?php endif; ?>
                <?php if ($totalPages > 3): ?>
                    <span class="pagination-dots">...</span>
                <?php endif; ?>
                <?php if ($totalPages > 1): ?>
                    <a href="?page=<?= $totalPages ?>" class="pagination-btn"><?= $totalPages ?></a>
                <?php endif; ?>
            <?php elseif ($page == $totalPages): ?>
                <?php if ($totalPages > 3): ?>
                    <span class="pagination-dots">...</span>
                <?php endif; ?>
                <?php if ($totalPages - 2 > 1): ?>
                    <a href="?page=<?= $totalPages - 2 ?>" class="pagination-btn"><?= $totalPages - 2 ?></a>
                <?php endif; ?>
                <?php if ($totalPages - 1 > 1): ?>
                    <a href="?page=<?= $totalPages - 1 ?>" class="pagination-btn"><?= $totalPages - 1 ?></a>
                <?php endif; ?>
                <a href="?page=<?= $totalPages ?>" class="pagination-btn active"><?= $totalPages ?></a>
            <?php else: ?>
                <?php if ($page > 3): ?>
                    <span class="pagination-dots">...</span>
                <?php endif; ?>

                <a href="?page=<?= $page ?>" class="pagination-btn active"><?= $page ?></a>

                <?php if ($page < $totalPages - 2): ?>
                    <span class="pagination-dots">...</span>
                <?php endif; ?>

                <?php if ($totalPages > 1): ?>
                    <a href="?page=<?= $totalPages ?>" class="pagination-btn"><?= $totalPages ?></a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="pagination-btn"><i class="fa-solid fa-chevron-right"></i></a>
            <?php endif; ?>
        </div>


    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Script -->
    <script src="header.js"></script>
</body>
</html>
