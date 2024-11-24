<?php
session_start();

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin-login.php");
    exit();
}

// Bao gồm tệp kết nối cơ sở dữ liệu
require_once 'db-connect.php';

// Thiết lập số bản ghi mỗi trang cho khách hàng và sản phẩm
$customer_records_per_page = 15;
$product_records_per_page = 15;

// Xác định trang hiện tại từ URL, mặc định là 1
$customer_page = isset($_GET['customer_page']) && is_numeric($_GET['customer_page']) ? (int) $_GET['customer_page'] : 1;
$product_page = isset($_GET['product_page']) && is_numeric($_GET['product_page']) ? (int) $_GET['product_page'] : 1;

// Tính toán OFFSET cho khách hàng và sản phẩm
$customer_offset = ($customer_page - 1) * $customer_records_per_page;
$product_offset = ($product_page - 1) * $product_records_per_page;

// Kiểm tra xem có đang lọc khách hàng hôm nay không
$customer_isTodayFilter = isset($_GET['today']) && $_GET['today'] == '1';

// Truy vấn dữ liệu khách hàng với ORDER BY, LIMIT và OFFSET
try {
    if ($customer_isTodayFilter) {
        // Lấy ngày hiện tại
        $today = date('Y-m-d');

        // Truy vấn với điều kiện ngày hiện tại
        $sql = "SELECT id, ten, sdt, diachi, thoigiandathang FROM khachhang 
                WHERE DATE(thoigiandathang) = :today 
                ORDER BY thoigiandathang DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':today', $today, PDO::PARAM_STR);
    } else {
        // Truy vấn không điều kiện lọc
        $sql = "SELECT id, ten, sdt, diachi, thoigiandathang FROM khachhang 
                ORDER BY thoigiandathang DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
    }

    // Liên kết tham số LIMIT và OFFSET cho khách hàng
    $stmt->bindParam(':limit', $customer_records_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $customer_offset, PDO::PARAM_INT);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn khách hàng: " . $e->getMessage());
}

// Tính tổng số bản ghi cho khách hàng
try {
    if ($customer_isTodayFilter) {
        // Truy vấn tổng số bản ghi với điều kiện ngày hiện tại
        $total_stmt = $pdo->prepare("SELECT COUNT(*) FROM khachhang WHERE DATE(thoigiandathang) = :today");
        $total_stmt->bindParam(':today', $today, PDO::PARAM_STR);
    } else {
        // Truy vấn tổng số bản ghi không điều kiện
        $total_stmt = $pdo->prepare("SELECT COUNT(*) FROM khachhang");
    }

    $total_stmt->execute();
    $total_records_customers = $total_stmt->fetchColumn();
    $total_pages_customers = ceil($total_records_customers / $customer_records_per_page);
} catch (PDOException $e) {
    die("Lỗi truy vấn tổng số bản ghi khách hàng: " . $e->getMessage());
}

// Truy vấn dữ liệu sản phẩm với ORDER BY, LIMIT và OFFSET
try {
    $sql_items = "SELECT id, name, description, img, price FROM items_detail ORDER BY id DESC LIMIT :limit OFFSET :offset";
    $stmt_items = $pdo->prepare($sql_items);
    $stmt_items->bindParam(':limit', $product_records_per_page, PDO::PARAM_INT);
    $stmt_items->bindParam(':offset', $product_offset, PDO::PARAM_INT);
    $stmt_items->execute();
    $products = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn sản phẩm: " . $e->getMessage());
}

