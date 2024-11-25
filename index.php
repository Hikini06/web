<?php
// index.php

// Bao gồm tệp kết nối cơ sở dữ liệu
require_once 'db-connect.php';

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
</head>
<body>
    <!-- HEADER ĐI THEO MỌI TRANG -->
    <?php include 'header.php'; ?>
    <!-- HEADER ĐI THEO MỌI TRANG END -->

    <!-- PHẦN KHU VỰC PROFILE -->
    <section class="profile-section">
        <div class="profile-section-cont">
            <a href="https://www.google.com.vn/">
                <div class="profile-card">
                    <img src="image/1.webp" alt="Profile Image" />
                    <p>Description 1</p>
                </div>
            </a>
            <a href="https://www.google.com.vn/">
                <div class="profile-card">
                    <img src="image/2.webp" alt="Profile Image" />
                    <p>Description 2</p>
                </div>
            </a>
            <a href="https://www.google.com.vn/">
                <div class="profile-card">
                    <img src="image/3.webp" alt="Profile Image" />
                    <p>Description 3</p>
                </div>
            </a>
            <a href="https://www.google.com.vn/">
                <div class="profile-card">
                    <img src="image/4.webp" alt="Profile Image" />
                    <p>Description 4</p>
                </div>
            </a>
            <a href="https://www.google.com.vn/">
                <div class="profile-card">
                    <img src="image/5.webp" alt="Profile Image" />
                    <p>Description 5</p>
                </div>
            </a>
            <a href="https://www.google.com.vn/">
                <div class="profile-card">
                    <img src="image/6.webp" alt="Profile Image" />
                    <p>Description 6</p>
                </div>
            </a>
            <a href="https://www.google.com.vn/">
                <div class="profile-card">
                    <img src="image/6.webp" alt="Profile Image" />
                    <p>Description 7</p>
                </div>
            </a>
            <a href="https://www.google.com.vn/">
                <div class="profile-card">
                    <img src="image/6.webp" alt="Profile Image" />
                    <p>Description 8</p>
                </div>
            </a>
            <a href="https://www.google.com.vn/">
                <div class="profile-card">
                    <img src="image/6.webp" alt="Profile Image" />
                    <p>Description 9</p>
                </div>
            </a>
        </div>
    </section>
    <!-- PHẦN KHU VỰC PROFILE END -->

    <!-- PHẦN HERO BANNER -->
    <main>
        <div class="hero-banner-cont">
            <section id="hero-banner" class="slideshow-container">
                <div class="mySlides fade" style="background-image: url('image/1.webp')"></div>
                <div class="mySlides fade" style="background-image: url('image/2.webp')"></div>
                <div class="mySlides fade" style="background-image: url('image/3.webp')"></div>
                <div class="mySlides fade" style="background-image: url('image/4.webp')"></div>
                <div class="mySlides fade" style="background-image: url('image/5.webp')"></div>
                <div class="mySlides fade" style="background-image: url('image/6.webp')"></div>
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
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
    </main>
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
    <div class="slider-container-one-upper">
        <h2>Danh sách sản phẩm kẹc kẹc gì đó</h2>
    </div>
    <div class="slider-container-one">
        <button class="arrow left" onclick="slideLeft()">&#10094;</button>
        <!-- Mũi tên trái -->

        <div class="slider">
            <?php foreach ($slider1_products as $product): ?>
                <div class="slider-item-one">
                    <img src="image/upload/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                    <p><?php echo htmlspecialchars($product['name']); ?></p>
                    <button class="product-slider-btn"><?php echo number_format($product['price'], 0, ',', '.') . 'đ'; ?></button>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="arrow right" onclick="slideRight()">&#10095;</button>
        <!-- Mũi tên phải -->
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
    <div class="slider-container-one-upper-two">
        <h2>Danh sách thứ 2 cho sản phẩm kẹc kẹc gì đó</h2>
    </div>
    <div class="slider-container-two">
        <button class="arrow-two left" onclick="slideLeftTwo()">&#10094;</button>
        <!-- Mũi tên trái -->

        <div class="slider-two">
            <?php foreach ($slider2_products as $product): ?>
                <div class="slider-item-two">
                    <img src="image/upload/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                    <p><?php echo htmlspecialchars($product['name']); ?></p>
                    <button class="product-slider-btn-two"><?php echo number_format($product['price'], 0, ',', '.') . 'đ'; ?></button>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="arrow-two right" onclick="slideRightTwo()">&#10095;</button>
        <!-- Mũi tên phải -->
    </div>
    <!-- PHẦN SLIDER 2 END -->

    <!-- FOOTER -->
    <?php include 'footer.php'; ?>

    <!-- Bao gồm tệp JavaScript -->
    <script src="home.js" defer></script>
    <script src="header.js"></script>
</body>
</html>
