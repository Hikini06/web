<?php
require 'db-connect.php';

// Lấy danh sách item_id từ bảng items
$queryItems = $pdo->prepare("SELECT id, name, description FROM items");
$queryItems->execute();
$items = $queryItems->fetchAll(PDO::FETCH_ASSOC);

// Lấy item_id được chọn (nếu có)
$item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : null;

// Lấy thông tin từ bảng items theo item_id
$itemInfo = [];
if ($item_id) {
    $queryItemInfo = $pdo->prepare("SELECT name, description FROM items WHERE id = :item_id");
    $queryItemInfo->bindValue(':item_id', $item_id, PDO::PARAM_INT);
    $queryItemInfo->execute();
    $itemInfo = $queryItemInfo->fetch(PDO::FETCH_ASSOC);
}

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
    <title>Tiệm hoa MiMi</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel= "stylesheet" href= "items.css"/>
    <link rel="stylesheet" href="header.css" />
</head>
<body>

<!-- HEADER -->
  <?php include 'header.php'; ?>

<!-- Hiển thị thông tin từ bảng items -->
<?php if (!empty($itemInfo)): ?>
    <div class="item-info">
        <h1><?= htmlspecialchars($itemInfo['name']) ?></h1>
        <p><?= htmlspecialchars($itemInfo['description']) ?></p>
    </div>
<?php endif; ?>
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
                            <p><?= htmlspecialchars(number_format($product['price'])) ?>đ</p>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có sản phẩm nào được tìm thấy.</p>
            <?php endif; ?>
        </div>
    </div>

<!-- LẤY SẢN PHẨM NGẪU NHIÊN -->
<?php
// Lấy danh sách sản phẩm gợi ý
$suggestedProducts = [];
if (!empty($item_id)) {
    // Bước 1: Lấy subcategory_id của sản phẩm hiện tại từ bảng `items`
    $querySubcategory = $pdo->prepare("
        SELECT subcategory_id FROM items WHERE id = ?
    ");
    $querySubcategory->execute([$item_id]);
    $subcategory_id = $querySubcategory->fetchColumn();

    if (!empty($subcategory_id)) {
        // Bước 2: Lấy danh sách `id` từ bảng `items` có cùng `subcategory_id`
        $queryItems = $pdo->prepare("
            SELECT id FROM items WHERE subcategory_id = ?
        ");
        $queryItems->execute([$subcategory_id]);
        $itemIds = $queryItems->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($itemIds)) {
            // Chuyển danh sách `id` thành chuỗi để sử dụng trong SQL
            $itemIdsStr = implode(',', array_map('intval', $itemIds));

            // Lấy danh sách id sản phẩm hiện tại để loại trừ
            $currentProductIds = array_column($products, 'id');
            $currentProductIdsStr = !empty($currentProductIds) 
                ? implode(',', array_map('intval', $currentProductIds)) 
                : 'NULL';

            // Bước 3: Lấy sản phẩm từ bảng `items_detail` loại trừ những sản phẩm đang hiển thị
            $querySuggestions = $pdo->prepare("
                SELECT img, name, price, id FROM items_detail 
                WHERE item_id IN ($itemIdsStr)
                AND id NOT IN ($currentProductIdsStr)
                ORDER BY RAND()
                LIMIT 8
            ");
            $querySuggestions->execute();
            $suggestedProducts = $querySuggestions->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "Không tìm thấy sản phẩm nào trong bảng items với subcategory_id = $subcategory_id.";
        }
    } else {
        echo "Không tìm thấy subcategory_id tương ứng.";
    }
} else {
    echo "item_id không hợp lệ.";
}
?>







    <?php if (!empty($suggestedProducts)): ?>
        <div class="suggested-items-cont">
            <h2>Phù hợp với bạn</h2>
            <div class="suggested-items">
                <?php foreach (array_chunk($suggestedProducts, 4) as $row): ?>
                    <div class="suggested-row">
                        <?php foreach ($row as $product): ?>
                            <div class="suggested-product">
                                <a href="product-detail.php?id=<?= htmlspecialchars($product['id']) ?>">
                                    <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                                    <p><?= htmlspecialchars(number_format($product['price'])) ?>đ</p>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <p class="no-succgestion">Không có sản phẩm gợi ý nào.</p>
    <?php endif; ?>





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










<?php include 'footer.php'; ?>
<script src="header.js"></script>
</body>
</html>