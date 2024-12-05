<?php
include '../config/db-connect.php';
require_once 'functions.php';

// CHỨC NĂNG HIỂN THỊ SẢN PHẨM MAIN
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Lấy dữ liệu từ bảng items_option
$productOptions = [];
if ($product_id) {
    try {
        $query = "SELECT * FROM items_option WHERE detail_id = :detail_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':detail_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $productOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Lỗi khi lấy dữ liệu từ bảng items_option: " . $e->getMessage());
    }
}

// Phân loại dữ liệu theo group_name
$groupedOptions = [
    'Màu sắc' => [],
    'Số lượng' => [],
    'Tùy chọn' => [],
    'Phụ kiện' => [],
];

foreach ($productOptions as $key => $optionItem) {
    if (!is_array($optionItem)) {
        // Nếu $optionItem không phải là mảng, gán giá trị mặc định
        $optionItem = [
            'add_price' => 0,
            'group_name' => '',
            'option_name' => '',
        ];
    } else {
        // Đảm bảo các khóa tồn tại và xử lý giá trị add_price
        $optionItem['add_price'] = isset($optionItem['add_price']) && is_numeric($optionItem['add_price']) ? (float)$optionItem['add_price'] : 0;
        $optionItem['group_name'] = isset($optionItem['group_name']) ? $optionItem['group_name'] : '';
        $optionItem['option_name'] = isset($optionItem['option_name']) ? $optionItem['option_name'] : '';
    }

    $groupName = $optionItem['group_name'];
    if (isset($groupedOptions[$groupName])) {
        $groupedOptions[$groupName][] = $optionItem['option_name'];
    }

    // Cập nhật lại mảng $productOptions
    $productOptions[$key] = $optionItem;
}





