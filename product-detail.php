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
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=11">ĐÈN DẠNG TRÒN</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=47">Đèn tròn hoa tulip</a></li>
                <li><a href="./items.php?item_id=48">Đèn tròn hoa hồng</a></li>
                <li><a href="./items.php?item_id=49">Hoa anh đào</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=12">ĐÈN DẠNG VUÔNG</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=56">Đèn vuông hoa tulip</a></li>
                <li><a href="./items.php?item_id=57">Đèn vuông hoa hồng</a></li>
                <li><a href="./items.php?item_id=58">Đèn vuông noel</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=13">ĐÈN ĐÁM MÂY</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=53">Đèn mây hoa tulip</a></li>
                <li><a href="./items.php?item_id=54">Đèn mây hoa hồng</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=14">ĐÈN THÚ</a>
              <ul class="product-banner-jim-ultwo">
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
              <a href="./categories.php?subcategory_id=1">BÓ HOA LEN, HOA SÁP</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=1">Hoa len thỏ</a></li>
                <li><a href="./items.php?item_id=2">Bó hoa len</a></li>
                <li><a href="./items.php?item_id=3">Hoa sáp</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=2">BÓ HOA GẤU BÔNG</a>
              <ul class="product-banner-jim-ultwo">
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
              <a href="./items.php?item_id=34">SET Loopy</a>
              <ul class="product-banner-jim-ultwo">
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
              <a href="./items.php?item_id=35">SET vịt hoa</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./product-detail.php?id=261">Vịt mặt hoa</a></li>
                <li><a href="./product-detail.php?id=262">Vịt mũ ếch</a></li>
                <li><a href="./product-detail.php?id=263">Vịt mũ xanh</a></li>
                <li><a href="./product-detail.php?id=264">Vịt ôm cá</a></li>
                <li><a href="./product-detail.php?id=265">Vịt ôm gối</a></li>
                <li><a href="./product-detail.php?id=266">Vịt nơ hồng</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./items.php?item_id=36">SET Baby Three</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./product-detail.php?id=267">Thỏ hồng</a></li>
                <li><a href="./product-detail.php?id=268">Cáo</a></li>
                <li><a href="./product-detail.php?id=269">Voi xanh</a></li>
                <li><a href="./product-detail.php?id=270">Cừu</a></li>
                <li><a href="./product-detail.php?id=271">Gấu trúc</a></li>
                <li><a href="./product-detail.php?id=272">Khủng long</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./items.php?item_id=37">SET Labubu</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./product-detail.php?id=273">Labubu xanh</a></li>
                <li><a href="./product-detail.php?id=274">Labubu hồng</a></li>
                <li><a href="./product-detail.php?id=275">Labubu tím</a></li>
                <li><a href="./product-detail.php?id=276">Labubu nâu</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./items.php?item_id=38">SET tất và nến</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./product-detail.php?id=277">Set tiểu Cường</a></li>
                <li><a href="./product-detail.php?id=278">Nến xanh lá</a></li>
                <li><a href="./product-detail.php?id=279">Nến xanh neon</a></li>
                <li><a href="./product-detail.php?id=280">Nến vàng</a></li>
                <li><a href="./product-detail.php?id=281">Nến đỏ</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./items.php?item_id=39">SET Capybara</a>
              <ul class="product-banner-jim-ultwo">
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
              <a href="./categories.php?subcategory_id=3">Gấu bông lớn</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=18">Capybara</a></li>
                <li><a href="./items.php?item_id=19">Capybara thể thao</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=4">Móc khoá</a>
              <ul class="product-banner-jim-ultwo">
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
              <a href="./categories.php?subcategory_id=5">GHIM CÀI ÁO</a>
              <ul class="product-banner-jim-ultwo">
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
              <a href="./categories.php?subcategory_id=7">Nến thơm</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=40">Nến bông tuyết</a></li>
                <li><a href="./items.php?item_id=41">Nến cây thông dạng dẹt</a></li>
                <li><a href="./items.php?item_id=42">Nến cây thông dạng đứng</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=8">Balo túi xách</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=43">Balo Capybara</a></li>
                <li><a href="./items.php?item_id=44">Túi xách gấu trúc</a></li>
                <li><a href="./items.php?item_id=45">Túi xách gà</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="./categories.php?subcategory_id=10">Đồ theo Trend</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=46">Gấu bông Trư Bát Giới</a></li>
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


