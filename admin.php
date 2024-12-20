<?php
session_start();

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin-login.php");
    exit();
}

// Bao gồm tệp kết nối cơ sở dữ liệu
require_once '../config/db-connect.php';

// Thiết lập số bản ghi mỗi trang cho khách hàng và sản phẩm
$customer_records_per_page = 15;
$product_records_per_page = 15;

// Xác định trang hiện tại từ URL, mặc định là 1
$customer_page = isset($_GET['customer_page']) && is_numeric($_GET['customer_page']) ? (int)$_GET['customer_page'] : 1;
$product_page = isset($_GET['product_page']) && is_numeric($_GET['product_page']) ? (int)$_GET['product_page'] : 1;

// Lấy search từ URL nếu có
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Tính toán OFFSET cho khách hàng và sản phẩm
$customer_offset = ($customer_page - 1) * $customer_records_per_page;
$product_offset = ($product_page - 1) * $product_records_per_page;

// Kiểm tra xem có đang lọc khách hàng hôm nay không
$customer_isTodayFilter = isset($_GET['today']) && $_GET['today'] == '1';

// Xử lý các form gửi từ slider selection
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_profiles']) && isset($_POST['profile_products'])) {
        $selected_products = $_POST['profile_products'];

        // Kiểm tra số lượng sản phẩm không vượt quá 9
        if (count($selected_products) > 9) {
            die("Chỉ có thể chọn tối đa 9 sản phẩm cho phần đầu trang.");
        }

        // Xóa các sản phẩm cũ trong bảng index_profiles
        try {
            $sql_delete_profiles = "DELETE FROM index_profiles";
            $stmt_delete_profiles = $pdo->prepare($sql_delete_profiles);
            $stmt_delete_profiles->execute();
        } catch (PDOException $e) {
            die("Lỗi khi xóa sản phẩm cũ: " . $e->getMessage());
        }

        // Thêm các sản phẩm mới
        try {
            $sql_insert_profiles = "INSERT INTO index_profiles (product_id) VALUES (:product_id)";
            $stmt_insert_profiles = $pdo->prepare($sql_insert_profiles);
            foreach ($selected_products as $product_id) {
                $stmt_insert_profiles->execute([':product_id' => $product_id]);
            }
        } catch (PDOException $e) {
            die("Lỗi khi thêm sản phẩm mới: " . $e->getMessage());
        }

        $successMessage = "Đã lưu sản phẩm đầu trang thành công!";
    }

    if (isset($_POST['save_slider1']) && isset($_POST['slider1_products'])) {
        $slider_type = 'slider1';
        $selected_products = $_POST['slider1_products'];
        
        // Xóa các sản phẩm cũ cho slider1
        try {
            $sql_delete = "DELETE FROM index_sliders WHERE slider_type = 'slider1'";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->execute();
        } catch (PDOException $e) {
            die("Lỗi khi xóa sản phẩm cũ cho slider1: " . $e->getMessage());
        }

        // Thêm các sản phẩm mới cho slider1
        try {
            $sql_insert = "INSERT INTO index_sliders (slider_type, product_id, display_order) VALUES (:slider_type, :product_id, :display_order)";
            $stmt_insert = $pdo->prepare($sql_insert);
            foreach ($selected_products as $order => $product_id) {
                $stmt_insert->execute([
                    ':slider_type' => $slider_type,
                    ':product_id' => $product_id,
                    ':display_order' => $order + 1
                ]);
            }
        } catch (PDOException $e) {
            die("Lỗi khi thêm sản phẩm cho slider1: " . $e->getMessage());
        }

        $successMessage = "Đã lưu sản phẩm cho Slider 1 thành công!";
    }

    if (isset($_POST['save_slider2']) && isset($_POST['slider2_products'])) {
        $slider_type = 'slider2';
        $selected_products = $_POST['slider2_products'];
        
        // Xóa các sản phẩm cũ cho slider2
        try {
            $sql_delete = "DELETE FROM index_sliders WHERE slider_type = 'slider2'";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->execute();
        } catch (PDOException $e) {
            die("Lỗi khi xóa sản phẩm cũ cho slider2: " . $e->getMessage());
        }

        // Thêm các sản phẩm mới cho slider2
        try {
            $sql_insert = "INSERT INTO index_sliders (slider_type, product_id, display_order) VALUES (:slider_type, :product_id, :display_order)";
            $stmt_insert = $pdo->prepare($sql_insert);
            foreach ($selected_products as $order => $product_id) {
                $stmt_insert->execute([
                    ':slider_type' => $slider_type,
                    ':product_id' => $product_id,
                    ':display_order' => $order + 1
                ]);
            }
        } catch (PDOException $e) {
            die("Lỗi khi thêm sản phẩm cho slider2: " . $e->getMessage());
        }

        $successMessage = "Đã lưu sản phẩm cho Slider 2 thành công!";
    }
}

