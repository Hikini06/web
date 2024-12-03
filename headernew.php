<?php
    include '../config/db-connect.php';

    // Fetch categories along with subcategories and items
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
            c.name ASC, sc.name ASC, i.name ASC
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Organize data into a nested array
    $categories = [];
    foreach ($rows as $row) {
        $cat_id = $row['category_id'];
        $subcat_id = $row['subcategory_id'];

        if (!isset($categories[$cat_id])) {
            $categories[$cat_id] = [
                'id' => $cat_id,
                'name' => $row['category_name'],
                'subcategories' => []
            ];
        }

        if ($subcat_id && !isset($categories[$cat_id]['subcategories'][$subcat_id])) {
            $categories[$cat_id]['subcategories'][$subcat_id] = [
                'id' => $subcat_id,
                'name' => $row['subcategory_name'],
                'items' => []
            ];
        }

        if ($subcat_id && $row['item_id']) {
            $categories[$cat_id]['subcategories'][$subcat_id]['items'][] = [
                'id' => $row['item_id'],
                'name' => $row['item_name']
            ];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- (Các thẻ head như trước) -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <base href="http://localhost/web-dm-lum/web/">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/1081860f2a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="headernew.css"/>
</head>
<body>
    <section class="main">
        <div class="floor-one">
            <div class="header-cont">
                <div class="logo-container">
                    <a href="./trang-chu" class="logo"><img src="image/mimi-logo.png" alt="Logo"></a>
                </div>
                <div class="search-bar-cont">
                    <form action="filter.php" method="GET" class="search-form">
                        <div class="search-input">
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

        <div class="floor-two">
            <nav class="main-menu">
                <ul class="categories">
                    <?php foreach ($categories as $category): ?>
                        <li class="category">
                            <a href="#" class="category-link">
                                <?php echo htmlspecialchars($category['name']); ?>
                                <i class="fa-solid fa-chevron-down"></i>
                            </a>
                            <?php if (!empty($category['subcategories'])): ?>
                                <ul class="subcategories">
                                    <?php foreach ($category['subcategories'] as $subcategory): ?>
                                        <li class="subcategory">
                                            <a href="danh-muc/<?php echo urlencode($subcategory['id']); ?>" class="subcategory-link">
                                                <?php echo htmlspecialchars($subcategory['name']); ?>
                                            </a>
                                            <?php if (!empty($subcategory['items'])): ?>
                                                <ul class="items">
                                                    <?php foreach ($subcategory['items'] as $item): ?>
                                                        <li class="item">
                                                            <a href="san-pham/<?php echo urlencode($item['id']); ?>">
                                                                <?php echo htmlspecialchars($item['name']); ?>
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
                    <div class = "categories-all-product"><a href="./tat-ca-san-pham" style = "text-decoration:none;">TẤT CẢ SẢN PHẨM</a></div>
                </ul>
            </nav>
        </div>

        <div class="floor-three">
            <button class="hamburger" id="hamburger">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
            <div class="categories-mobi" id="categories-mobi">
                <div class="categories-mobi-container">
                    <ul class="categories-mobi-list">
                        <?php foreach ($categories as $category): ?>
                            <li class="category-mobi">
                                <a href="#" class="category-link-mobi" data-category-id="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <ul class="subcategories-mobi">
                        <!-- Subcategories sẽ được thêm động qua JavaScript khi nhấn vào category -->
                    </ul>
                </div>
            </div>
        </div>



    </section>
    <script src="headernew.js"></script>
</body>
</html>
