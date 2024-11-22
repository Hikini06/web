<!DOCTYPE html>
<html lang="en">
  >
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tiệm hoa mimi</title>
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    /> -->
      <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <!-- <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    /> -->
    <link rel="stylesheet" href="home.css" />
    <link rel="stylesheet" href="header.css" />
  </head>
  <body>
    <header>
      <div class="header-cont">
        <div class="header-left">
          <input type="checkbox" id="menu-toggle" class="menu-toggle" />
          <label
            for="menu-toggle"
            class="hamburger-menu"
            aria-label="Toggle menu"
          >
            <span class="hamburger-box">
              <span class="hamburger-inner"></span>
            </span>
          </label>
        </div>
        <div class="logo-container">
          <a href="index.html" class="logo">Tiệm hoa mimi</a>
        </div>
        <!-- <div class="header-right">
          <div class="search-icon-container">
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
              <ul id="menu-nav-ul">
                <li><a href="index.html">Trang chủ</a></li>
            
                <li>
                  <div class="dropdown">
                    <a href="product.php" class="dropbtn">Sản phẩm</a>
                    <div class="dropdown-content">
                      <div class="dropdown_1 dropdown-content-list">
                        <a href="sp1" class="dropbtn_1 dropbtn-all">Đèn ngủ</a>
                        <ul class="dropdown_1 dropdown-content-list-ul">
                          <li> <a href="sp1.1">Đèn tròn tulip</a></li>
                          <li><a href="sp1.2">Đèn trong hoa hồng</a></li>
                          <li><a href="sp1.3">Đèn trong hoa anh đào</a></li>
                          <li><a href="sp1.4">Đèn vuông tulip</a></li>
                          <li><a href="sp1.5">Đèn vuông hoa hồng</a></li>
                          <li> <a href="sp1.6">Đèn vuông noel</a></li>
                          <li><a href="sp1.7">Đèn mây tulip</a></li>
                          <li> <a href="sp1.8">Đèn mây hoa hồng</a></li>
                          <li><a href="sp1.9">Đèn thú</a></li>
                        </ul>
                        
                      </div>
                      <div class="dropdown_2 dropdown-content-list">
                        <a href="sp2" class="dropbtn_2 dropbtn-all">Hoa gấu bông</a>
                        <ul class="dropdown-content-list-ul">
                          <li><a href="sp2.2">Gấu ôm hoa sáp</a></li>
                          <li><a href="sp2.3">Gấu dâu bóng mica</a></li>
                          <li><a href="sp2.1">Gấu lông xù</a></li>
                          <li><a href="sp2.4">Hoa len</a></li>
                          <li><a href="sp2.5">Hộp hoa gấu</a></li>
                          <li><a href="sp2.6">Hoa gấu để bàn</a></li>
                          <li><a href="sp2.7">Bó loopy</a></li>
                          <li><a href="sp2.8">Bó tí hon</a></li>
                          <li><a href="sp2.9">Bó hoa thỏ</a></li>
                          <li><a href="sp2.10">Bó gấu trúc</a></li>
                          <li><a href="sp2.11">Bó capybara</a></li>
                          <li><a href="sp2.12">Bó hoa noel</a></li>
                          <li><a href="sp2.13">Bó gấu mây</a></li>
                          <li><a href="sp2.14">Bó loopy tai thỏ</a></li>
                          <li><a href="sp2.15">Bó kem hoa gấu</a></li>
                          <li><a href="sp2.16">Bó gấu dâu lotso</a></li>
                          <li><a href="sp2.17">Bó heo hồng đất nặn</a></li>
                          <li><a href="sp2.18">Bó mini loopy,labubu</a></li>
                        </ul>
                      
                      </div>
                      <div class="dropdown_3 dropdown-content-list">
                        <a href="sp3" class="dropbtn_3 dropbtn-all">Gấu bông</a>
                        <ul class="dropdown-content-list-ul">
                          <li><a href="sp3.1">Loop 25cm</a></li>
                          <li><a href="sp3.2">Capybara</a></li>
                         <li> <a href="sp3.3">Capybara kỳ lân</a></li>
                          <li><a href="sp3.4">Capybara thể thao</a></li>
                          <li><a href="sp3.5">Gâu ôm hoa sáp</a></li>

                        </ul>
                      </div>
                      <div class="dropdown_4 dropdown-content-list">
                        <a href="sp4" class="dropbtn_4 dropbtn-all">Gấu bông mini</a>
                        <ul class="dropdown-content-list-ul">
                          <li><a href="sp4.1">Lalubu hoa quả</a></li>
                          <li><a href="sp4.2">Lalubu đeo nơ</a></li>
                          <li><a href="sp4.3">Lalubu bánh vòng</a></li>
                          <li><a href="sp4.4">Lalubu mặt hoa</a></li>
                          <li><a href="sp4.5">Capybara nhỏ</a></li>
                          <li><a href="sp4.6">Capybara kem</a></li>
                          <li><a href="sp4.7">Baby three</a></li>
                          <li><a href="sp4.8">Gấu bông vịt dỗi</a></li>
                          <li><a href="sp4.9">Loopy đội mũ</a></li>
                          <li><a href="sp4.10">Tiểu cường</a></li>
                          <li><a href="sp4.11">Vịt cute</a></li>
                        </ul>
                      </div>
                      <div class="dropdown_5 dropdown-content-list">
                        <a href="sp5" class="dropbtn_5 dropbtn-all">Set quà tặng</a>
                        <ul class="dropdown-content-list-ul">
                          <li><a href="sp5.1">Set vịt dỗi</a></li>
                          <li><a href="sp5.2">Set tất noel</a></li>
                          <li><a href="sp5.3">Set labubu</a></li>
                          <li><a href="sp5.4">Set quà loopy</a></li>
                          <li><a href="sp5.5">Set baby three noel</a></li>
                        </ul>
                      </div>
                      <div class="dropdown_6 dropdown-content-list">
                        <a href="sp6" class="dropbtn_6 ">Hoa sáp</a>
                        <ul class="dropdown-content-list-ul">
                          <li><a href="sp6.1">Gấu ôm hoa sáp</a></li>
                          <li><a href="sp6.2">Hoa sáp hộp bạc</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </li>
                <li><a href="new.html">Tin Tức</a></li>
                <li><a href="about.html">Giới thiệu</a></li>
                <li><a href="contact.html">Liên hệ</a></li>
              </ul>
            </nav>
            <a href="#" class="search-icon" id="searchToggle">
              <i class="fas fa-search"></i>
            </a>
          </div>
        </div> -->
      </div>




    </header>
    <!-- <section class="top-banner">
      Mua ngay sản phẩm mới ra mắt với ngập tràn ưu đãi hấp dẫn
      <a href="#">Tại Đây!</a>
    </section> -->
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
            <p>Description 3</p>
          </div>
        </a>
        <a href="https://www.google.com.vn/">
          <div class="profile-card">
            <img src="image/5.webp" alt="Profile Image" />
            <p>Description 3</p>
          </div>
        </a>
        <a href="https://www.google.com.vn/">
          <div class="profile-card">
            <img src="image/6.webp" alt="Profile Image" />
            <p>Description 3</p>
          </div>
        </a>
        <a href="https://www.google.com.vn/">
          <div class="profile-card">
            <img src="image/6.webp" alt="Profile Image" />
            <p>Description 3</p>
          </div>
        </a>
        <a href="https://www.google.com.vn/">
          <div class="profile-card">
            <img src="image/6.webp" alt="Profile Image" />
            <p>Description 3</p>
          </div>
        </a>
        <a href="https://www.google.com.vn/">
          <div class="profile-card">
            <img src="image/6.webp" alt="Profile Image" />
            <p>Description 3</p>
          </div>
        </a>
      </div>
    </section>
    <main>
      <div class="hero-banner-cont">
        <section id="hero-banner" class="slideshow-container">
          <!-- <div class="hero-banner-img1"> -->
          <!-- <div
            class="mySlides fade"
            style="background-image: url('image/banner.webp')"
          ></div> -->
          <div
            class="mySlides fade"
            style="background-image: url('image/1.webp')"
          ></div>
          <div
            class="mySlides fade"
            style="background-image: url('image/2.webp')"
          ></div>
          <div
            class="mySlides fade"
            style="background-image: url('image/3.webp')"
          ></div>
          <div
            class="mySlides fade"
            style="background-image: url('image/4.webp')"
          ></div>
          <div
            class="mySlides fade"
            style="background-image: url('image/5.webp')"
          ></div>
          <div
            class="mySlides fade"
            style="background-image: url('image/6.webp')"
          ></div>
          <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
          <a class="next" onclick="plusSlides(1)">&#10095;</a>
          <!-- </div>   -->
          <!-- <div class="hero-banner-img2">
              <div style="background-image: url('image/1.webp');"></div>
              <div style="background-image: url('image/4.webp');"></div>
            </div> -->
        </section>
        <section class="banner-divine-to-two">
          <div class="banner-divine-to-two-child">
            <img src="image/2.webp" alt="" />
          </div>
          <div
            class="banner-divine-to-two-child banner-divine-to-two-child-cont"
          >
            <img src="image/3.webp" alt="" />
            <a
              class="banner-divine-to-two-child-btn"
              href="product.php"
              class="cta-button"
              >Mua ngay</a
            >
          </div>
        </section>
      </div>

      <!-- <div class="hero-content"> -->
      <!-- </div> -->
    </main>
    <section id="featured-products">
      <h2>Sản phẩm nổi bật</h2>
      <div class="product-grid">
        <a href="">
          <div class="product-card">
            <img src="image/2.webp" alt="Sản phẩm 1" />
            <h3>Bó hoa hồng đỏ</h3>
            <!-- <p>Bó hoa 12 bông hồng đỏ tươi thắm</p> -->
            <button>159.000đ</button>
          </div>
        </a>
        <a href="">
          <div class="product-card">
            <img src="image/5.webp" alt="Sản phẩm 2" />
            <h3>Hoa cưới cầm tay</h3>
            <!-- <p>Bó hoa cưới đẹp lung linh cho cô dâu</p> -->
            <button>250.000đ</button>
          </div>
        </a>
        <a href="">
          <div class="product-card">
            <img src="image/3.webp" alt="Sản phẩm 3" />
            <h3>Hoa chúc mừng khai trương</h3>
            <!-- <p>Lẵng hoa tươi chúc mừng khai trương</p> -->
            <button>129.000đ</button>
          </div>
        </a>
        <a href="">
          <div class="product-card">
            <img src="image/3.webp" alt="Sản phẩm 4" />
            <h3>Hoa sinh nhật</h3>
            <!-- <p>Bó hoa tươi thắm cho ngày sinh nhật</p> -->
            <button>390.000đ</button>
          </div>
        </a>
      </div>
    </section>
  </section> 
    <div class="slider-container-one-upper">
      <h2>Danh sách sản phẩm kẹc kẹc gì đó</h2>
    </div>
    <!-- slider jim làm   -->
    <div class="slider-container-one">
      <button class="arrow left" onclick="slideLeft()">&#10094;</button>
      <!-- Mũi tên trái -->

      <div class="slider">
        <div class="slider-item-one">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn">119.000đ</button>
        </div>
        <div class="slider-item-one">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn">129.000đ</button>
        </div>
        <div class="slider-item-one">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn">140.000đ</button>
        </div>
        <div class="slider-item-one">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn">259.000đ</button>
        </div>
        <div class="slider-item-one">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn">199.000đ</button>
        </div>
        <div class="slider-item-one">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn">79.000đ</button>
        </div>
        <div class="slider-item-one">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn">235.000đ</button>
        </div>
        <div class="slider-item-one">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn">190.000đ</button>
        </div>
        <div class="slider-item-one">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn">248.000đ</button>
        </div>
        <div class="slider-item-one">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn">114.000đ</button>
        </div>
      </div>

      <button class="arrow right" onclick="slideRight()">&#10095;</button>
      <!-- Mũi tên phải -->
    </div>
    <!-- slider jim làm end -->

    <!-- 2 banner sản phẩm start -->
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
    <!-- banner 2 sản phẩm end -->

    <!-- slider 2 jim làm -->
    <div class="slider-container-one-upper-two">
      <h2>Danh sách thứ 2 cho sản phẩm kẹc kẹc gì đó</h2>
    </div>
    <div class="slider-container-two">
      <button class="arrow-two left" onclick="slideLeftTwo()">&#10094;</button>
      <!-- Mũi tên trái -->

      <div class="slider-two">
        <div class="slider-item-two">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn-two">117.000đ</button>
        </div>
        <div class="slider-item-two">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn-two">250.000đ</button>
        </div>
        <div class="slider-item-two">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn-two">99.000đ</button>
        </div>
        <div class="slider-item-two">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn-two">269.000đ</button>
        </div>
        <div class="slider-item-two">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn-two">140.000đ</button>
        </div>
        <div class="slider-item-two">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn-two">239.000đ</button>
        </div>
        <div class="slider-item-two">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn-two">148.000đ</button>
        </div>
        <div class="slider-item-two">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn-two">250.000đ</button>
        </div>
        <div class="slider-item-two">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn-two">119.000đ</button>
        </div>
        <div class="slider-item-two">
          <img src="./image/1.webp" alt="" />
          <p>abc</p>
          <button class="product-slider-btn-two">218.000đ</button>
        </div>
      </div>

      <button class="arrow-two right" onclick="slideRightTwo()">
        &#10095;
      </button>
      <!-- Mũi tên phải -->
    </div>
    <!-- slider 2 jim làm end -->

    <!-- <section id="product-categories">
      <h2>Danh mục sản phẩm</h2>
      <div class="category-grid">
        <a href="product.php#wedding" class="category-item">
          <img src="path/to/wedding-category.jpg" alt="Hoa cưới" />
          <h3>Hoa cưới</h3>
        </a>
        <a href="product.php#birthday" class="category-item">
          <img src="path/to/birthday-category.jpg" alt="Hoa sinh nhật" />
          <h3>Hoa sinh nhật</h3>
        </a>
        <a href="product.php#congratulations" class="category-item">
          <img src="path/to/congrats-category.jpg" alt="Hoa chúc mừng" />
          <h3>Hoa chúc mừng</h3>
        </a>
        <a href="product.php#love" class="category-item">
          <img src="path/to/love-category.jpg" alt="Hoa tình yêu" />
          <h3>Hoa tình yêu</h3>
        </a>
      </div>
    </section> -->

    <!-- <section id="about-us">
      <h2>Về chúng tôi</h2>
      <p>
        Tiệm hoa mimi là nơi bạn có thể tìm thấy những bó hoa tươi đẹp nhất với
        giá cả phải chăng. Chúng tôi cam kết mang đến cho khách hàng những trải
        nghiệm mua sắm tuyệt vời nhất.
      </p>
    </section> -->

    <!-- <section id="customer-reviews">
      <h2>Khách hàng nói gì về chúng tôi</h2>
      <div class="review-grid">
        <div class="review-item">
          <p>"Hoa luôn tươi và đẹp, dịch vụ rất tốt!"</p>
          <p class="reviewer">- Nguyễn Văn A</p>
        </div>
        <div class="review-item">
          <p>"Đặt hoa rất nhanh chóng và thuận tiện."</p>
          <p class="reviewer">- Trần Thị B</p>
        </div>
        <div class="review-item">
          <p>"Giá cả hợp lý, chất lượng sản phẩm tuyệt vời."</p>
          <p class="reviewer">- Lê Văn C</p>
        </div>
      </div>
    </section> -->


  <!-- FOOTER START -->
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

    <script src="home.js" defer></script>
  </body>
</html>