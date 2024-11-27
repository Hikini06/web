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
            $stmt->bindParam(':sdt', $sdt, PDO::PARAM_STR);

            // Thực thi câu lệnh
            $stmt->execute();

            // Hiển thị thông báo thành công
            $successMessage = "Chúng tôi sẽ liên lạc với bạn ngay!";
        } catch (PDOException $e) {
            $errorMessage = "Lỗi khi lưu dữ liệu: " . $e->getMessage();
        }
    } else {
        $errorMessage = "Vui lòng nhập đúng số điện thoại.";
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
        $currentItemId = $product['item_id']; // Giả sử bảng items_detail có trường item_id
    } else {
        $productName = "Sản phẩm không tồn tại";
        $productPrice = "0đ";
        $productImg = "default.jpg";
        $currentItemId = null;
    }
} catch (PDOException $e) {
    die("Lỗi: " . $e->getMessage());
}

// CHỨC NĂNG LẤY SẢN PHẨM TƯƠNG TỰ
function getSimilarItems($pdo, $current_id, $item_id, $limit = 4) {
    try {
        // Truy vấn các sản phẩm cùng item_id, khác id hiện tại, sắp xếp ngẫu nhiên
        $query = "
            SELECT * 
            FROM items_detail 
            WHERE item_id = :item_id AND id != :current_id 
            ORDER BY RAND() 
            LIMIT :limit
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
        $stmt->bindParam(':current_id', $current_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $similarItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Nếu chưa đủ, bổ sung các sản phẩm khác gần nhất
        if (count($similarItems) < $limit) {
            $remaining = $limit - count($similarItems);
            // Lấy các id đã được lấy trong similarItems để loại trừ trong truy vấn bổ sung
            $excludedIds = array_map(function($item) { return $item['id']; }, $similarItems);
            $excludedIds[] = $current_id; // loại trừ sản phẩm hiện tại

            // Chuẩn bị chuỗi placeholders cho IN
            if (count($excludedIds) > 0) {
                $placeholders = implode(',', array_fill(0, count($excludedIds), '?'));
            } else {
                $placeholders = '';
            }

            // Truy vấn các sản phẩm khác, không cùng item_id và không trong excludedIds, sắp xếp theo độ gần với id hiện tại
            $queryExtra = "
                SELECT * 
                FROM items_detail 
                WHERE " . (count($excludedIds) > 0 ? "id NOT IN ($placeholders)" : "1") . " 
                ORDER BY ABS(id - ?) 
                LIMIT ?
            ";
            $stmtExtra = $pdo->prepare($queryExtra);

            // Liên kết các tham số
            $i = 1;
            foreach ($excludedIds as $id) {
                $stmtExtra->bindValue($i++, $id, PDO::PARAM_INT);
            }
            $stmtExtra->bindValue($i++, $current_id, PDO::PARAM_INT);
            $stmtExtra->bindValue($i++, $remaining, PDO::PARAM_INT);

            $stmtExtra->execute();
            $extraItems = $stmtExtra->fetchAll(PDO::FETCH_ASSOC);

            // Kết hợp hai mảng
            $similarItems = array_merge($similarItems, $extraItems);
        }

        return $similarItems;
    } catch (PDOException $e) {
        die("Lỗi khi lấy sản phẩm tương tự: " . $e->getMessage());
    }
}

// Lấy danh sách sản phẩm tương tự
$similarProducts = ($currentItemId !== null) ? getSimilarItems($pdo, $product_id, $currentItemId, 4) : [];

// CHỨC NĂNG LẤY SẢN PHẨM NGẪU NHIÊN
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
        die("Lỗi khi lấy sản phẩm ngẫu nhiên: " . $e->getMessage());
    }
}
// Lấy danh sách sản phẩm ngẫu nhiên
$suggestItems = getRandomSuggestItems($pdo, 4);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiệm hoa MiMi</title>
    <link rel="stylesheet" href="product-detail.css">
    <link rel="stylesheet" href="header.css">
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
                    <!-- Phần dành cho ảnh phụ nếu cần -->
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
                    <p>- Giao hàng nhanh chóng khu vực Hà Nội</p>
                    <p>- Lỗi 1 đổi 1 hoặc hoàn tiền</p>
                    <p>- Bảo hành 14 ngày</p>
                    <p>- Cam kết sản phẩm giống với hình ảnh</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CÁC SẢN PHẨM TƯƠNG TỰ -->
    <div class="product-detail-common-cont">
        <h2 class="product-detail-common-cont-title">Sản phẩm tương tự</h2>
        <div class="product-detail-common">
            <?php if (!empty($similarProducts) && is_array($similarProducts)): ?>
                <?php foreach ($similarProducts as $item): ?>
                    <a href="product-detail.php?id=<?php echo htmlspecialchars($item['id']); ?>" class="product-detail-common-items">
                        <img src="<?php echo htmlspecialchars($item['img'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? 'No Name'); ?>">
                        <h3><?php echo htmlspecialchars($item['name'] ?? 'Sản phẩm không có tên'); ?></h3>
                        <h4><?php echo isset($item['price']) ? number_format($item['price'], 0, ',', '.') . 'đ' : '0đ'; ?></h4>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không tìm thấy sản phẩm tương tự.</p>
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
    <!-- Popup Form -->
    <div id="order-popup" class="popup">
        <div class="popup-content">
            <span id="close-popup" class="close-popup">&times;</span>
            <h2>Thông tin đặt hàng</h2>
            <form id="order-form" method="post">
                <label for="name">Tên:</label>
                <input type="text" id="name" name="ten" placeholder="Nhập tên" required>
                
                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="sdt" placeholder="Nhập số điện thoại" required>
                <div id="phone-error" class="error-message"></div> <!-- Thông báo lỗi -->

                <label for="address">Địa chỉ:</label>
                <input type="text" id="address" name="diachi" placeholder="Nhập địa chỉ" required>
                
                <button type="submit">Xác nhận</button>
            </form>
        </div>
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
    </script>

</body>
</html>