// Truy vấn dữ liệu khách hàng với ORDER BY, LIMIT và OFFSET, bao gồm thông tin sản phẩm
try {
    if ($customer_isTodayFilter) {
        // Lấy ngày hiện tại
        $today = date('Y-m-d');

        // Truy vấn với điều kiện ngày hiện tại
        $sql = "SELECT kh.id, kh.ten, kh.sdt, kh.diachi, kh.thoigiandathang, kh.product_id, it.name AS product_name
                FROM khachhang kh
                LEFT JOIN items_detail it ON kh.product_id = it.id
                WHERE DATE(kh.thoigiandathang) = :today 
                ORDER BY kh.thoigiandathang DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':today', $today, PDO::PARAM_STR);
    } else {
        // Truy vấn không điều kiện lọc
        $sql = "SELECT kh.id, kh.ten, kh.sdt, kh.diachi, kh.thoigiandathang, kh.product_id, it.name AS product_name
                FROM khachhang kh
                LEFT JOIN items_detail it ON kh.product_id = it.id
                ORDER BY kh.thoigiandathang DESC 
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

// Truy vấn dữ liệu sản phẩm với ORDER BY, LIMIT và OFFSET, bao gồm filter search
try {
    if (!empty($search)) {
        $sql_items = "SELECT id, name, description, img, price 
                     FROM items_detail 
                     WHERE name LIKE :search 
                     ORDER BY id DESC 
                     LIMIT :limit OFFSET :offset";
        $stmt_items = $pdo->prepare($sql_items);
        $search_param = '%' . $search . '%';
        $stmt_items->bindParam(':search', $search_param, PDO::PARAM_STR);
    } else {
        $sql_items = "SELECT id, name, description, img, price 
                     FROM items_detail 
                     ORDER BY id DESC 
                     LIMIT :limit OFFSET :offset";
        $stmt_items = $pdo->prepare($sql_items);
    }

    $stmt_items->bindParam(':limit', $product_records_per_page, PDO::PARAM_INT);
    $stmt_items->bindParam(':offset', $product_offset, PDO::PARAM_INT);
    $stmt_items->execute();
    $products = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn sản phẩm: " . $e->getMessage());
}

// Tính tổng số bản ghi cho sản phẩm, bao gồm filter search
try {
    if (!empty($search)) {
        $sql_total_items = "SELECT COUNT(*) FROM items_detail WHERE name LIKE :search";
        $stmt_total_items = $pdo->prepare($sql_total_items);
        $stmt_total_items->bindParam(':search', $search_param, PDO::PARAM_STR);
    } else {
        $sql_total_items = "SELECT COUNT(*) FROM items_detail";
        $stmt_total_items = $pdo->prepare($sql_total_items);
    }

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
    <link rel="icon" href="./image/mimi-logo-vuong.png" type="image/png">

    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- SIDE BAR -->
    <div class="sidebar">
        <h1>Chào đại ca <span class="user"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span></h1>
        <!-- xem thông tin khách hàng -->
        <div class="customer-infor-bar">
            <button class="customer-infor-bar-btn" id="toggleCustomerInfo" aria-expanded="false" aria-controls="customerInfoCont">
                Thông tin khách hàng
            </button>
        </div>
        <!-- Chọn sản phẩm đầu trang -->
        <div class="profile-selection-bar">
            <button class="profile-selection-bar-btn" id="toggleProfileSelection" aria-expanded="false" aria-controls="profileSelectionCont">
                Chọn sản phẩm đầu trang
            </button>
        </div>
        <!-- chọn slider sản phẩm -->
        <div class="product-infor-bar">
            <button class="product-infor-bar-btn" id="toggleProductInfo" aria-expanded="false" aria-controls="productInfoCont">
                Sản phẩm
            </button>
        </div>
        <!-- index-slider selection -->
        <div class="index-slider-bar">
            <button class="index-slider-bar-btn" id="toggleIndexSliderInfo" aria-expanded="false" aria-controls="indexSliderInfoCont">
                Chọn sản phẩm cho slider
            </button>
        </div>
        <div class="logout"><a href="logout.php">Đăng xuất</a></div>
    </div>
    <!-- SIDE BAR END -->

    <!-- PHẦN CHỌN SẢN PHẨM CHO SLIDER -->
    <div class="index-slider-info-cont" id="indexSliderInfoCont" style="display: none; padding: 20px;">
        <div class="index-slider-info">
            <div class="slider-forms-container">
                <!-- Form Slider 1 -->
                <div class="slider-form">
                    <h2>Chọn sản phẩm cho Slider 1</h2>
                    <form method="POST" action="admin.php">
                        <input type="hidden" name="slider_type" value="slider1">
                        <div class="product-selection">
                            <?php
                            // Fetch all products
                            try {
                                $sql_all_products = "SELECT id, name FROM items_detail ORDER BY name ASC";
                                $stmt_all_products = $pdo->prepare($sql_all_products);
                                $stmt_all_products->execute();
                                $all_products = $stmt_all_products->fetchAll(PDO::FETCH_ASSOC);
                            } catch (PDOException $e) {
                                die("Lỗi truy vấn sản phẩm: " . $e->getMessage());
                            }

                            // Fetch selected products for slider1
                            try {
                                $sql_selected_slider1 = "SELECT product_id FROM index_sliders WHERE slider_type = 'slider1'";
                                $stmt_selected_slider1 = $pdo->prepare($sql_selected_slider1);
                                $stmt_selected_slider1->execute();
                                $selected_slider1 = $stmt_selected_slider1->fetchAll(PDO::FETCH_COLUMN);
                            } catch (PDOException $e) {
                                die("Lỗi truy vấn sản phẩm đã chọn cho slider1: " . $e->getMessage());
                            }

                            // Display products with checkboxes
                            foreach ($all_products as $product) {
                                $checked = in_array($product['id'], $selected_slider1) ? 'checked' : '';
                                echo '<div class="product-checkbox">';
                                echo '<input type="checkbox" name="slider1_products[]" value="' . htmlspecialchars($product['id']) . '" ' . $checked . '>';
                                echo '<label>' . htmlspecialchars($product['name']) . '</label>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <button type="submit" name="save_slider1" class="save-button">Lưu Slider 1</button>
                    </form>
                </div>

                <!-- Form Slider 2 -->
                <div class="slider-form">
                    <h2>Chọn sản phẩm cho Slider 2</h2>
                    <form method="POST" action="admin.php">
                        <input type="hidden" name="slider_type" value="slider2">
                        <div class="product-selection">
                            <?php
                            // Fetch selected products for slider2
                            try {
                                $sql_selected_slider2 = "SELECT product_id FROM index_sliders WHERE slider_type = 'slider2'";
                                $stmt_selected_slider2 = $pdo->prepare($sql_selected_slider2);
                                $stmt_selected_slider2->execute();
                                $selected_slider2 = $stmt_selected_slider2->fetchAll(PDO::FETCH_COLUMN);
                            } catch (PDOException $e) {
                                die("Lỗi truy vấn sản phẩm đã chọn cho slider2: " . $e->getMessage());
                            }

                            // Display products with checkboxes
                            foreach ($all_products as $product) {
                                $checked = in_array($product['id'], $selected_slider2) ? 'checked' : '';
                                echo '<div class="product-checkbox">';
                                echo '<input type="checkbox" name="slider2_products[]" value="' . htmlspecialchars($product['id']) . '" ' . $checked . '>';
                                echo '<label>' . htmlspecialchars($product['name']) . '</label>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <button type="submit" name="save_slider2" class="save-button">Lưu Slider 2</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- PHẦN CHỌN SẢN PHẨM CHO SLIDER END -->

    <?php if (isset($successMessage)): ?>
        <div class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <!-- PHẦN BẢNG KHÁCH HÀNG -->
    <div class="customer-info-cont" id="customerInfoCont">
        <div class="customer-info">
            <!-- Nút lọc "Khách hàng hôm nay" và "Tất cả khách hàng" -->
            <div class="filter-buttons">
                <a href="admin.php?today=1&customer_page=1" class="filter-btn <?php echo $customer_isTodayFilter ? 'active' : ''; ?>">
                    Khách hàng hôm nay
                </a>
                <a href="admin.php?customer_page=1" class="filter-btn <?php echo !$customer_isTodayFilter ? 'active' : ''; ?>">
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
                        <th>Sản phẩm</th>
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
                                <td><?php echo htmlspecialchars($customer['product_name'] ?? 'Không xác định'); ?></td>
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
                            <td colspan="6">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>

            <!-- Phân Trang Khách Hàng -->
            <div class="pagination-cont">
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

                    <?php 
                        // Hiển thị nút "Trang đầu"
                        if ($customer_page > 1) {
                            echo '<li><a href="' . build_customer_url(1, $customer_isTodayFilter) . '">Trang đầu</a></li>';
                        } else {
                            echo '<li class="disabled">Trang đầu</li>';
                        }

                        // Hiển thị nút "Trang trước"
                        if ($customer_page > 1) {
                            echo '<li><a href="' . build_customer_url($customer_page - 1, $customer_isTodayFilter) . '">&laquo; Trang trước</a></li>';
                        } else {
                            echo '<li class="disabled">&laquo; Trang trước</li>';
                        }

                        // Hiển thị số trang cho khách hàng
                        for ($i = 1; $i <= $total_pages_customers; $i++):
                            if ($i == $customer_page):
                    ?>
                                <li class="active"><?php echo $i; ?></li>
                    <?php else: ?>
                                <li><a href="<?php echo build_customer_url($i, $customer_isTodayFilter); ?>"><?php echo $i; ?></a></li>
                    <?php
                            endif;
                        endfor;
                    ?>

                    <?php 
                        // Hiển thị nút "Trang sau"
                        if ($customer_page < $total_pages_customers) {
                            echo '<li><a href="' . build_customer_url($customer_page + 1, $customer_isTodayFilter) . '">Trang sau &raquo;</a></li>';
                        } else {
                            echo '<li class="disabled">Trang sau &raquo;</li>';
                        }

                        // Hiển thị nút "Trang cuối"
                        if ($customer_page < $total_pages_customers) {
                            echo '<li><a href="' . build_customer_url($total_pages_customers, $customer_isTodayFilter) . '">Trang cuối</a></li>';
                        } else {
                            echo '<li class="disabled">Trang cuối</li>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- PHẦN BẢNG KHÁCH HÀNG END -->

    <!-- PHẦN BẢNG SẢN PHẨM -->
    <div class="product-info-cont" id="productInfoCont" style="display: none;">
        <div class="product-info">
             <!-- Search Filter for Products -->
            <div class="product-search-filter">
                <form method="GET" action="admin.php">
                    <input type="hidden" name="product_page" value="1">
                    <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Tìm kiếm</button>
                </form>
            </div>
            
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
                                    <img src="/image/upload/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="100">
                                    <br>
                                    <button class="upload-image-btn" data-id="<?php echo htmlspecialchars($product['id']); ?>">Tải lên ảnh</button>
                                    <input type="file" accept="image/*" class="upload-image-input" data-id="<?php echo htmlspecialchars($product['id']); ?>" style="display: none;">
                                </td>
                                <td class="editable" data-field="price"><?php echo htmlspecialchars(number_format($product['price'], 0, ',', '.') . 'đ'); ?></td>
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
                // Hàm để xây dựng URL phân trang sản phẩm bao gồm tham số search
                function build_product_url($page, $search) {
                    $params = [];
                    if (!empty($search)) {
                        $params['search'] = $search;
                    }
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
                        echo '<li><a href="' . build_product_url(1, $search) . '">Trang đầu</a></li>';
                    } else {
                        echo '<li class="disabled">Trang đầu</li>';
                    }

                    // Hiển thị nút "Trang trước"
                    if ($product_page > 1) {
                        echo '<li><a href="' . build_product_url($product_page - 1, $search) . '">&laquo; Trang trước</a></li>';
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
                            echo '<li><a href="' . build_product_url($i, $search) . '">' . $i . '</a></li>';
                        }
                    }

                    // Hiển thị dấu "..." nếu cần thiết sau các trang liền kề
                    if ($product_page < ($total_pages_items - ($adjacents + 1))) {
                        echo '<li class="disabled">...</li>';
                    }

                    // Hiển thị nút "Trang sau"
                    if ($product_page < $total_pages_items) {
                        echo '<li><a href="' . build_product_url($product_page + 1, $search) . '">Trang sau &raquo;</a></li>';
                    } else {
                        echo '<li class="disabled">Trang sau &raquo;</li>';
                    }

                    // Hiển thị nút "Trang cuối"
                    if ($product_page < $total_pages_items) {
                        echo '<li><a href="' . build_product_url($total_pages_items, $search) . '">Trang cuối</a></li>';
                    } else {
                        echo '<li class="disabled">Trang cuối</li>';
                    }

                    echo '</ul>';
                }
                ?>
            </div>
        </div>
    </div>
    <!-- PHẦN BẢNG SẢN PHẨM END -->

    <!-- PHẦN CHỌN SẢN PHẨM ĐẦU TRANG -->
    <div class="profile-selection-cont" id="profileSelectionCont" style="display: none; padding: 20px;">
        <div class="profile-selection">
            <h2>Chọn sản phẩm đầu trang (tối đa 9 sản phẩm)</h2>
            <form method="POST" action="admin.php">
                <div class="product-selection">
                    <?php
                    // Fetch tất cả sản phẩm
                    try {
                        $sql_all_products = "SELECT id, name FROM items_detail ORDER BY name ASC";
                        $stmt_all_products = $pdo->prepare($sql_all_products);
                        $stmt_all_products->execute();
                        $all_products = $stmt_all_products->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        die("Lỗi truy vấn sản phẩm: " . $e->getMessage());
                    }

                    // Fetch các sản phẩm đã chọn cho profile-section
                    try {
                        $sql_selected_profiles = "SELECT product_id FROM index_profiles";
                        $stmt_selected_profiles = $pdo->prepare($sql_selected_profiles);
                        $stmt_selected_profiles->execute();
                        $selected_profiles = $stmt_selected_profiles->fetchAll(PDO::FETCH_COLUMN);
                    } catch (PDOException $e) {
                        die("Lỗi truy vấn sản phẩm đã chọn: " . $e->getMessage());
                    }

                    // Hiển thị sản phẩm với checkbox
                    foreach ($all_products as $product) {
                        $checked = in_array($product['id'], $selected_profiles) ? 'checked' : '';
                        echo '<div class="product-checkbox">';
                        echo '<input type="checkbox" name="profile_products[]" value="' . htmlspecialchars($product['id']) . '" ' . $checked . '>';
                        echo '<label>' . htmlspecialchars($product['name']) . '</label>';
                        echo '</div>';
                    }
                    ?>
                </div>
                <button type="submit" name="save_profiles" class="save-button">Lưu Sản Phẩm</button>
            </form>
        </div>
    </div>


    <!-- Bao gồm tệp JavaScript -->
    <script src="admin.js"></script>
</body>
</html>

<?php
// Đóng kết nối
$pdo = null;
?>
