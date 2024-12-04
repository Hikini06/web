
<?php
require '../config/db-connect.php';
require_once 'functions.php';

if (!isset($_GET['subcategory_id'])) {
    // Chuyển hướng đến URL mặc định
    header("Location: ./danh-muc/1");
    exit; // Dừng thực thi mã PHP sau khi chuyển hướng
}

 // Tạo chuỗi truy vấn cho items_per_page nếu có
 $items_per_page_query = '';
 if (isset($_GET['items_per_page'])) {
     $items_per_page_query = '?items_per_page=' . htmlspecialchars($_GET['items_per_page']);
 }
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
    <title>Tiệm hoa MiMi</title>
    <base href="https://tiemhoamimi.com/">
    <!-- <base href="http://localhost/web-dm-lum/web/"> -->
    <link rel="icon" href="image/mimi-logo-vuong.png" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('categories.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo asset('categories-responsive.css'); ?>"/>
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
        <button class="arrow left-arrow"><i class="fa-solid fa-chevron-left"></i></button>
        <div class="categories-filter">
        <ul>
            <?php foreach ($subcategories as $subcategory): ?>
                <li class="<?= $subcategory['id'] === $subcategory_id ? 'active' : '' ?>">
                    <a href="./danh-muc/<?= htmlspecialchars($subcategory['id']) ?>">
                        <?= htmlspecialchars($subcategory['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        </div>
        <button class="arrow right-arrow"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
    <!-- Categories Products -->
    <div class="categories">
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
            <p>Không có sản phẩm nào trong danh mục này.</p>
        <?php endif; ?>
    </div>

    <!-- Phân trang -->
    <div class="pagination">
        <?php if ($total_pages > 1): ?>
            <!-- Trang đầu tiên -->
            <?php if ($page > 2): ?>
                <a href="./danh-muc/<?= htmlspecialchars($subcategory_id) ?>/page/1<?= $items_per_page_query ?>">1</a>
            <?php endif; ?>

            <!-- Trang trước -->
            <?php if ($page > 1): ?>
                <a href="./danh-muc/<?= htmlspecialchars($subcategory_id) ?>/page/<?= $page - 1 ?><?= $items_per_page_query ?>">
                    <?= $page - 1 ?>
                </a>
            <?php endif; ?>

            <!-- Trang hiện tại -->
            <a class="active"><?= $page ?></a>

            <!-- Trang sau -->
            <?php if ($page < $total_pages): ?>
                <?php if ($page + 1 < $total_pages): ?>
                    <a href="./danh-muc/<?= htmlspecialchars($subcategory_id) ?>/page/<?= $page + 1 ?><?= $items_per_page_query ?>">
                        <?= $page + 1 ?>
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Trang cuối cùng -->
            <?php if ($page < $total_pages): ?>
                <a href="./danh-muc/<?= htmlspecialchars($subcategory_id) ?>/page/<?= $total_pages ?><?= $items_per_page_query ?>">
                    <?= $total_pages ?>
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>



    <!-- FOOTER -->
    <?php include 'footer.php'; ?>


    <script src="<?php echo asset('categories.js'); ?>"></script>
    <script>
        // Script để tự động điều chỉnh items_per_page dựa trên kích thước màn hình
        (function() {
            const url = new URL(window.location.href);
            const urlParams = url.searchParams;
            const currentItemsPerPage = parseInt(urlParams.get('items_per_page')) || 16;
            const screenWidth = window.innerWidth;

            let newItemsPerPage = 16;
            if (screenWidth <= 768) {
                newItemsPerPage = 10;
            }

            if (currentItemsPerPage !== newItemsPerPage) {
                urlParams.set('items_per_page', newItemsPerPage);

                // Reset trang về 1 khi thay đổi items_per_page
                // Kiểm tra nếu URL có chứa /page/X
                const pathParts = url.pathname.split('/');
                const pageIndex = pathParts.indexOf('page');
                if (pageIndex !== -1 && pathParts.length > pageIndex + 1) {
                    // Thay thế số trang hiện tại bằng 1
                    pathParts[pageIndex + 1] = '1';
                    url.pathname = pathParts.join('/');
                } else {
                    // Nếu chưa có /page/X trong đường dẫn, thêm /page/1
                    if (!url.pathname.endsWith('/')) {
                        url.pathname += '/';
                    }
                    url.pathname += 'page/1';
                }

                // Loại bỏ tham số 'page' trong query string nếu có
                url.searchParams.delete('page');

                // Cập nhật URL và tải lại trang
                window.location.href = url.toString();
            }
        })();
    </script>

</body>
</html>
