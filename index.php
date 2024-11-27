<?php
// index.php

// Bao gồm tệp kết nối cơ sở dữ liệu
require_once 'db-connect.php';

// Fetch selected products for profile section
try {
    $sql_profiles = "SELECT items_detail.* FROM index_profiles 
                     JOIN items_detail ON index_profiles.product_id = items_detail.id";
    $stmt_profiles = $pdo->prepare($sql_profiles);
    $stmt_profiles->execute();
    $profile_products = $stmt_profiles->fetchAll();
} catch (PDOException $e) {
    die("Lỗi khi lấy sản phẩm đầu trang: " . $e->getMessage());
}

// Fetch selected products for slider1
try {
    $sql_slider1 = "SELECT items_detail.* FROM index_sliders 
                   JOIN items_detail ON index_sliders.product_id = items_detail.id 
                   WHERE index_sliders.slider_type = 'slider1' 
                   ORDER BY index_sliders.display_order ASC";
    $stmt_slider1 = $pdo->prepare($sql_slider1);
    $stmt_slider1->execute();
    $slider1_products = $stmt_slider1->fetchAll();
} catch (PDOException $e) {
    die("Lỗi khi lấy sản phẩm cho slider1: " . $e->getMessage());
}

// Fetch selected products for slider2
try {
    $sql_slider2 = "SELECT items_detail.* FROM index_sliders 
                   JOIN items_detail ON index_sliders.product_id = items_detail.id 
                   WHERE index_sliders.slider_type = 'slider2' 
                   ORDER BY index_sliders.display_order ASC";
    $stmt_slider2 = $pdo->prepare($sql_slider2);
    $stmt_slider2->execute();
    $slider2_products = $stmt_slider2->fetchAll();
} catch (PDOException $e) {
    die("Lỗi khi lấy sản phẩm cho slider2: " . $e->getMessage());
}

$categories = [
    [
        'link' => './categories.php?subcategory_id=1',
        'img'  => './image/1.jpg',
        'name' => 'Bó hoa len Hoa sáp'
    ],
    [
        'link' => './categories.php?subcategory_id=2',
        'img'  => './image/1.jpg',
        'name' => 'Bó hoa gấu bông'
    ],
    [
        'link' => './categories.php?subcategory_id=3',
        'img'  => './image/1.jpg',
        'name' => 'Gấu bông lớn'
    ],
    [
        'link' => './categories.php?subcategory_id=4',
        'img'  => './image/1.jpg',
        'name' => 'Móc khoá'
    ],
    [
        'link' => './categories.php?subcategory_id=5',
        'img'  => './image/1.jpg',
        'name' => 'Ghim cài áo'
    ],
    [
        'link' => './categories.php?subcategory_id=6',
        'img'  => './image/1.jpg',
        'name' => 'Set gấu hoa'
    ],
    [
        'link' => './categories.php?subcategory_id=7',
        'img'  => './image/1.jpg',
        'name' => 'Nến thơm'
    ],
    [
        'link' => './categories.php?subcategory_id=8',
        'img'  => './image/1.jpg',
        'name' => 'Balo Túi xách'
    ],
    [
        'link' => './categories.php?subcategory_id=9',
        'img'  => './image/1.jpg',
        'name' => 'Tất'
    ],
    [
        'link' => './categories.php?subcategory_id=10',
        'img'  => './image/1.jpg',
        'name' => 'Đồ theo trend'
    ],
    [
        'link' => './categories.php?subcategory_id=11',
        'img'  => './image/1.jpg',
        'name' => 'Đèn hình tròn'
    ],
    [
        'link' => './categories.php?subcategory_id=12',
        'img'  => './image/1.jpg',
        'name' => 'Đèn hình vuông'
    ],
    [
        'link' => './categories.php?subcategory_id=13',
        'img'  => './image/1.jpg',
        'name' => 'Đèn đám mây'
    ],
    [
        'link' => './categories.php?subcategory_id=14',
        'img'  => './image/1.jpg',
        'name' => 'Đèn hình thú'
    ],
];

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tiệm hoa MiMi</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/1081860f2a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="header.css"/>
    <link rel="stylesheet" href="home.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="index-responsive.css" />
