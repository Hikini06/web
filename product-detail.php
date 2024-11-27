<?php
include 'db-connect.php';

// Xử lý dữ liệu từ form Quick Buy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sdt'])) {
    $sdt = trim($_POST['sdt']);

    // Kiểm tra số điện thoại trên máy chủ
    if (preg_match('/^\d{9,10}$/', $sdt)) {
        try {
            // Chuẩn bị câu lệnh INSERT
            $query = "INSERT INTO khachhang (sdt, thoigiandathang) VALUES (:sdt, CURRENT_TIMESTAMP)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':sdt', $sdt);

            // Thực thi câu lệnh
            $stmt->execute();

            // Hiển thị thông báo thành công
            $successMessage = "Bọn mình sẽ liên lạc với bạn liền nha!";
        } catch (PDOException $e) {
            $errorMessage = "Lỗi khi lưu dữ liệu: " . $e->getMessage();
        }
    } else {
        $errorMessage = "Vui lòng nhập đúng số điện thoại";
    }
}

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
    <?php include 'header.php'; ?>

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
                    <div class="product-detail-pic-text-quickbuy-input">
                        <form id="quick-buy-form" method="post">
                            <input
                                type="text"
                                name="sdt"
                                placeholder="Số điện thoại"
                                pattern="\d{9,10}"
                                required
                                title="Vui lòng nhập số điện thoại có 9 đến 10 chữ số"
                            />
                            <button type="submit">Gửi</button>
                        </form>
                        <?php if (isset($errorMessage)): ?>
                            <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
                        <?php endif; ?>
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
    <?php include 'footer.php'; ?>
    <!-- Popup thông báo -->
    <div id="popup-message" class="popup-message">
        <span id="popup-text"></span>
    </div>

    <script src="product_detail.js"></script>
    
    <script>
        // JavaScript kiểm tra và hiển thị popup
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($successMessage)): ?>
                // Nếu có thông báo thành công từ PHP
                showPopup("<?php echo htmlspecialchars($successMessage); ?>");
            <?php endif; ?>
        });

        function showPopup(message) {
            var popup = document.getElementById('popup-message');
            var popupText = document.getElementById('popup-text');
            popupText.textContent = message;
            popup.classList.add('show');

            // Ẩn popup sau 3 giây
            setTimeout(function() {
                popup.classList.remove('show');
            }, 3000);
        }
    </script><!-- Pop-up Đăng ký -->
<div class="popup" id="registrationPopup">
        <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <h2>Đăng ký mua hàng</h2>
            <form id="registrationForm">
                <input type="text" placeholder="Tên của bạn" required><br><br>
                <input type="email" placeholder="Email của bạn" required><br><br>
                <input type="tel" placeholder="Số điện thoại" required><br><br>
                <button type="submit">Đặt hàng</button>
            </form>
        </div>
    </div>

    <script>
        function openPopup() {
            document.getElementById('registrationPopup').style.display = 'flex';
        }

        function closePopup() {
            document.getElementById('registrationPopup').style.display = 'none';
        }

        // Xử lý sự kiện khi form được gửi
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn chặn hành động mặc định của form

            // Thực hiện các hành động cần thiết, ví dụ: gửi dữ liệu đến máy chủ

            // Hiển thị thông báo xác nhận
            alert('Cảm ơn bạn đã đăng ký mua hàng!');

            // Đóng pop-up sau khi gửi
            closePopup();
        });

        // Ví dụ: Mở pop-up khi trang được tải
        window.onload = function() {
            openPopup();
        }
    </script>
</body>
</html>


