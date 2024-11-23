<?php
require 'db-connect.php';

// Lấy danh sách item_id từ bảng items
$queryItems = $pdo->prepare("SELECT id, name FROM items");
$queryItems->execute();
$items = $queryItems->fetchAll(PDO::FETCH_ASSOC);

// Lấy item_id được chọn (nếu có)
$item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : null;

// Pagination variables
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 16; // Hiển thị 16 sản phẩm mỗi trang
$offset = ($page - 1) * $limit;

// Nếu có item_id, lấy danh sách sản phẩm từ items_detail
$products = [];
if ($item_id) {
    $queryProducts = $pdo->prepare("
        SELECT * FROM items_detail
        WHERE item_id = ?
        LIMIT $limit OFFSET $offset
    ");
    $queryProducts->execute([$item_id]);
    $products = $queryProducts->fetchAll(PDO::FETCH_ASSOC);

    // Đếm tổng số sản phẩm
    $queryTotal = $pdo->prepare("SELECT COUNT(*) FROM items_detail WHERE item_id = ?");
    $queryTotal->execute([$item_id]);
    $totalProducts = $queryTotal->fetchColumn();
    $totalPages = ceil($totalProducts / $limit);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel= "stylesheet" href= "items.css"/>
    <link rel="stylesheet" href="header.css" />
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
              <a href="#">ĐÈN DẠNG TRÒN</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=47">Đèn tròn hoa tulip</a></li>
                <li><a href="./items.php?item_id=48">Đèn tròn hoa hồng</a></li>
                <li><a href="./items.php?item_id=49">Hoa anh đào</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="#">ĐÈN DẠNG VUÔNG</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=56">Đèn vuông hoa tulip</a></li>
                <li><a href="./items.php?item_id=57">Đèn vuông hoa hồng</a></li>
                <li><a href="./items.php?item_id=58">Đèn vuông noel</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="#">ĐÈN ĐÁM MÂY</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=53">Đèn mây hoa tulip</a></li>
                <li><a href="./items.php?item_id=54">Đèn mây hoa hồng</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="#">ĐÈN THÚ</a>
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
              <a href="#">BÓ HOA LEN, HOA SÁP</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=1">Hoa len thỏ</a></li>
                <li><a href="./items.php?item_id=2">Bó hoa len</a></li>
                <li><a href="./items.php?item_id=3">Hoa sáp</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="#">BÓ HOA GẤU BÔNG</a>
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
              <a href="#">SET Loopy</a>
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
              <a href="#">SET vịt hoa</a>
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
              <a href="#">SET Baby Three</a>
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
              <a href="#">SET Labubu</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./product-detail.php?id=273">Labubu xanh</a></li>
                <li><a href="./product-detail.php?id=274">Labubu hồng</a></li>
                <li><a href="./product-detail.php?id=275">Labubu tím</a></li>
                <li><a href="./product-detail.php?id=276">Labubu nâu</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="#">SET tất và nến</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./product-detail.php?id=277">Set tiểu Cường</a></li>
                <li><a href="./product-detail.php?id=278">Nến xanh lá</a></li>
                <li><a href="./product-detail.php?id=279">Nến xanh neon</a></li>
                <li><a href="./product-detail.php?id=280">Nến vàng</a></li>
                <li><a href="./product-detail.php?id=281">Nến đỏ</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="#">SET Capybara</a>
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
              <a href="#">Gấu bông lớn</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=18">Capybara</a></li>
                <li><a href="./items.php?item_id=19">Capybara thể thao</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="#">Móc khoá</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=20">Baby Three</a></li>
                <li><a href="./items.php?item_id=21">Loopy đội mũ</a></li>
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
              <a href="#">GHIM CÀI ÁO</a>
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
              <a href="#">Nến thơm</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=40">Nến bông tuyết</a></li>
                <li><a href="./items.php?item_id=41">Nến cây thông dạng dẹt</a></li>
                <li><a href="./items.php?item_id=42">Nến cây thông dạng đứng</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="#">Balo túi xách</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=43">Balo Capybara</a></li>
                <li><a href="./items.php?item_id=44">Túi xách gấu trúc</a></li>
                <li><a href="./items.php?item_id=45">Túi xách gà</a></li>
              </ul>
            </li>
            <li class="product-banner-jim-li">
              <a href="#">Đồ theo Trend</a>
              <ul class="product-banner-jim-ultwo">
                <li><a href="./items.php?item_id=46">Gấu bông Trư Bát Giới</a></li>
              </ul>
            </li>
          </ul>
        </div>

      </section>
    </section>
<!-- FILTER DANH MỤC -->
    <div class="items-filter-cont">
        <div class="items-filter">
            <ul>
                <?php foreach ($items as $item): ?>
                    <li>
                        <a href="items.php?item_id=<?= $item['id'] ?>">
                            <?= htmlspecialchars($item['name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

<!-- HIỂN THỊ SẢN PHẨM -->
    <div class="items-cont">
        <div class="items">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product">
                        <a href="product-detail.php?id=<?= htmlspecialchars($product['id']) ?>">
                            <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p><?= htmlspecialchars(number_format($product['price'])) ?>đ</p>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có sản phẩm nào được tìm thấy.</p>
            <?php endif; ?>
        </div>
    </div>

<!-- PHÂN TRANG -->
    <?php if (!empty($products) && $totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="items.php?item_id=<?= $item_id ?>&page=<?= $i ?>" 
                class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
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