</head>
<body>
    <!-- HEADER ĐI THEO MỌI TRANG -->
    <?php include 'header.php'; ?>
    <!-- HEADER ĐI THEO MỌI TRANG END -->
    <!-- PHẦN DANH MỤC SẢN PHẨM -->
    <section class="categories-section-cont">
        <div class="categories-section swiper">
            <div class="swiper-wrapper">
                <?php foreach ($categories as $category): ?>
                    <a href="<?php echo htmlspecialchars($category['link']); ?>" class="swiper-slide category-item">
                        <div class="category-content">
                            <img src="<?php echo htmlspecialchars($category['img']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" />
                            <p><?php echo htmlspecialchars($category['name']); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <!-- Nút điều hướng -->
            <div class="swiper-button-next categories-swiper-button-next"></div>
            <div class="swiper-button-prev categories-swiper-button-prev"></div>
        </div>
    </section>
    <!-- PHẦN DANH MỤC SẢN PHẨM END -->
    <!-- PHẦN KHU VỰC PROFILE -->
    <section class="profile-section">
            <div class="profile-section-cont">
                <?php foreach ($profile_products as $product): ?>
                    <a href="product-detail.php?id=<?php echo htmlspecialchars($product['id']); ?>">
                        <div class="profile-card">
                            <img src="image/upload/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                            <p><?php echo htmlspecialchars($product['name']); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
    </section>         
    <!-- PHẦN KHU VỰC PROFILE END -->

    <!-- PHẦN HERO BANNER -->
    <div class="hero-banner-cont">
        <section id="hero-banner" class="swiper hero-banner">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img class = "hero-banner-img" src="image/1.webp" alt="Hoa 1">
                </div>
                <div class="swiper-slide">
                    <img class = "hero-banner-img" src="image/2.webp" alt="Hoa 2">
                </div>
                <div class="swiper-slide">
                    <img class = "hero-banner-img" src="image/3.webp" alt="Hoa 3">
                </div>
                <div class="swiper-slide">
                    <img class = "hero-banner-img" src="image/4.webp" alt="Hoa 4">
                </div>
                <div class="swiper-slide">
                    <img class = "hero-banner-img" src="image/5.webp" alt="Hoa 5">
                </div>
                <div class="swiper-slide">
                    <img class = "hero-banner-img" src="image/6.webp" alt="Hoa 6">
                </div>
                <!-- Thêm thêm slide nếu cần -->
            </div>
            <!-- Nút điều hướng -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <!-- Chấm phân trang -->
            <div class="swiper-pagination"></div>
        </section>
        <section class="banner-divine-to-two">
            <div class="banner-divine-to-two-child">
                <img src="image/2.webp" alt="" />
            </div>
            <div class="banner-divine-to-two-child banner-divine-to-two-child-cont">
                <img src="image/3.webp" alt="" />
                <a class="banner-divine-to-two-child-btn cta-button" href="product.php">Mua ngay</a>
            </div>
        </section>
    </div>  
    <!-- PHẦN HERO BANNER END -->


    <!-- PHẦN SẢN PHẨM NỔI BẬT -->
    <section id="featured-products">
        <h2>Sản phẩm nổi bật</h2>
        <div class="product-grid">
            <a href="">
                <div class="product-card">
                    <img src="image/2.webp" alt="Sản phẩm 1" />
                    <h3>Bó hoa hồng đỏ</h3>
                    <button>159.000đ</button>
                </div>
            </a>
            <a href="">
                <div class="product-card">
                    <img src="image/5.webp" alt="Sản phẩm 2" />
                    <h3>Hoa cưới cầm tay</h3>
                    <button>250.000đ</button>
                </div>
            </a>
            <a href="">
                <div class="product-card">
                    <img src="image/3.webp" alt="Sản phẩm 3" />
                    <h3>Hoa chúc mừng khai trương</h3>
                    <button>129.000đ</button>
                </div>
            </a>
            <a href="">
                <div class="product-card">
                    <img src="image/3.webp" alt="Sản phẩm 4" />
                    <h3>Hoa sinh nhật</h3>
                    <button>390.000đ</button>
                </div>
            </a>
        </div>
    </section>
    <!-- PHẦN SẢN PHẨM NỔI BẬT END -->

    <!-- PHẦN SLIDER 1 - DANH SÁCH SẢN PHẨM -->
    <h2 class= "title-for-all">Sản phẩm này thuộc danh mục gì đó!</h2>
    <div class="swiper-slider-container">
        <div class="swiper slider-container-one">
            <div class="swiper-wrapper">
                <?php foreach ($slider1_products as $product): ?>
                    <a href="product-detail.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="swiper-slide slider-item-one">
                        <img src="image/upload/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                        <p><?php echo htmlspecialchars($product['name']); ?></p>
                        <button class="product-slider-btn"><?php echo number_format($product['price'], 0, ',', '.') . 'đ'; ?></button>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
    <!-- PHẦN SLIDER 1 END -->

    <!-- PHẦN 2 BANNER SẢN PHẨM -->
    <section class="two-product-banner">
        <h2 class="two-product-banner-text">
            Nghĩ ra 1 tiêu đề gì đó cho 2 cái banner này!!!
        </h2>
        <div class="two-product-banner-cont">
            <div class="two-product-banner-child">
                <div class="two-product-banner-child-two">
                    <div class="img-one">
                        <img src="image/noel-background.jpg" alt="" />
                    </div>
                    <div class="img-two">
                        <img src="image/den-vuong-noel.jpeg" alt="" />
                    </div>
                </div>
            </div>
            <div class="two-product-banner-child">
                <div class="two-product-banner-child-two">
                    <div class="img-one">
                        <img src="image/noel-background-two.jfif" alt="" />
                    </div>
                    <div class="img-two">
                        <img src="image/den-tron-hoa-hong.jpg" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- PHẦN 2 BANNER SẢN PHẨM END -->

    <!-- PHẦN SLIDER 2 - DANH SÁCH SẢN PHẨM -->
    <h2 class= "title-for-all">Phàn danh mục sản phẩm chưa đặt tên!</h2>
    <div class="swiper-slider-container">
      <div class="swiper slider-container-two">
        <div class="swiper-wrapper">
                <?php foreach ($slider2_products as $product): ?>
                    <a href="product-detail.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="swiper-slide slider-item-two">
                        <img src="image/upload/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                        <p><?php echo htmlspecialchars($product['name']); ?></p>
                        <button class="product-slider-btn-two"><?php echo number_format($product['price'], 0, ',', '.') . 'đ'; ?></button>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
    <!-- PHẦN SLIDER 2 END -->

    <!-- FOOTER -->
    <?php include 'footer.php'; ?>

    <!-- Bao gồm tệp JavaScript -->
    <script src="home.js" defer></script>
    <script src="header.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js" defer></script>
    <script src="index-responsive.js" defer></script>

</body>
</html>
