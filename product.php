<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm - Tiệm hoa mimi</title>
    <!-- FONT CHỮ -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
  />
   <!-- Bootstrap CSS -->
  <!-- <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
  /> -->
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="header.css">

</head>
<body>
  <!-- Thẻ header -->
    <header>
        <div class="header-left">
          <input type="checkbox" id="menu-toggle" class="menu-toggle" />
          <label for="menu-toggle" class="hamburger-menu" aria-label="Toggle menu">
            <span class="hamburger-box">
              <span class="hamburger-inner"></span>
            </span>
          </label>
        </div>
        <div class="logo-container">
          <a href="index.php" class="logo">Tiệm hoa mimi</a>
        </div>
        <div class="header-right">
          <div class="search-icon-container">
            <a href="#" class="search-icon" id="searchToggle">
              <i class="fas fa-search"></i>
            </a>
            <div class="search-container" id="searchContainer">
              <form id="searchForm" action="search-results.html" method="get">
                <input
                  type="text"
                  id="searchBox"
                  name="query"
                  placeholder="Tìm kiếm..."
                />
              </form>
            </div>
            <nav id="menu-nav" class="menu-nav">
              <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="product.php">Sản phẩm</a></li>
                <li><a href="new.html">Tin Tức</a></li>
                <li><a href="about.html">Giới thiệu</a></li>
                <li><a href="contact.html">Liên hệ</a></li>
              </ul>
            </nav>
          </div>
        </div>
    </header>
  <!-- section san pham jim lam -->
    <section class="product-banner-jim">
      <div class="product-banner-jim-child">
        <div class="product-banner-jim-sanpham product-banner-jim-sanpham-one">
          <a class="product-banner-jim-a" href="">ĐÈN NGỦ ▶</a>
          <ul class="product-banner-jim-ul product-banner-jim-ul-one">
            <li class="product-banner-jim-li">ĐÈN TRÒN
              <ul class="product-banner-jim-ultwo">
                <li>Đèn tròn hoa tulip</li>
                <li>Đèn tròn hoa hồng</li>
                <!-- <li>a</li>
                <li>b</li>
                <li>b</li> -->
              </ul>
            </li>
            <li class="product-banner-jim-li">ĐÈN VUÔNG
              <ul class="product-banner-jim-ultwo">
                <li>Đèn vuông hoa tulip</li>
                <li>Đèn vuông hoa hồng</li>
                <li>Đèn vuông noel</li>
                <!-- <li>c</li>
                <li>c</li> -->
              </ul>
            </li>
            <li class="product-banner-jim-li">ĐÈN MÂY
              <ul class="product-banner-jim-ultwo">
                <li>Đèn mây hoa tulip</li>
                <li>Đèn mây hoa hồng</li>
                <!-- <li>d</li>
                <li>d</li>
                <li>d</li> -->
              </ul>
            </li>
            <li class="product-banner-jim-li">ĐÈN THÚ
              <ul class="product-banner-jim-ultwo">
                <li>Đèn thú heo hồng</li>
                <li>Đèn thú vịt vàng</li>
                <li>Đèn thú thỏ trắng</li>
                <!-- <li>e</li>
                <li>e</li> -->
              </ul>
            </li>
            <!-- <li class="product-banner-jim-li">ĐÈN VUÔNG NOEL
              <ul class="product-banner-jim-ultwo">
                <li>f</li>
                <li>f</li>
                <li>f</li>
                <li>f</li>
                <li>f</li>
              </ul>
            </li> -->
          </ul>
        </div>
        <div class="product-banner-jim-sanpham product-banner-jim-sanpham-two">
          <a class="product-banner-jim-a" href="">HOA GẤU BÔNG ▶</a>
          <ul class="product-banner-jim-ul product-banner-jim-ul-two">
            <li class="product-banner-jim-li">GẤU ÔM HOA SÁP
              <ul class="product-banner-jim-ultwo">
                <li>Hồng đậm</li>
                <li>Hồng nhạt</li>
                <!-- <li>a</li>
                <li>b</li>
                <li>b</li> -->
              </ul>
            </li>
            <li class="product-banner-jim-li">GẤU DÂU BÓNG MICA
              <ul class="product-banner-jim-ultwo">
                <li>Mica to hồng đậm</li>
                <li>Mica to hồng nhạt</li>
                <li>Moca lotso</li>
                <!-- <li>c</li>
                <li>c</li> -->
              </ul>
            </li>
            <li class="product-banner-jim-li">GẤU LÔNG XÙ
              <ul class="product-banner-jim-ultwo">
                <li>Mù mặt to</li>
                <li>Xù gấu</li>
                <!-- <li>d</li>
                <li>d</li>
                <li>d</li> -->
              </ul>
            </li>
            <li class="product-banner-jim-li">HOA LEN
              <ul class="product-banner-jim-ultwo">
                <li>Hoa len hồng</li>
                <li>Hoa len xanh</li>
                <li>Hoa len hướng dương vàng</li>
                <!-- <li>e</li>
                <li>e</li> -->
              </ul>
            </li>
            <li class="product-banner-jim-li">HỘP HOA GẤU
              <ul class="product-banner-jim-ultwo">
                <li>Hộp xanh</li>
                <li>Hộp đỏ</li>
                <li>Hộp vàng</li>
                <li>Hộp tím</li>
                <li>Hộp hồng</li>
                <li>Hộp da cam</li>
                <li>Hộp tím than</li>
                <li>Hộp xanh biển</li>
                <li>Hộp xanh biển nhạt</li>
                <li>Hộp xanh biển đậm</li>
                <li>Hộp abc</li>
                <li>Hộp xyz</li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="product-banner-jim-sanpham product-banner-jim-sanpham-three">
          <a class="product-banner-jim-a" href="">SET QUÀ TẶNG ▶</a>
          <ul class="product-banner-jim-ul product-banner-jim-ul-three">
            <li class="product-banner-jim-li">SET NOEL
              <ul class="product-banner-jim-ultwo">
                <li>Set tất noel</li>
                <li>Baby three nel</li>
                <li>Labubu noel</li>
              </ul>
            </li>
            <li class="product-banner-jim-li">SET LOOPY
              <ul class="product-banner-jim-ultwo">
                <li>Loopy mũ hươu</li>
                <li>Mũ dâu</li>
                <li>Mũ cá</li>
                <li>Mũ lợn</li>
                <li>Mũ gà</li>
              </ul>
            </li>
            <li class="product-banner-jim-li">SET VỊT DỖI
              <ul class="product-banner-jim-ultwo">
                <li>Vịt dỗi vàng</li>
                <li>Vịt dỗi xanh</li>
                <li>Vịt dỗi đỏ</li>
                <li>Vịt dỗi tím</li>
                <li>Vịt dỗi hồng</li>
                
              </ul>
            </li>
          </ul>
            </li>
          </ul>
        </div>
        <div class="product-banner-jim-sanpham product-banner-jim-sanpham-four">
          <a class="product-banner-jim-a" href="">GẤU BÔNG ▶</a>
          <ul class="product-banner-jim-ul product-banner-jim-ul-four">
            <li class="product-banner-jim-li">Loopy
              <ul class="product-banner-jim-ultwo">
                <li>Loopy mũ hươu</li>
                <li>mũ gà</li>
                <li>Mũ vịt</li>
                <li>Mũ lợn</li>
                <li>Mũ dê</li>
              </ul>
            </li>
            <li class="product-banner-jim-li">Capybara
              <ul class="product-banner-jim-ultwo">
                <li>Ôm đàn</li>
                <li>Cầm hoa</li>
                <li>Rút mũi</li>
                <li>Rút tai</li>
                <li>Mặc áo ông già</li>
                <li>Mặc áo bà già</li>
              </ul>
            </li>
            <li class="product-banner-jim-li">GẤU ÔM HOA SÁP
              <ul class="product-banner-jim-ultwo">
                <li>Hoa sáp đỏ</li>
                <li>Hoa sáp hồng</li>
                <li>Hoa sáp tím</li>
                <li>Hoa sáp xanh</li>
                <li>Hoa sáp bảy màu đẹp</li>
              </ul>
            </li>
            <!-- <li class="product-banner-jim-li">ĐÈN THÚ
              <ul class="product-banner-jim-ultwo">
                <li>Đèn thú heo hồng</li>
                <li>Đèn thú vịt vàng</li>
                <li>Đèn thú thỏ trắng</li> -->
                <!-- <li>e</li>
                <li>e</li> -->
              </ul>
            </li>
          </ul>
        </div>
        <div class="product-banner-jim-sanpham product-banner-jim-sanpham-all">
          <a class="product-banner-jim-a" href="./product.php">TẤT CẢ SẢN PHẨM</a>
        </div>
      </div>
      

    </section>
  <!-- list sản phẩm main -->
    <section class="product-all-cont">
        <div class="products-container" id="products-container">
            <!-- Sản phẩm sẽ được hiển thị tại đây -->
        </div>

        <div class="pagination" id="pagination">
            <!-- Nút phân trang sẽ được hiển thị tại đây -->
        </div>
    </section>
  <!-- guide section slider -->
  <h3 class="guideSection-h">HƯỚNG DẪN</h3>
  <section class="guideSection-cont">
    <div class="guideSection-slider-wrapper">
      <button class="guideSection-slider-prev-btn">⬅</button>
      <div class="guideSection-image-slider-container">
          <div class="guideSection-image-slider">
              <a href="https://example.com/1" target="_blank">
                  <div class="guideSection-image-card">
                      <img src="image/image/Ảnh Hoa/đèn tròn hoa hồng/b (1).jpg" alt="Slide 1">
                      <button class="guideSection-play-btn">▶</button>
                      <p class="guideSection-image-description">Cách lắp đèn tròn hoa hồng</p>

                  </div>
              </a>
              <a href="https://example.com/2" target="_blank">
                  <div class="guideSection-image-card">
                      <img src="image/image/Ảnh Hoa/Đèn vuông/den-hoa-tulip-17.jpg" alt="Slide 2">
                      <button class="guideSection-play-btn">▶</button>
                      <p class="guideSection-image-description">Cách lắp đèn vuông hoa tulip</p>

                  </div>
              </a>
              <a href="https://example.com/3" target="_blank">
                  <div class="guideSection-image-card">
                      <img src="image/image/Ảnh Hoa/đèn mây hoa hồng/den-ngu-hoa-hong (3).jpeg" alt="Slide 3">
                      <button class="guideSection-play-btn">▶</button>
                      <p class="guideSection-image-description">Lắp đèn mây hoa hồng</p>

                  </div>
              </a>
              <a href="https://example.com/4" target="_blank">
                  <div class="guideSection-image-card">
                      <img src="image/image/Ảnh Hoa/đèn ngủ AD/hoa-anh-dao-6.jpg" alt="Slide 4">
                      <button class="guideSection-play-btn">▶</button>
                      <p class="guideSection-image-description">Cách lắp đèn tròn anh đào</p>

                  </div>
              </a>
              <a href="https://example.com/5" target="_blank">
                  <div class="guideSection-image-card">
                      <img src="image/image/Ảnh Hoa/Đèn Ngủ Hoa Tulip (Tặng kèm túi quà) Nhiều Màu Sắc Thủ Công DIY , Quà Tặng Ý Nghĩa,Đèn Hoa Để Phòng Ngủ, Đèn Trang Trí _ Shopee Việt Nam/SKU-Hồng-01.jpg" alt="Slide 5">
                      <button class="guideSection-play-btn">▶</button>
                      <p class="guideSection-image-description">Lắp đèn tròn hoa tulip</p>

                  </div>
              </a>
          </div>
      </div>
      <button class="guideSection-slider-next-btn">⮕</button>
    </div>
  </section>
  <!-- FOOTER START -->
  <footer class="footer">
    <div class="footer-cont">
      <div class="footer-adress">
        <h3>
          ĐỊA CHỈ
        </h3>
        <p>
          Ngõ 2 Tân Mỹ, Mỹ Đình, Nam Từ Liêm, Hà Nội
        </p>
      </div>
      <div class="footer-contact">
        <h3>
          LIÊN HỆ VỚI BỌN MÌNH
        </h3>
        <div class="footer-icon">
          <a href="https://www.facebook.com/people/Ti%E1%BB%87m-hoa-MiMi/61560867710445/" target="_blank"><div><img src="image/icon/icon-facebook.png" alt=""></div></a>
          <a href="" target="_blank"><div><img src="image/icon/icon-insta.webp"  alt=""></div></a>
          <a href="https://vn.shp.ee/FofnRRP" target="_blank"><div><img src="image/icon/icon-shopee.webp"  alt=""></div></a>
        </div>
        <div class="footer-icon">
          <a href="" target="_blank"><div><img src="image/icon/icon-threads.webp"  alt=""></div></a>
          <a href="https://www.tiktok.com/@phannthaonguyen" target="_blank"><div><img src="image/icon/icon-tiktok.webp" alt=""></div></a>
          <a href="https://zalo.me/84354235669" target="_blank"><div><img src="image/icon/icon-zalo.png" alt=""></div></a>
        </div>
      </div>
      <div class="footer-payment">
        <h3>PHƯƠNG THỨC THANH TOÁN</h3>
        <h4>TECHCOMBANK</h4>
        <P>19033199069019</P>
        <P>PHAN THẢO NGUYÊN</P>
      </div>
    </div>
    <h5 class="footer-h">Tiệm hoa MiMi - Since 2020</h5>
  </footer>
<!-- FOOTER END -->
<script src="product.js"></script>
</body>
</html>