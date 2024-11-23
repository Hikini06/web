<?php
require 'db-connect.php'; 

// Lấy danh sách subcategories từ bảng subcategories
$query = $pdo->prepare("SELECT * FROM subcategories");
$query->execute();
$subcategories = $query->fetchAll(PDO::FETCH_ASSOC);

// Lấy subcategory_id từ URL (nếu có)
$subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : null;

// Số sản phẩm tối đa mỗi trang
$items_per_page = 16;

// Trang hiện tại
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page = max(1, $page); // Đảm bảo trang >= 1
$offset = ($page - 1) * $items_per_page;

// Nếu có subcategory_id, truy vấn danh sách sản phẩm từ bảng items_detail
$total_products = 0;
$products = [];
if ($subcategory_id) {
    // Đếm tổng số sản phẩm
    $count_query = $pdo->prepare("
        SELECT COUNT(*) AS total
        FROM items_detail
        INNER JOIN items ON items_detail.item_id = items.id
        WHERE items.subcategory_id = :subcategory_id
    ");
    $count_query->bindValue(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
    $count_query->execute();
    $total_products = $count_query->fetch(PDO::FETCH_ASSOC)['total'];

    // Truy vấn sản phẩm có phân trang
    $query = $pdo->prepare("
        SELECT items_detail.*
        FROM items_detail
        INNER JOIN items ON items_detail.item_id = items.id
        WHERE items.subcategory_id = :subcategory_id
        LIMIT :limit OFFSET :offset
    ");
    $query->bindValue(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
    $query->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    $query->execute();
    $products = $query->fetchAll(PDO::FETCH_ASSOC);
}

// Tính tổng số trang
$total_pages = ceil($total_products / $items_per_page);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh mục sản phẩm</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="categories.css"/>
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
    <!-- Categories Filter -->
    <div class="categories-filter-cont">
        <button class="arrow left-arrow">&lt;</button>
        <div class="categories-filter">
        <ul>
            <?php foreach ($subcategories as $subcategory): ?>
                <li class="<?= $subcategory['id'] === $subcategory_id ? 'active' : '' ?>">
                    <a href="categories.php?subcategory_id=<?= htmlspecialchars($subcategory['id']) ?>">
                        <?= htmlspecialchars($subcategory['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        </div>
        <button class="arrow right-arrow">&gt;</button>
    </div>
    <!-- Categories Products -->
    <div class="categories">
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
            <p>Không có sản phẩm nào trong danh mục này.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($total_pages > 1): ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="categories.php?subcategory_id=<?= htmlspecialchars($subcategory_id) ?>&page=<?= $i ?>"
                   class="<?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        <?php endif; ?>
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
<script src="categories.js"></script>
</body>
</html>
