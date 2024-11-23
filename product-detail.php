<?php
include 'db-connect.php';

// CHỨC NĂNG HIỂN THỊ SẢN PHẨM MAIN
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
// Truy vấn lấy thông tin sản phẩm chính
try {
    $query = "SELECT * FROM items_detail WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $productName = htmlspecialchars($product['name']);
        $productPrice = number_format($product['price'], 0, ',', '.') . "đ";
        $productImg = htmlspecialchars($product['img']);
    } else {
        $productName = "Sản phẩm không tồn tại";
        $productPrice = "0đ";
        $productImg = "default.jpg";
    }
} catch (PDOException $e) {
    die("Lỗi: " . $e->getMessage());
}

// CHỨC NĂNG CHO HIỂN THỊ 4 SẢN PHẨM GỢI Ý
function getRandomItems($pdo, $item_id, $limit = 4) {
    try {
        $query = "
            SELECT * 
            FROM items_detail 
            WHERE item_id = :item_id 
            ORDER BY RAND() 
            LIMIT :limit
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: [];
    } catch (PDOException $e) {
        die("Lỗi: " . $e->getMessage());
    }
}
// Ánh xạ dải ID sản phẩm chính và `item_id` gợi ý
$itemMapping = [
    '1-4' => 1,
    '5-9' => 2,
    '10-15' => 3,
    // Thêm các dải khác nếu cần
];
// Lấy `item_id` gợi ý dựa trên sản phẩm đang hiển thị
function getItemIdFromRange($productId, $itemMapping) {
    foreach ($itemMapping as $range => $item_id) {
        list($start, $end) = explode('-', $range);
        if ($productId >= (int)$start && $productId <= (int)$end) {
            return $item_id;
        }
    }
    return null;
}
// Xác định `item_id` cho sản phẩm gợi ý
$item_id_for_common = getItemIdFromRange($product_id, $itemMapping);
// Lấy danh sách sản phẩm gợi ý theo `item_id`
$items = ($item_id_for_common !== null) ? getRandomItems($pdo, $item_id_for_common, 4) : [];

