<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiệm hoa MiMi</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/1081860f2a.js" crossorigin="anonymous"></script>
    <link rel= "stylesheet" href= "header.css"/>
    <link rel= "stylesheet" href= "header-responsive.css"/>
</head>
<body>
       <!-- HEADER ĐI THEO MỌI TRANG -->
       <section class="main-header-important">
      <header>
        <div class="header-cont">
          <div class="header-left">
            <!-- Nút Hamburger -->
            <button class="hamburger" id="hamburger">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
            </button>
          </div>
          <div class="logo-container">
            <a href="index.php" class="logo"><img src="image/mimi-logo.png" alt=""></a>
            <!-- <a href="index.php" class="logo-mobi">MiMi</a> -->
          </div>
          <div class="search-bar-cont">
            <form action="filter.php" method="GET" class="search-form">
                <div class="search-input">
                    <input type="text" name="q" placeholder="Tìm kiếm sản phẩm..." required />
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
          </div>
          <div class = "header-contact-number">
            <a href="https://zalo.me/84354235669" target="_blank" ><h1>035.4235.669</h1></a>
          </div>
        </div>
      </header>
      <!-- section san pham jim lam -->
      <section class="product-banner-jim">
        <div class="product-banner-jim-child">
          <div class="product-banner-jim-sanpham product-banner-jim-sanpham-one">
            <a class="product-banner-jim-a" href="">ĐÈN NGỦ <i class="fa-solid fa-chevron-down"></i><i class="fa-solid fa-chevron-right"></i></a>
          </div>
          <div class="product-banner-jim-sanpham product-banner-jim-sanpham-two">
            <a class="product-banner-jim-a" href="">HOA GẤU BÔNG <i class="fa-solid fa-chevron-down"></i><i class="fa-solid fa-chevron-right"></i></a>
          </div>
          <div class="product-banner-jim-sanpham product-banner-jim-sanpham-three">
            <a class="product-banner-jim-a" href="">SET QUÀ TẶNG <i class="fa-solid fa-chevron-down"></i><i class="fa-solid fa-chevron-right"></i></a>
                        
          </div>
          <div class="product-banner-jim-sanpham product-banner-jim-sanpham-four">
            <a class="product-banner-jim-a" href="">GẤU BÔNG <i class="fa-solid fa-chevron-down"></i><i class="fa-solid fa-chevron-right"></i></a>
            
          </div>
          <div class="product-banner-jim-sanpham product-banner-jim-sanpham-five">
            <a class="product-banner-jim-a" href="">KHÁC <i class="fa-solid fa-chevron-down"></i><i class="fa-solid fa-chevron-right"></i></a>
            
          </div>
          <div class="product-banner-jim-sanpham product-banner-jim-sanpham-all">
            <a class="product-banner-jim-a" href="./product.php">TẤT CẢ SẢN PHẨM</a>
          </div>
        </div>
        <div class="product-banner-jim-table">
          <ul class="product-banner-jim-table-ul product-banner-jim-table-ul-one">
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=11">ĐÈN DẠNG TRÒN <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./items.php?item_id=47">Đèn tròn hoa tulip</a></li>
                <li><a href="./items.php?item_id=48">Đèn tròn hoa hồng</a></li>
                <li><a href="./items.php?item_id=49">Hoa anh đào</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=12">ĐÈN DẠNG VUÔNG <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./items.php?item_id=56">Đèn vuông hoa tulip</a></li>
                <li><a href="./items.php?item_id=57">Đèn vuông hoa hồng</a></li>
                <li><a href="./items.php?item_id=58">Đèn vuông noel</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=13">ĐÈN ĐÁM MÂY <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./items.php?item_id=53">Đèn mây hoa tulip</a></li>
                <li><a href="./items.php?item_id=54">Đèn mây hoa hồng</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=14">ĐÈN THÚ <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./product-detail.php?id=335">Đèn thú heo hồng</a></li>
                <li><a href="./product-detail.php?id=333">Đèn thú vịt vàng</a></li>
                <li><a href="./product-detail.php?id=334">Đèn thú thỏ trắng</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="product-banner-jim-table-two">
          <ul class="product-banner-jim-table-ul product-banner-jim-table-ul-one">
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=1">BÓ HOA LEN, HOA SÁP <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./items.php?item_id=1">Hoa len thỏ</a></li>
                <li><a href="./items.php?item_id=2">Bó hoa len</a></li>
                <li><a href="./items.php?item_id=3">Hoa sáp</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=2">BÓ HOA GẤU BÔNG <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./items.php?item_id=4">Bó hoa gấu hoạt hình</a></li>
                <li><a href="./items.php?item_id=5">Bó dạng đứng để bàn</a></li>
                <li><a href="./items.php?item_id=6">Hộp hoa gấu bông</a></li>
                <li><a href="./items.php?item_id=7">Hoa gấu hình kem</a></li>
                <li><a href="./items.php?item_id=8">Hoa gấu lông xù</a></li>
                <li><a href="./items.php?item_id=9">Gấu ôm hoa sáp</a></li>
                <li><a href="./items.php?item_id=10">Hoa heo hồng đất sét</a></li>
                <li><a href="./items.php?item_id=11">Hoa gấu 1 bông</a></li>
                <li><a href="./items.php?item_id=12">Bó hoa tí hon</a></li>
                <li><a href="./items.php?item_id=13">Các bó gấu Loopy</a></li>
                <li><a href="./items.php?item_id=14">Bó Loopy tai thỏ</a></li>
                <li><a href="./items.php?item_id=15">Các mẫu Capybara</a></li>
                <li><a href="./items.php?item_id=16">Các mẫu gấu trúc</a></li>
                <li><a href="./items.php?item_id=17">Các mẫu gấu dâu</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="product-banner-jim-table-three">
          <ul class="product-banner-jim-table-ul product-banner-jim-table-ul-one">
            <li class="product-banner-jim-li">
              <a href="./items.php?item_id=34">SET Loopy <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./product-detail.php?id=250">Loopy heo</a></li>
                <li><a href="./product-detail.php?id=251">Loopy gấu dâu</a></li>
                <li><a href="./product-detail.php?id=252">Loopy Kuromi</a></li>
                <li><a href="./product-detail.php?id=253">Loopy cá hề</a></li>
                <li><a href="./product-detail.php?id=254">Loopy gấu vàng</a></li>
                <li><a href="./product-detail.php?id=255">Loopy ong vàng</a></li>
                <li><a href="./product-detail.php?id=256">Loopy cá xanh khủng long</a></li>
                <li><a href="./product-detail.php?id=257">Loopy mũ cá</a></li>
                <li><a href="./product-detail.php?id=258">Set ghim cài 1</a></li>
                <li><a href="./product-detail.php?id=259">Set ghim cài 2</a></li>
                <li><a href="./product-detail.php?id=260">Set ghim cài Capybara</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./items.php?item_id=35">SET vịt hoa <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./product-detail.php?id=261">Vịt mặt hoa</a></li>
                <li><a href="./product-detail.php?id=262">Vịt mũ ếch</a></li>
                <li><a href="./product-detail.php?id=263">Vịt mũ xanh</a></li>
                <li><a href="./product-detail.php?id=264">Vịt ôm cá</a></li>
                <li><a href="./product-detail.php?id=265">Vịt ôm gối</a></li>
                <li><a href="./product-detail.php?id=266">Vịt nơ hồng</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./items.php?item_id=36">SET Baby Three <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./product-detail.php?id=267">Thỏ hồng</a></li>
                <li><a href="./product-detail.php?id=268">Cáo</a></li>
                <li><a href="./product-detail.php?id=269">Voi xanh</a></li>
                <li><a href="./product-detail.php?id=270">Cừu</a></li>
                <li><a href="./product-detail.php?id=271">Gấu trúc</a></li>
                <li><a href="./product-detail.php?id=272">Khủng long</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./items.php?item_id=37">SET Labubu <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./product-detail.php?id=273">Labubu xanh</a></li>
                <li><a href="./product-detail.php?id=274">Labubu hồng</a></li>
                <li><a href="./product-detail.php?id=275">Labubu tím</a></li>
                <li><a href="./product-detail.php?id=276">Labubu nâu</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./items.php?item_id=38">SET tất và nến <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./product-detail.php?id=277">Set tiểu Cường</a></li>
                <li><a href="./product-detail.php?id=278">Nến xanh lá</a></li>
                <li><a href="./product-detail.php?id=279">Nến xanh neon</a></li>
                <li><a href="./product-detail.php?id=280">Nến vàng</a></li>
                <li><a href="./product-detail.php?id=281">Nến đỏ</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./items.php?item_id=39">SET Capybara <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./product-detail.php?id=282">Capybara hồng</a></li>
                <li><a href="./product-detail.php?id=283">Capybara kem hồng</a></li>
                <li><a href="./product-detail.php?id=284">Capybara kem nâu</a></li>
                <li><a href="./product-detail.php?id=285">Capybara kem xanh</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="product-banner-jim-table-four">
          <ul class="product-banner-jim-table-ul product-banner-jim-table-ul-one">
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=3">Gấu bông lớn <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./items.php?item_id=18">Capybara</a></li>
                <li><a href="./items.php?item_id=19">Capybara thể thao</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=4">Móc khoá <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./items.php?item_id=21">Baby Three</a></li>
                <li><a href="./items.php?item_id=20">Loopy đội mũ</a></li>
                <li><a href="./items.php?item_id=22">Labubu hoa quả</a></li>
                <li><a href="./items.php?item_id=23">Labubu nơ</a></li>
                <li><a href="./items.php?item_id=24">Labubu bánh vòng</a></li>
                <li><a href="./items.php?item_id=25">Labubu mặt hoa</a></li>
                <li><a href="./items.php?item_id=26">Capybara móc khoá</a></li>
                <li><a href="./items.php?item_id=27">Kem Capybara</a></li>
                <li><a href="./items.php?item_id=28">Vịt</a></li>
                <li><a href="./items.php?item_id=29">Tiểu Cường</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=5">GHIM CÀI ÁO <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./items.php?item_id=30">Ghim Loopy</a></li>
                <li><a href="./items.php?item_id=31">Ghim Labubu</a></li>
                <li><a href="./items.php?item_id=32">Ghim Pet</a></li>
                <li><a href="./items.php?item_id=33">Ghim Capybara</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="product-banner-jim-table-five">
          <ul class="product-banner-jim-table-ul product-banner-jim-table-ul-one">
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=7">Nến thơm <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./items.php?item_id=40">Nến bông tuyết</a></li>
                <li><a href="./items.php?item_id=41">Nến cây thông dạng dẹt</a></li>
                <li><a href="./items.php?item_id=42">Nến cây thông dạng đứng</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=8">Balo túi xách <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./items.php?item_id=43">Balo Capybara</a></li>
                <li><a href="./items.php?item_id=44">Túi xách gấu trúc</a></li>
                <li><a href="./items.php?item_id=45">Túi xách gà</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=10">Đồ theo Trend <i class="fa-solid fa-chevron-down"></i></a>
              <ul class="product-banner-jim-ultwo nested-ul">
                <li><a href="./items.php?item_id=46">Gấu bông Trư Bát Giới</a></li>
              </ul>
            </li>
          </ul>
        </div>

      </section>
    </section>
<script src="header-responsive.js" defer></script>
<script src="header.js"></script>
</body>
</html>