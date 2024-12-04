<?php
    include '../config/db-connect.php';
    require_once 'functions.php';

    $stmt = $pdo->prepare("
    SELECT 
        c.id AS category_id, 
        c.name AS category_name, 
        sc.id AS subcategory_id, 
        sc.name AS subcategory_name,
        i.id AS item_id,
        i.name AS item_name
    FROM 
        categories c
    LEFT JOIN 
        subcategories sc ON c.id = sc.category_id
    LEFT JOIN 
        items i ON sc.id = i.subcategory_id
    ORDER BY 
        FIELD(c.name, 'ĐÈN NGỦ', 'HOA GẤU BÔNG', 'GẤU BÔNG', 'SET QUÀ TẶNG', 'KHÁC') ASC,
        c.name ASC,
        sc.name ASC,
        i.name ASC
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Organize data into a nested array
    $headerCategories = [];
    foreach ($rows as $row) {
        $cat_id = $row['category_id'];
        $subcat_id = $row['subcategory_id'];

        if (!isset($headerCategories[$cat_id])) {
            $headerCategories[$cat_id] = [
                'id' => $cat_id,
                'name' => $row['category_name'],
                'subcategories' => []
            ];
        }

        if ($subcat_id && !isset($headerCategories[$cat_id]['subcategories'][$subcat_id])) {
            $headerCategories[$cat_id]['subcategories'][$subcat_id] = [
                'id' => $subcat_id,
                'name' => $row['subcategory_name'],
                'items' => []
            ];
        }

        if ($subcat_id && $row['item_id']) {
            $headerCategories[$cat_id]['subcategories'][$subcat_id]['items'][] = [
                'id' => $row['item_id'],
                'name' => $row['item_name']
            ];
        }
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <!-- (Các thẻ head như trước) -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <base href="http://localhost/web-dm-lum/web/"> -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/1081860f2a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?php echo asset('header.css'); ?>"/>
</head>
<body>
    <section class="header-main">
        <div class="header-floor-one">
            <div class="header-cont">
                <div class="header-logo-container">
                    <a href="./trang-chu" class="header-logo"><img src="image/mimi-logo.webp" alt="Logo"></a>
                </div>
                <div class="header-search-bar-cont">
                    <form action="filter.php" method="GET" class="header-search-form">
                        <div class="header-search-input">
                            <input type="text" name="q" placeholder="Tìm kiếm sản phẩm..." required />
                            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
                <div class="header-contact-number">
                    <a href="https://zalo.me/84354235669" target="_blank"><h1>035.4235.669</h1></a>
                </div>
            </div>
        </div>

        <div class="header-floor-two">
            <nav class="header-main-menu">
                <ul class="header-categories">
                    <?php foreach ($headerCategories as $headerCategory): ?>
                        <li class="header-category">
                            <a href="#" class="header-category-link">
                                <?php echo htmlspecialchars($headerCategory['name']); ?>
                                <i class="fa-solid fa-chevron-down"></i>
                            </a>
                            <?php if (!empty($headerCategory['subcategories'])): ?>
                                <ul class="header-subcategories">
                                    <?php foreach ($headerCategory['subcategories'] as $headerSubcategory): ?>
                                        <li class="header-subcategory">
                                            <a href="danh-muc/<?php echo urlencode($headerSubcategory['id']); ?>" class="header-subcategory-link">
                                                <?php echo htmlspecialchars($headerSubcategory['name']); ?>
                                            </a>
                                            <?php if (!empty($headerSubcategory['items'])): ?>
                                                <ul class="header-items">
                                                    <?php foreach ($headerSubcategory['items'] as $headerItem): ?>
                                                        <li class="header-item">
                                                            <a href="san-pham/<?php echo urlencode($headerItem['id']); ?>">
                                                                <?php echo htmlspecialchars($headerItem['name']); ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    <div class="header-categories-all-product"><a href="./tat-ca-san-pham" style="text-decoration:none;">TẤT CẢ SẢN PHẨM</a></div>
                </ul>
            </nav>
        </div>

        <div class="header-floor-three">
            <div class="header-hamburger-cont">
                <button class="header-hamburger" id="header-hamburger">
                    <span class="header-hamburger-line"></span>
                    <span class="header-hamburger-line"></span>
                    <span class="header-hamburger-line"></span>
                </button>
            </div>
            <div class="header-categories-mobi" id="header-categories-mobi">
                <div class="header-categories-mobi-container">
                    <ul class="header-categories-mobi-list">
                        <?php foreach ($headerCategories as $headerCategory): ?>
                            <li class="header-category-mobi">
                                <a href="#" class="header-category-link-mobi" data-category-id="<?php echo $headerCategory['id']; ?>">
                                    <?php echo htmlspecialchars($headerCategory['name']); ?>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <a href="./tat-ca-san-pham" class="header-categories-mobi-list-all" style="text-decoration:none">TẤT CẢ SẢN PHẨM</a>
                    </ul>
                    <ul class="header-subcategories-mobi">
                        <!-- Subcategories sẽ được thêm động qua JavaScript khi nhấn vào category -->
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fetch/3.6.2/fetch.min.js"></script>
    <script src="<?php echo asset('header.js'); ?>" defer></script>
</body>
</html>
