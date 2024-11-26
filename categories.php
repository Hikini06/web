<?php
require 'db-connect.php';

// Lấy danh sách subcategories từ bảng subcategories
$query = $pdo->prepare("SELECT * FROM subcategories");
$query->execute();
$subcategories = $query->fetchAll(PDO::FETCH_ASSOC);

// Lấy subcategory_id từ URL (nếu có)
$subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : null;

// Lấy thông tin subcategory từ bảng subcategories
$subcategoryInfo = [];
if ($subcategory_id) {
    $querySubcategoryInfo = $pdo->prepare("
        SELECT name, description
        FROM subcategories
        WHERE id = :subcategory_id
    ");
    $querySubcategoryInfo->bindValue(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
    $querySubcategoryInfo->execute();
    $subcategoryInfo = $querySubcategoryInfo->fetch(PDO::FETCH_ASSOC);
}

// Lấy items_per_page từ URL hoặc đặt mặc định
$items_per_page = isset($_GET['items_per_page']) ? intval($_GET['items_per_page']) : 16;

// Trang hiện tại
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page = max(1, $page); // Đảm bảo trang >= 1
$offset = ($page - 1) * $items_per_page;

// Nếu có subcategory_id, truy vấn danh sách sản phẩm từ bảng items_detail
$total_products = 0;
$products = [];
if ($subcategory_id) {
    // Đếm tổng số sản phẩm
    $count_query = $pdo->prepare("
        SELECT COUNT(*) AS total
        FROM items_detail
        INNER JOIN items ON items_detail.item_id = items.id
        WHERE items.subcategory_id = :subcategory_id
    ");
    $count_query->bindValue(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
    $count_query->execute();
    $total_products = $count_query->fetch(PDO::FETCH_ASSOC)['total'];

    // Truy vấn sản phẩm có phân trang
    $query = $pdo->prepare("
        SELECT items_detail.*
        FROM items_detail
        INNER JOIN items ON items_detail.item_id = items.id
        WHERE items.subcategory_id = :subcategory_id
        LIMIT :limit OFFSET :offset
    ");
    $query->bindValue(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
    $query->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    $query->execute();
    $products = $query->fetchAll(PDO::FETCH_ASSOC);
}

// Tính tổng số trang
$total_pages = ceil($total_products / $items_per_page);
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh mục sản phẩm</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="categories.css"/>
    <link rel="stylesheet" href="header.css" />
    <link rel="stylesheet" href="categories-responsive.css"/>
</head>
<body>
    <!-- HEADER -->
    <?php include 'header.php'; ?>

    <!-- Subcategory Info -->
    <?php if (!empty($subcategoryInfo)): ?>
        <div class="subcategory-info">
            <h1><?= htmlspecialchars($subcategoryInfo['name']) ?></h1>
            <p><?= htmlspecialchars($subcategoryInfo['description']) ?></p>
        </div>
    <?php endif; ?>
    <!-- Categories Filter -->
    <div class="categories-filter-cont">
        <button class="arrow left-arrow">&lt;</button>
        <div class="categories-filter">
        <ul>
            <?php foreach ($subcategories as $subcategory): ?>
                <li class="<?= $subcategory['id'] === $subcategory_id ? 'active' : '' ?>">
                    <a href="categories.php?subcategory_id=<?= htmlspecialchars($subcategory['id']) ?>">
                        <?= htmlspecialchars($subcategory['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        </div>
        <button class="arrow right-arrow">&gt;</button>
    </div>
    <!-- Categories Products -->
    <div class="categories">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <a href="product-detail.php?id=<?= htmlspecialchars($product['id']) ?>">
                        <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p><?= htmlspecialchars(number_format($product['price'])) ?>đ</p>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có sản phẩm nào trong danh mục này.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($total_pages > 1): ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="categories.php?subcategory_id=<?= htmlspecialchars($subcategory_id) ?>&items_per_page=<?= htmlspecialchars($items_per_page) ?>&page=<?= $i ?>"
                class="<?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
    <!-- FOOTER -->
    <?php include 'footer.php'; ?>


<script src="header.js"></script>
<script src="categories.js"></script>
<script>
        // Script để tự động điều chỉnh items_per_page dựa trên kích thước màn hình
        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            const currentItemsPerPage = parseInt(urlParams.get('items_per_page')) || 16;
            const screenWidth = window.innerWidth;

            // Xác định nếu màn hình nhỏ hơn hoặc bằng 768px thì items_per_page = 10
            if (screenWidth <= 768 && currentItemsPerPage !== 10) {
                urlParams.set('items_per_page', 10);
                urlParams.set('page', 1); // Đặt lại trang về 1
                window.location.search = urlParams.toString();
            }

            // Nếu màn hình lớn hơn 768px và items_per_page không phải là 16, cập nhật
            if (screenWidth > 768 && currentItemsPerPage !== 16) {
                urlParams.set('items_per_page', 16);
                urlParams.set('page', 1); // Đặt lại trang về 1
                window.location.search = urlParams.toString();
            }
        })();
</script>
</body>
</html>