// Tính tổng số bản ghi cho sản phẩm
try {
    $sql_total_items = "SELECT COUNT(*) FROM items_detail";
    $stmt_total_items = $pdo->prepare($sql_total_items);
    $stmt_total_items->execute();
    $total_records_items = $stmt_total_items->fetchColumn();
    $total_pages_items = ceil($total_records_items / $product_records_per_page);
} catch (PDOException $e) {
    die("Lỗi truy vấn tổng số sản phẩm: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin MiMi</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- SIDE BAR -->
    <div class="sidebar">
        <h1>Chào đại ca <span class="user"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span></h1>
        <div class="customer-infor-bar">
            <button class="customer-infor-bar-btn" id="toggleCustomerInfo" aria-expanded="false" aria-controls="customerInfoCont">
                Thông tin khách hàng
            </button>
        </div>
        <div class="product-infor-bar">
            <button class="product-infor-bar-btn" id="toggleProductInfo" aria-expanded="false" aria-controls="productInfoCont">
                Sản phẩm
            </button>
        </div>
        <div class="logout"><a href="logout.php">Đăng xuất</a></div>
    </div>
    <!-- SIDE BAR END -->

    <!-- PHẦN BẢNG KHÁCH HÀNG -->
    <div class="customer-info-cont" id="customerInfoCont">
        <div class="customer-info">
            <!-- Nút lọc "Khách hàng hôm nay" và "Tất cả khách hàng" -->
            <div class="filter-buttons">
                <a href="admin.php<?php echo $customer_isTodayFilter ? '' : '?today=1'; ?>" class="filter-btn <?php echo $customer_isTodayFilter ? 'active' : ''; ?>">
                    Khách hàng hôm nay
                </a>
                <a href="admin.php<?php echo $customer_isTodayFilter ? '?customer_page=' . $customer_page : ''; ?>" class="filter-btn <?php echo !$customer_isTodayFilter ? 'active' : ''; ?>">
                    Tất cả khách hàng
                </a>
            </div>
            <!-- Bảng thông tin khách hàng -->
            <table>
                <thead>
                    <tr>
                        <th>Số thứ tự</th>
                        <th>Tên khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Thời gian đặt hàng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($customers && count($customers) > 0): ?>
                        <?php foreach ($customers as $index => $customer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer_offset + $index + 1); ?></td>
                                <td><?php echo htmlspecialchars($customer['ten']); ?></td>
                                <td><?php echo htmlspecialchars($customer['sdt']); ?></td>
                                <td><?php echo htmlspecialchars($customer['diachi']); ?></td>
                                <td>
                                    <?php 
                                        if (!empty($customer['thoigiandathang'])) {
                                            $timestamp = strtotime($customer['thoigiandathang']);
                                            echo htmlspecialchars(date("H:i d/m/Y", $timestamp));
                                        } else {
                                            echo "Không xác định";
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Phân Trang Khách Hàng -->
            <div class="pagination">
                <?php 
                    // Hàm để xây dựng URL phân trang khách hàng
                    function build_customer_url($page, $isTodayFilter) {
                        $params = [];
                        if ($isTodayFilter) {
                            $params['today'] = '1';
                        }
                        if ($page > 1) {
                            $params['customer_page'] = $page;
                        }
                        return 'admin.php' . (count($params) > 0 ? '?' . http_build_query($params) : '');
                    }
                ?>

                <?php if ($customer_page > 1): ?>
                    <a href="<?php echo build_customer_url($customer_page - 1, $customer_isTodayFilter); ?>">&laquo; Trang trước</a>
                <?php endif; ?>

                <?php
                // Hiển thị số trang cho khách hàng
                for ($i = 1; $i <= $total_pages_customers; $i++):
                    if ($i == $customer_page):
                ?>
                        <span><?php echo $i; ?></span>
                <?php else: ?>
                        <a href="<?php echo build_customer_url($i, $customer_isTodayFilter); ?>"><?php echo $i; ?></a>
                <?php
                    endif;
                endfor;
                ?>

                <?php if ($customer_page < $total_pages_customers): ?>
                    <a href="<?php echo build_customer_url($customer_page + 1, $customer_isTodayFilter); ?>">Trang sau &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- PHẦN BẢNG KHÁCH HÀNG END -->

    <!-- PHẦN BẢNG SẢN PHẨM -->
    <div class="product-info-cont" id="productInfoCont" style="display: none;">
        <div class="product-info">
            <!-- Bảng thông tin sản phẩm -->
            <table id="productsTable">
                <thead>
                    <tr>
                        <th>Số thứ tự</th>
                        <th>Tên sản phẩm</th>
                        <th>Mô tả</th>
                        <th>Ảnh</th>
                        <th>Giá</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($products && count($products) > 0):
                        foreach ($products as $index => $product):
                    ?>
                            <tr data-id="<?php echo htmlspecialchars($product['id']); ?>">
                                <td><?php echo htmlspecialchars($product_offset + $index + 1); ?></td>
                                <td class="editable" data-field="name"><?php echo htmlspecialchars($product['name']); ?></td>
                                <td class="editable" data-field="description"><?php echo htmlspecialchars($product['description']); ?></td>
                                <td>
                                    <img src="image/upload/<?php echo htmlspecialchars($product['img']); ?>" alt="Ảnh sản phẩm" width="100">
                                </td>
                                <td class="editable" data-field="price"><?php echo htmlspecialchars($product['price']); ?></td>
                                <td>
                                    <button class="save-btn" data-id="<?php echo htmlspecialchars($product['id']); ?>" style="display: none;">Lưu</button>
                                    <button class="cancel-btn" data-id="<?php echo htmlspecialchars($product['id']); ?>" style="display: none;">Hủy</button>
                                </td>
                            </tr>
                    <?php
                        endforeach;
                    else:
                    ?>
                        <tr>
                            <td colspan="6">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Phân Trang Sản Phẩm -->
            <div class="pagination">
                <?php
                // Hàm để xây dựng URL phân trang sản phẩm
                function build_product_url($page) {
                    $params = [];
                    if ($page > 1) {
                        $params['product_page'] = $page;
                    }
                    return 'admin.php' . (count($params) > 0 ? '?' . http_build_query($params) : '');
                }

                // Số lượng trang hiển thị trước và sau trang hiện tại
                $adjacents = 1;

                if ($total_pages_items > 1) {
                    echo '<ul class="pagination-list">';

                    // Hiển thị nút "Trang đầu"
                    if ($product_page > 1) {
                        echo '<li><a href="' . build_product_url(1) . '">Trang đầu</a></li>';
                    } else {
                        echo '<li class="disabled">Trang đầu</li>';
                    }

                    // Hiển thị nút "Trang trước"
                    if ($product_page > 1) {
                        echo '<li><a href="' . build_product_url($product_page - 1) . '">&laquo; Trang trước</a></li>';
                    } else {
                        echo '<li class="disabled">&laquo; Trang trước</li>';
                    }

                    // Hiển thị dấu "..." nếu cần thiết trước các trang liền kề
                    if ($product_page > ($adjacents + 2)) {
                        echo '<li class="disabled">...</li>';
                    }

                    // Hiển thị các trang liền kề
                    for ($i = max(1, $product_page - $adjacents); $i <= min($product_page + $adjacents, $total_pages_items); $i++) {
                        if ($i == $product_page) {
                            echo '<li class="active">' . $i . '</li>';
                        } else {
                            echo '<li><a href="' . build_product_url($i) . '">' . $i . '</a></li>';
                        }
                    }

                    // Hiển thị dấu "..." nếu cần thiết sau các trang liền kề
                    if ($product_page < ($total_pages_items - ($adjacents + 1))) {
                        echo '<li class="disabled">...</li>';
                    }

                    // Hiển thị nút "Trang sau"
                    if ($product_page < $total_pages_items) {
                        echo '<li><a href="' . build_product_url($product_page + 1) . '">Trang sau &raquo;</a></li>';
                    } else {
                        echo '<li class="disabled">Trang sau &raquo;</li>';
                    }

                    // Hiển thị nút "Trang cuối"
                    if ($product_page < $total_pages_items) {
                        echo '<li><a href="' . build_product_url($total_pages_items) . '">Trang cuối</a></li>';
                    } else {
                        echo '<li class="disabled">Trang cuối</li>';
                    }

                    echo '</ul>';
                }
                ?>
            </div>

    <!-- PHẦN BẢNG SẢN PHẨM END -->

    <!-- Bao gồm tệp JavaScript -->
    <script src="admin.js"></script>
</body>
</html>

<?php
// Đóng kết nối (không bắt buộc với PDO, nhưng bạn có thể đặt biến $pdo thành null nếu muốn)
$pdo = null;
?>