// CHỨC NĂNG LẤY 4 SẢN PHẨM NGẪU NHIÊN
function getRandomSuggestItems($pdo, $limit = 4) {
  try {
      $query = "
          SELECT * 
          FROM items_detail 
          ORDER BY RAND() 
          LIMIT :limit
      ";
      $stmt = $pdo->prepare($query);
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $result ?: [];
  } catch (PDOException $e) {
      die("Lỗi: " . $e->getMessage());
  }
}
// Lấy danh sách sản phẩm ngẫu nhiên
$suggestItems = getRandomSuggestItems($pdo, 4);

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Tiệm hoa Mimi</title>
    <link rel="stylesheet" href = "product-detail.css" > 
    <link rel="stylesheet" href = "header.css" > 
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>
<body>
  <!-- HEADER ĐI THEO MỌI TRANG -->
    <section class="main-header-important">
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
            <a href="index.php" class="logo">Tiệm hoa MiMi</a>
          </div>
          <div class = "shearch-bar-cont">
            <div class = "shearch-bar"></div>
          </div>
          <div class = "header-contact-number">
            <a href="https://zalo.me/84354235669" target="_blank" ><h1>035.4235.669</h1></a>
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
      <!-- section san pham jim lam -->
      <section class="product-banner-jim">
        <div class="product-banner-jim-child">
          <div class="product-banner-jim-sanpham product-banner-jim-sanpham-one">
            <a class="product-banner-jim-a" href="">ĐÈN NGỦ ▶</a>
          </div>
          <div class="product-banner-jim-sanpham product-banner-jim-sanpham-two">
            <a class="product-banner-jim-a" href="">HOA GẤU BÔNG ▶</a>
          </div>
          <div class="product-banner-jim-sanpham product-banner-jim-sanpham-three">
            <a class="product-banner-jim-a" href="">SET QUÀ TẶNG ▶</a>
                        
          </div>
          <div class="product-banner-jim-sanpham product-banner-jim-sanpham-four">
            <a class="product-banner-jim-a" href="">GẤU BÔNG ▶</a>
            
          </div>
          <div class="product-banner-jim-sanpham product-banner-jim-sanpham-five">
            <a class="product-banner-jim-a" href="">KHÁC ▶</a>
            
          </div>
          <div class="product-banner-jim-sanpham product-banner-jim-sanpham-all">
            <a class="product-banner-jim-a" href="./product.php">TẤT CẢ SẢN PHẨM</a>
          </div>
        </div>
        <div class="product-banner-jim-table">
          <ul class="product-banner-jim-table-ul product-banner-jim-table-ul-one">
              <li class="product-banner-jim-li">ĐÈN DẠNG TRÒN
                <ul class="product-banner-jim-ultwo">
                  <li>Đèn tròn hoa tulip</li>
                  <li>Đèn tròn hoa hồng</li>
                  <li>Hoa anh đào</li>
                </ul>
              </li>
              <li class="product-banner-jim-li">ĐÈN DẠNG VUÔNG
                <ul class="product-banner-jim-ultwo">
                  <li>Đèn vuông hoa tulip</li>
                  <li>Đèn vuông hoa hồng</li>
                  <li>Đèn vuông noel</li>
                  <!-- <li>c</li>
                  <li>c</li> -->
                </ul>
              </li>
              <li class="product-banner-jim-li">ĐÈN ĐÁM MÂY
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
            
          </ul>
        </div>
        <div class="product-banner-jim-table-two">
          <ul class="product-banner-jim-table-ul product-banner-jim-table-ul-one">
              <li class="product-banner-jim-li">BÓ HOA LEN, HOA SÁP
                <ul class="product-banner-jim-ultwo">
                  <li>Hoa len thỏ</li>
                  <li>Bó hoa len</li>
                  <li>Hoa sáp</li>
                </ul>
              </li>
              <li class="product-banner-jim-li">BÓ HOA GẤU BÔNG
                <ul class="product-banner-jim-ultwo">
                  <li>Bó hoa gấu hoạt hình</li>
                  <li>Bó dạng đứng để bàn</li>
                  <li>Hộp hoa gấu bông</li>
                  <li>Hoa gấu hình kem</li>
                  <li>Hoa gấu lông xù</li>
                  <li>Gấu ôm hoa sáp</li>
                  <li>Hoa heo hồng đất sét</li>
                  <li>Hoa gấu 1 bông</li>
                  <li>Bó hoa tí hon</li>
                  <li>Các bó gấu Loopy</li>
                  <li>Bó Loopy tai thỏ</li>
                  <li>Các mẫu Capybara</li>
                  <li>Các mẫu gấu trúc</li>
                  <li>Các mẫu gấu dâu</li>
                </ul>
              </li>
            </ul>
              </li>
            </ul>
        </div>
        <div class="product-banner-jim-table-three">
          <ul class="product-banner-jim-table-ul product-banner-jim-table-ul-one">
              <li class="product-banner-jim-li">SET Loopy
                <ul class="product-banner-jim-ultwo">
                  <li>Loopy heo</li>
                  <li>Loopy gấu dâu</li>
                  <li>Loopy Kuromi</li>
                  <li>Loopy cá hề</li>
                  <li>Loopy gấu vàng</li>
                  <li>Loopy ong vàng</li>
                  <li>Loopy cá xanh khủng long</li>
                  <li>Loopy mũ cá</li>
                  <li>Set ghim cài 1</li>
                  <li>Set ghim cài 2</li>
                  <li>Set ghim cài Capybara</li>
                  
                </ul>
              </li>
              <li class="product-banner-jim-li">SET vịt hoa
                <ul class="product-banner-jim-ultwo">
                  <li>Vịt mặt hoa</li>
                  <li>Vịt mũ ếch</li>
                  <li>Vịt mũ xanh</li>
                  <li>Vịt ôm cá</li>
                  <li>Vịt ôm gối</li>
                  <li>Vịt nơ hồng</li>
                </ul>
              </li>
              <li class="product-banner-jim-li">SET Baby Three
                <ul class="product-banner-jim-ultwo">
                  <li>Thỏ hồng</li>
                  <li>Cáo</li>
                  <li>Voi xanh</li>
                  <li>Cừu</li>
                  <li>Gấu trúc</li>
                  <li>Khủng long</li>
                </ul>
              </li>
              <li class="product-banner-jim-li">SET Labubu
                <ul class="product-banner-jim-ultwo">
                  <li>Labubu xanh</li>
                  <li>Labubu hồng</li>
                  <li>Labubu tím</li>
                  <li>Labubu nâu</li>
                  
                </ul>
              </li>
              <li class="product-banner-jim-li">SET tất và nến
                <ul class="product-banner-jim-ultwo">
                  <li>Set tiểu Cường</li>
                  <li>Nến xanh lá</li>
                  <li>Nến xanh neon</li>
                  <li>Nến vàng</li>
                  <li>Nến đỏ</li>
                
                </ul>
              </li>
              <li class="product-banner-jim-li">SET Capybara
                <ul class="product-banner-jim-ultwo">
                  <li>Capybara hồng</li>
                  <li>Capybara kem hồng</li>
                  <li>Capybara kem nâu</li>
                  <li>Capybara kem xanh</li>
                
                </ul>
              </li>
            </ul>
        </div>
        <div class="product-banner-jim-table-four">
          <ul class="product-banner-jim-table-ul product-banner-jim-table-ul-one">
              <li class="product-banner-jim-li">Gấu bông lớn
                <ul class="product-banner-jim-ultwo">
                  <li>Capybara</li>
                  <li>Capybara thể thao</li>
                </ul>
              </li>
              <li class="product-banner-jim-li">Móc khoá
                <ul class="product-banner-jim-ultwo">
                  <li>Loopy đội mũ</li>
                  <li>Baby Three</li>
                  <li>Labubu hoa quả</li>
                  <li>Labubu nơ</li>
                  <li>Labubu bánh vòng</li>
                  <li>Labubu mặt hoa</li>
                  <li>Capybara móc khoá</li>
                  <li>Kem Capybara</li>
                  <li>Vịt</li>
                  <li>Tiểu Cường</li>
                  
                </ul>
              </li>
              <li class="product-banner-jim-li">GHIM CÀI ÁO
                <ul class="product-banner-jim-ultwo">
                  <li>Ghim Loopy</li>
                  <li>Ghim Labubu</li>
                  <li>Ghim Pet</li>
                  <li>Ghim Capybara</li>
                
                </ul>
              </li>
            
                </ul>
              </li>
            </ul>
        </div>
        <div class="product-banner-jim-table-five">
          <ul class="product-banner-jim-table-ul product-banner-jim-table-ul-one">
              <li class="product-banner-jim-li">Nến thơm
                <ul class="product-banner-jim-ultwo">
                  <li>Nến bông tuyết</li>
                  <li>Nến cây thông dạng dẹt</li>
                  <li>Nến cây thông dạng đứng</li>
                </ul>
              </li>
              <li class="product-banner-jim-li">Balo túi xách
                <ul class="product-banner-jim-ultwo">
                  <li>Balo Capybara</li>
                  <li>Túi xách gấu trúc</li>
                  <li>Túi xách gà</li>
                                
                </ul>
              </li>
              <li class="product-banner-jim-li">Đồ theo Trend
                <ul class="product-banner-jim-ultwo">
                  <li>Gấu bông Trư Bát Giới</li>
                
                </ul>
              </li>
            
                </ul>
              </li>
            </ul>
        </div>
      </section>
    </section>
  <!-- HEADER ĐI THEO MỌI TRANG END -->


    <!-- HIỂN THỊ SẢN PHẨM CHÍNH -->
    <div class="product-detail-pic-cont">
        <div class="product-detail-pic">
            <!-- Phần ảnh sản phẩm -->
            <div class="product-detail-pic-img">
                <div class="product-detail-pic-img-mainimg">
                    <!-- Hiển thị ảnh sản phẩm -->
                    <img src="<?php echo $productImg; ?>" alt="<?php echo $productName; ?>" style="width: 100%; height: 100%;">
                </div>
                <div class="product-detail-pic-img-moreimg">
                    <!-- Phần dành cho ảnh phụ -->
                </div>
            </div>

            <!-- Phần thông tin sản phẩm -->
            <div class="product-detail-pic-text">
                <div class="product-detail-pic-text-nameandprice">
                    <h1><?php echo $productName; ?></h1>
                    <h3><?php echo $productPrice; ?></h3>
                </div>
                <button class="product-detail-pic-text-buynow">Đặt Hàng</button>
                <div class="product-detail-pic-text-quickbuy">
                    <h4>MUA HÀNG NHANH</h4>
                    <div class = "product-detail-pic-text-quickbuy-input">
                      <input type="text" placeholder="Số điện thoại" />
                      <button>Gửi</button>
                    </div>
                </div>
                <div class="product-detail-pic-text-dis">
                    <p>- Giao hàng hoả tốc khu vực Hà Nội</p>
                    <p>- Lỗi 1 đổi 1 hoặc hoàn tiền</p>
                    <p>- Bảo hành 14 ngày</p>
                    <p>- Cam kết sản phẩm giống với hình ảnh</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CÁC SẢN PHẨM GỢI Ý -->
    <div class="product-detail-common-cont">
      <h2 class = "product-detail-common-cont-title">Sản phẩm tương tự</h2>
      <div class="product-detail-common">
        <?php if (!empty($items) && is_array($items)): ?>
            <?php foreach ($items as $item): ?>
                <a href="product-detail.php?id=<?php echo htmlspecialchars($item['id']); ?>" class="product-detail-common-items">
                    <img src="<?php echo htmlspecialchars($item['img'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? 'No Name'); ?>">
                    <h3><?php echo htmlspecialchars($item['name'] ?? 'Sản phẩm không có tên'); ?></h3>
                    <h4><?php echo isset($item['price']) ? number_format($item['price'], 0, ',', '.') . 'đ' : '0đ'; ?></h4>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không tìm thấy sản phẩm nào.</p>
        <?php endif; ?>
      </div>

    </div>

    <!-- CÁC SẢN PHẨM NGẪU NHIÊN -->
    <div class="product-detail-suggest-cont">
      <h2 class="product-detail-suggest-cont-title">Sản phẩm bạn có thể thích</h2>
      <div class="product-detail-suggest">
          <?php if (!empty($suggestItems) && is_array($suggestItems)): ?>
              <?php foreach ($suggestItems as $item): ?>
                  <a href="product-detail.php?id=<?php echo htmlspecialchars($item['id']); ?>" class="product-detail-suggest-items">
                      <img src="<?php echo htmlspecialchars($item['img'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? 'No Name'); ?>">
                      <h3><?php echo htmlspecialchars($item['name'] ?? 'Sản phẩm không có tên'); ?></h3>
                      <h4><?php echo isset($item['price']) ? number_format($item['price'], 0, ',', '.') . 'đ' : '0đ'; ?></h4>
                  </a>
              <?php endforeach; ?>
          <?php else: ?>
              <p>Không tìm thấy sản phẩm nào.</p>
          <?php endif; ?>
      </div>
    </div>

    <!-- FOOTER -->
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

<script src="header.js"></script>





</body>
</html>


