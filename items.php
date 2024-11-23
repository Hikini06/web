<?php
require 'db-connect.php';

// Lấy danh sách item_id từ bảng items
$queryItems = $pdo->prepare("SELECT id, name FROM items");
$queryItems->execute();
$items = $queryItems->fetchAll(PDO::FETCH_ASSOC);

// Lấy item_id được chọn (nếu có)
$item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : null;

// Pagination variables
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 16; // Hiển thị 16 sản phẩm mỗi trang
$offset = ($page - 1) * $limit;

// Nếu có item_id, lấy danh sách sản phẩm từ items_detail
$products = [];
if ($item_id) {
    $queryProducts = $pdo->prepare("
        SELECT * FROM items_detail
        WHERE item_id = ?
        LIMIT $limit OFFSET $offset
    ");
    $queryProducts->execute([$item_id]);
    $products = $queryProducts->fetchAll(PDO::FETCH_ASSOC);

    // Đếm tổng số sản phẩm
    $queryTotal = $pdo->prepare("SELECT COUNT(*) FROM items_detail WHERE item_id = ?");
    $queryTotal->execute([$item_id]);
    $totalProducts = $queryTotal->fetchColumn();
    $totalPages = ceil($totalProducts / $limit);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel= "stylesheet" href= "items.css"/>
    <link rel="stylesheet" href="header.css" />
</head>
<body>
<!-- FILTER DANH MỤC -->
    <div class="items-filter-cont">
        <div class="items-filter">
            <ul>
                <?php foreach ($items as $item): ?>
                    <li>
                        <a href="items.php?item_id=<?= $item['id'] ?>">
                            <?= htmlspecialchars($item['name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

<!-- HIỂN THỊ SẢN PHẨM -->
    <div class="items-cont">
        <div class="items">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product">
                        <a href="product-detail.php?id=<?= htmlspecialchars($product['id']) ?>">
                            <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>Price: <?= htmlspecialchars(number_format($product['price'], 2)) ?> VND</p>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có sản phẩm nào được tìm thấy.</p>
            <?php endif; ?>
        </div>
    </div>

<!-- PHÂN TRANG -->
    <?php if (!empty($products) && $totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="items.php?item_id=<?= $item_id ?>&page=<?= $i ?>" 
                class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>









<script src="header.js"></script>
</body>
</html>