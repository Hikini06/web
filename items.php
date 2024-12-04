<?php
require '../config/db-connect.php';
require_once 'functions.php';

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

// LẤY SẢN PHẨM GỢI Ý
$suggestedProducts = [];
$randomMode = false; // Biến để xác định chế độ hiển thị
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

            // Kiểm tra nếu số lượng sản phẩm gợi ý chưa đủ 8
            if (count($suggestedProducts) < 8) {
                $remaining = 8 - count($suggestedProducts);

                // Bước 4: Lấy các item_id khác, sắp xếp theo độ gần với item_id hiện tại
                $queryOtherItems = $pdo->prepare("
                    SELECT id FROM items 
                    WHERE id NOT IN ($itemIdsStr)
                    ORDER BY ABS(id - ?)
                    LIMIT 20
                ");
                $queryOtherItems->execute([$item_id]);
                $otherItemIds = $queryOtherItems->fetchAll(PDO::FETCH_COLUMN);

                if (!empty($otherItemIds)) {
                    // Chuyển danh sách `id` thành chuỗi để sử dụng trong SQL
                    $otherItemIdsStr = implode(',', array_map('intval', $otherItemIds));

                    // Lấy danh sách id sản phẩm đã lấy từ bước trước để loại trừ
                    $fetchedProductIds = array_column($suggestedProducts, 'id');
                    if (!empty($fetchedProductIds)) {
                        $fetchedProductIdsStr = implode(',', array_map('intval', $fetchedProductIds));
                    } else {
                        $fetchedProductIdsStr = 'NULL';
                    }

                    // Bước 5: Lấy thêm sản phẩm từ các item_id khác, loại trừ các sản phẩm đã lấy
                    $queryAdditionalSuggestions = $pdo->prepare("
                        SELECT img, name, price, id FROM items_detail 
                        WHERE item_id IN ($otherItemIdsStr)
                        AND id NOT IN ($fetchedProductIdsStr, $currentProductIdsStr)
                        ORDER BY RAND()
                        LIMIT ?
                    ");
                    $queryAdditionalSuggestions->bindValue(1, $remaining, PDO::PARAM_INT);
                    $queryAdditionalSuggestions->execute();
                    $additionalSuggested = $queryAdditionalSuggestions->fetchAll(PDO::FETCH_ASSOC);

                    // Gộp các sản phẩm gợi ý thêm vào danh sách chính
                    $suggestedProducts = array_merge($suggestedProducts, $additionalSuggested);
                }
            }
        } else {
            // Không tìm thấy item nào trong cùng subcategory_id
            // Không cần echo ở đây vì có thể gây lỗi giao diện
            // Thay vào đó, bạn có thể ghi log hoặc xử lý theo cách khác
            // echo "Không tìm thấy sản phẩm nào trong bảng items với subcategory_id = $subcategory_id.";
        }
    } else {
        // Không tìm thấy subcategory_id
        // Không cần echo ở đây vì có thể gây lỗi giao diện
        // Thay vào đó, bạn có thể ghi log hoặc xử lý theo cách khác
        // echo "Không tìm thấy subcategory_id tương ứng.";
    }
}

// Kiểm tra nếu không có sản phẩm gợi ý nào sau tất cả các bước
if (empty($suggestedProducts)) {
    // Lấy 8 sản phẩm ngẫu nhiên từ items_detail, loại trừ các sản phẩm đang hiển thị
    $currentProductIds = array_column($products, 'id');
    $currentProductIdsStr = !empty($currentProductIds) 
        ? implode(',', array_map('intval', $currentProductIds)) 
        : 'NULL';

    $queryRandomSuggestions = $pdo->prepare("
        SELECT img, name, price, id FROM items_detail
        WHERE id NOT IN ($currentProductIdsStr)
        ORDER BY RAND()
        LIMIT 8
    ");
    $queryRandomSuggestions->execute();
    $randomSuggestedProducts = $queryRandomSuggestions->fetchAll(PDO::FETCH_ASSOC);

    // Gán vào $suggestedProducts nếu có
    if (!empty($randomSuggestedProducts)) {
        $suggestedProducts = $randomSuggestedProducts;
        $randomMode = true; // Biến để biết rằng đây là sản phẩm ngẫu nhiên
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiệm hoa MiMi</title>
    <base href="https://tiemhoamimi.com/">
    <link rel="icon" href="./image/mimi-logo-vuong.png" type="image/png">
    <link rel="icon" href="./image/mimi-logo-vuong.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('items.css'); ?>"/>
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
                    <a href="./san-pham/<?= $item['id'] ?>">
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
                    <a href="chi-tiet-san-pham/<?= htmlspecialchars($product['id']) ?>">
                        <img src="image/upload/<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
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

<!-- LẤY SẢN PHẨM GỢI Ý HOẶC NGẪU NHIÊN -->
<?php if (!empty($suggestedProducts)): ?>
    <div class="suggested-items-cont">
        <h2>
            <?php echo isset($randomMode) && $randomMode ? "Sản phẩm ngẫu nhiên" : "Phù hợp với bạn"; ?>
        </h2>
        <div class="suggested-items">
            <?php foreach (array_chunk($suggestedProducts, 4) as $row): ?>
                <div class="suggested-row">
                    <?php foreach ($row as $product): ?>
                        <div class="suggested-product">
                            <a href="chi-tiet-san-pham/<?= htmlspecialchars($product['id']) ?>">
                                <img src="image/upload/<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <h3><?= htmlspecialchars($product['name']) ?></h3>
                                <p><?= htmlspecialchars(number_format($product['price'])) ?>đ</p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php elseif (empty($suggestedProducts) && empty($randomSuggestedProducts)): ?>
    <p class="no-succgestion">Không có sản phẩm gợi ý nào.</p>
<?php endif; ?>

<!-- PHÂN TRANG -->
<?php if (!empty($products) && $totalPages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/san-pham/<?= htmlspecialchars($item_id) ?>/page/<?= $i ?>" 
                class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
</body>
</html>