// Xử lý dữ liệu từ form Quick Buy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sdt']) && isset($_POST['product_id'])) {
    $sdt = trim($_POST['sdt']);
    $product_id = (int)$_POST['product_id'];

    // Kiểm tra product_id
    if ($product_id <= 0) {
        $errorMessage = "Thông tin sản phẩm không hợp lệ!";
    }
    // Kiểm tra số điện thoại trên máy chủ
    elseif (preg_match('/^\d{9,10}$/', $sdt)) {
        try {
            // Chuẩn bị câu lệnh INSERT
            $query = "INSERT INTO khachhang (sdt, thoigiandathang, product_id) VALUES (:sdt, CURRENT_TIMESTAMP, :product_id)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':sdt', $sdt, PDO::PARAM_STR);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

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
        $currentItemId = $product['id']; // Giả sử bảng items_detail có trường item_id
        $basePrice = $product['price'] ?? 0; // Gán giá trị mặc định nếu NULL
    } else {
        $productName = "Sản phẩm không tồn tại";
        $productPrice = "0đ";
        $productImg = "default.jpg";
        $currentItemId = null;
        $basePrice = 0; // Giá mặc định khi không có sản phẩm
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
    <!-- <base href="https://tiemhoamimi.com/"> -->
    <base href="http://localhost/web-dm-lum/web/">
    <link rel="icon" href="./image/mimi-logo-vuong.png" type="image/png">
    <link rel="stylesheet" href="<?php echo asset('product-detail.css'); ?>">
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
                    <img src="image/upload/<?php echo $productImg; ?>" alt="<?php echo $productName; ?>" style="width: 100%; height: 100%;">
                </div>
                <div class="product-detail-pic-img-moreimg">
                    <!-- Phần dành cho ảnh phụ nếu cần -->
                </div>
            </div>

            <!-- Phần thông tin sản phẩm -->
            <div class="product-detail-pic-text">
                <div class="product-detail-pic-text-nameandprice">
                    <h1><?php echo $productName; ?></h1>
                    <h3><?php echo number_format($basePrice, 0, ',', '.') . "đ"; ?></h3> <!-- Sử dụng $basePrice thay vì $productPrice -->
                </div>

                
                <div class="product-detail-option-cont">
                <!-- Màu sắc -->
                <?php if (!empty($groupedOptions['Màu sắc'])): ?>
                    <div class="product-detail-mau-sac">
                        <h3 class="product-detail-option-title">Màu sắc</h3>
                        <div>
                            <?php foreach ($productOptions as $optionItem): ?>
                                <?php if ($optionItem['group_name'] === 'Màu sắc'): ?>
                                    <button
                                        class="option-button"
                                        data-add-price="<?php echo $optionItem['add_price']; ?>"
                                        data-type="color">
                                        <?php echo htmlspecialchars($optionItem['option_name']); ?>
                                    </button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>



                    <!-- Số lượng -->
                    <?php if (!empty($groupedOptions['Số lượng'])): ?>
                        <div class="product-detail-so-luong">
                            <h3 class="product-detail-option-title">Số lượng</h3>
                            <div>
                                <?php foreach ($productOptions as $optionItem): ?>
                                    <?php if ($optionItem['group_name'] === 'Số lượng'): ?>
                                        <button 
                                            class="option-button" 
                                            data-add-price="<?php echo $optionItem['add_price']; ?>" 
                                            data-type="quantity">
                                            <?php echo htmlspecialchars($optionItem['option_name']); ?>
                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Tùy chọn -->
                    <?php if (!empty($groupedOptions['Tùy chọn'])): ?>
                        <div class="product-detail-tuy-chon">
                            <h3 class="product-detail-option-title">Tùy chọn</h3>
                            <div>
                                <?php foreach ($productOptions as $optionItem): ?>
                                    <?php if ($optionItem['group_name'] === 'Tùy chọn'): ?>
                                        <button 
                                            class="option-button" 
                                            data-add-price="<?php echo $optionItem['add_price']; ?>" 
                                            data-type="option">
                                            <?php echo htmlspecialchars($optionItem['option_name']); ?>
                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Phụ kiện -->
                    <?php if (!empty($groupedOptions['Phụ kiện'])): ?>
                        <div class="product-detail-phu-kien">
                            <h3 class="product-detail-option-title">Phụ kiện</h3>
                            <div>
                                <?php foreach ($productOptions as $optionItem): ?>
                                    <?php if ($optionItem['group_name'] === 'Phụ kiện'): ?>
                                        <button 
                                            class="option-button" 
                                            data-add-price="<?php echo $optionItem['add_price']; ?>" 
                                            data-type="accessory">
                                            <?php echo htmlspecialchars($optionItem['option_name']); ?>
                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>


                </div>


                <button class="product-detail-pic-text-buynow">Đặt Hàng</button>
                <div class="product-detail-pic-text-quickbuy">
                    <h4>MUA HÀNG NHANH</h4>
                    <div class="product-detail-pic-text-quickbuy-input">
                        <form id="quick-buy-form" method="post">
                            <!-- Thêm trường ẩn để gửi product_id -->
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            
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
                    <a href="chi-tiet-san-pham/<?php echo htmlspecialchars($item['id']); ?>" class="product-detail-common-items">
                        <img src="image/upload/<?php echo htmlspecialchars($item['img'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? 'No Name'); ?>">
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
                    <a href="chi-tiet-san-pham/<?php echo htmlspecialchars($item['id']); ?>" class="product-detail-suggest-items">
                        <img src="image/upload/<?php echo htmlspecialchars($item['img'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? 'No Name'); ?>">
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
                    <!-- Thêm trường ẩn để gửi product_id -->
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <div id="phone-error" class="error-message"></div>
                    <label for="name">Tên:</label>
                    <input type="text" id="name" name="ten" placeholder="Nhập tên" required>
                    
                    <label for="phone">Số điện thoại:</label>
                    <input type="text" id="phone" name="sdt" placeholder="Nhập số điện thoại" required>
                

                    <label for="address">Địa chỉ:</label>
                    <input type="text" id="address" name="diachi" placeholder="Nhập địa chỉ" required>
                    
                    <button type="submit">Xác nhận</button>
                </form>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const popup = document.getElementById('order-popup');
            const openPopupButton = document.querySelector('.product-detail-pic-text-buynow');
            const closePopupButton = document.getElementById('close-popup');
            const orderForm = document.getElementById('order-form');
            const phoneInput = document.getElementById('phone');
            const phoneError = document.getElementById('phone-error');

            let basePrice = <?php echo json_encode($basePrice); ?>;
            let selectedOptions = {
                color: 0,
                quantity: 0,
                option: 0,
                accessory: 0,
            };

            function updatePrice(addPrice, type) {
                selectedOptions[type] = addPrice;
                let totalPrice = parseFloat(basePrice) 
                    + parseFloat(selectedOptions.color || 0) 
                    + parseFloat(selectedOptions.quantity || 0) 
                    + parseFloat(selectedOptions.option || 0) 
                    + parseFloat(selectedOptions.accessory || 0);

                // Định dạng lại tổng giá theo kiểu tiền tệ Việt Nam
                document.querySelector('.product-detail-pic-text-nameandprice h3').textContent = 
                    totalPrice.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
            }

            // Bind click event to all buttons dynamically
            document.querySelectorAll('.option-button').forEach(button => {
                button.addEventListener('click', function () {
                    const addPrice = parseFloat(this.getAttribute('data-add-price') || 0);
                    const type = this.getAttribute('data-type'); // Sử dụng data-type đã được cập nhật

                    updatePrice(addPrice, type);
                });
            });

            // Xử lý form Quick Buy
            document.getElementById('quick-buy-form').addEventListener('submit', function(event) {
                var sdtInput = document.querySelector('input[name="sdt"]');
                var sdtValue = sdtInput.value.trim();
                var errorMessage = document.querySelector('.error-message');

                // Kiểm tra xem sdtValue có phải là 9 đến 10 chữ số không
                var phoneRegex = /^\d{9,10}$/;
                if (!phoneRegex.test(sdtValue)) {
                    event.preventDefault(); // Ngăn chặn form gửi đi
                    if (errorMessage) {
                        errorMessage.textContent = 'Vui lòng nhập đúng số điện thoại';
                    } else {
                        var errorDiv = document.createElement('div');
                        errorDiv.className = 'error-message';
                        errorDiv.textContent = 'Vui lòng nhập đúng số điện thoại';
                        sdtInput.parentNode.appendChild(errorDiv);
                    }
                } else {
                    if (errorMessage) {
                        errorMessage.textContent = '';
                    }
                }
            });

            // Mở popup
            openPopupButton.addEventListener('click', function () {
                popup.style.display = 'flex';
            });

            // Đóng popup
            closePopupButton.addEventListener('click', function () {
                popup.style.display = 'none';
            });

            // Gửi dữ liệu form
            orderForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Ngăn gửi form mặc định

                const formData = new FormData(orderForm);

                // Gửi AJAX request
                fetch('order-handler.php', {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Đặt hàng thành công!');
                        } else {
                            alert('Lỗi: ' + data.message);
                        }
                        popup.style.display = 'none'; // Đóng popup
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        alert('Đã xảy ra lỗi!');
                    });
            });

            // Hiển thị popup thông báo thành công nếu có
            <?php if (isset($successMessage)): ?>
                showPopup("<?php echo htmlspecialchars($successMessage); ?>");
            <?php endif; ?>

            function showPopup(message) {
                var popupMsg = document.getElementById('popup-message');
                var popupText = document.getElementById('popup-text');
                popupText.textContent = message;
                popupMsg.classList.add('show');

                // Ẩn popup sau 3 giây
                setTimeout(function() {
                    popupMsg.classList.remove('show');
                }, 3000);
            }
        });
    </script>


</body>
</html>
