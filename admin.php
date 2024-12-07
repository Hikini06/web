<?php
session_start();

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin-login.php");
    exit();
}

// Bao gồm tệp kết nối cơ sở dữ liệu
require_once '../config/db-connect.php';
require_once 'functions.php';

// Thiết lập số bản ghi mỗi trang cho khách hàng và sản phẩm
$customer_records_per_page = 15;
$product_records_per_page = 15;

// Xác định trang hiện tại từ URL, mặc định là 1
$customer_page = isset($_GET['customer_page']) && is_numeric($_GET['customer_page']) ? (int)$_GET['customer_page'] : 1;
$product_page = isset($_GET['product_page']) && is_numeric($_GET['product_page']) ? (int)$_GET['product_page'] : 1;

// Lấy search từ URL nếu có
$search = isset($_GET['search']) ? trim($_GET['search']) : null; // Tham số tìm kiếm
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1; // Tham số trang
$limit = 50; // Số sản phẩm mỗi trang
$offset = ($page - 1) * $limit; // Tính toán offset

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
    <!-- <base href="http://localhost/web_dm_lum/"> -->
    <link rel="icon" href="./image/mimi-logo-vuong.png" type="image/png">
    <link rel="stylesheet" href="<?php echo asset('admin.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- SIDE BAR -->
    <div class="sidebar">
        <h1>Chào đại ca <span class="user"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span></h1>
        <!-- xem thông tin khách hàng -->
        <div class="customer-infor-bar">
            <button class="customer-infor-bar-btn sidebar-button" id="toggleCustomerInfo" aria-expanded="false" aria-controls="customerInfoCont">
                Thông tin khách hàng
            </button>
        </div>
        <!-- Chọn sản phẩm đầu trang -->
        <div class="profile-selection-bar">
            <button class="profile-selection-bar-btn sidebar-button" id="toggleProfileSelection" aria-expanded="false" aria-controls="profileSelectionCont">
                Chọn sản phẩm đầu trang
            </button>
        </div>
        <!-- index-slider selection -->
        <div class="index-slider-bar">
            <button class="index-slider-bar-btn sidebar-button" id="toggleIndexSliderInfo" aria-expanded="false" aria-controls="indexSliderInfoCont">
                Chọn sản phẩm cho slider
            </button>
        </div>
         <!-- Thêm nút mới cho chức năng quản lý sản phẩm -->
        <div class="product-change-bar">
            <button class="product-change-bar-btn sidebar-button" id="toggleProductChange" aria-expanded="false" aria-controls="productChangeCont">
                Chỉnh sửa sản phẩm
            </button>
        </div>
        <!-- Thêm nút "Chỉnh sửa danh mục" -->
        <div class="category-edit-bar">
            <button class="category-edit-bar-btn sidebar-button" id="toggleCategoryEdit" aria-expanded="false" aria-controls="categoryEditCont">
                Chỉnh sửa danh mục
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

    <!-- PHẦN QUẢN LÝ SẢN PHẨM -->
    <div class="product-change-cont" id="productChangeCont" style="display: none; padding: 20px;">
        <div class="product-change">
            <h2>Quản lý Sản phẩm</h2>
            <table id="productChangeTable">
                <thead>
                    <tr>
                        <th>Categories</th>
                        <th>Subcategories</th>
                        <th>Items</th>
                        <th>Items Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <!-- Categories Column -->
                        <td>
                            <select id="categoriesSelect">
                                <option value="">-- Chọn Category --</option>
                                <?php
                                // Fetch all categories
                                try {
                                    $sql_categories = "SELECT id, name FROM categories ORDER BY name ASC";
                                    $stmt_categories = $pdo->prepare($sql_categories);
                                    $stmt_categories->execute();
                                    $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
                                } catch (PDOException $e) {
                                    die("Lỗi truy vấn categories: " . $e->getMessage());
                                }

                                foreach ($categories as $category) {
                                    echo '<option value="' . htmlspecialchars($category['id']) . '">' . htmlspecialchars($category['name']) . '</option>';
                                }
                                ?>
                            </select>
                            <button class="add-category-btn">Add</button>
                            <button class="delete-category-btn">Delete</button>
                        </td>

                        <!-- Subcategories Column -->
                        <td>
                            <select id="subcategoriesSelect" disabled>
                                <option value="">-- Chọn Subcategory --</option>
                            </select>
                            <button class="add-subcategory-btn" disabled>Add</button>
                            <button class="delete-subcategory-btn" disabled>Delete</button>
                        </td>

                        <!-- Items Column -->
                        <td>
                            <select id="itemsSelect" disabled>
                                <option value="">-- Chọn Item --</option>
                            </select>
                            <button class="add-item-btn" disabled>Add</button>
                            <button class="delete-item-btn" disabled>Delete</button>
                        </td>

                        <!-- Items Detail Column -->
                        <td>
                            <select id="itemsDetailSelect" disabled>
                                <option value="">-- Chọn Items Detail --</option>
                            </select>
                            <button class="add-item-detail-btn" disabled>Add</button>
                            <button class="delete-item-detail-btn" disabled>Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Bảng mới cho Items Option -->
            <div class="items-option-cont" id="itemsOptionCont" style="padding: 20px;">
                <div class="items-option">
                    <h2>Items Option</h2>

                    <!-- Nút thêm tùy chọn mới -->
                    <button id="addOptionBtn" style="margin-bottom:10px;">Thêm tùy chọn mới</button>

                    <!-- Form thêm tùy chọn mới (ẩn ban đầu) -->
                    <div id="addOptionForm" style="display:none; margin-bottom:20px;">
                        <h3>Thêm Tùy Chọn Mới</h3>
                        <label>Nhóm: 
                            <select id="newOptionGroup">
                                <option value="Màu sắc">Màu sắc</option>
                                <option value="Số lượng">Số lượng</option>
                                <option value="Tùy chọn">Tùy chọn</option>
                                <option value="Phụ kiện">Phụ kiện</option>
                            </select>
                        </label>
                        <br><br>
                        <label>Tên Tùy Chọn: <input type="text" id="newOptionName"></label><br><br>
                        <label>Giá Thêm: <input type="number" id="newOptionPrice" step="1000" value="0"></label><br><br>
                        <button id="saveNewOptionBtn">Lưu</button>
                        <button id="cancelNewOptionBtn">Hủy</button>
                    </div>

                    <table id="itemsOptionTable">
                        <thead>
                            <tr>
                                <th>Nhóm</th>
                                <th>Tên Tùy Chọn</th>
                                <th>Giá Thêm</th>
                                <th>Ảnh</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Nội dung Items Option được chèn bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PHẦN BẢNG SẢN PHẨM -->
            <div class="product-info">
                <h2>Danh sách Sản phẩm</h2>
                <input type="text" id="productSearchInput" placeholder="Tìm kiếm sản phẩm theo tên">
                <!-- ... trước table -->
                <!-- Trước table -->
                <table id="productsTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>ID</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Mô Tả</th>
                            <th>Ảnh</th>
                            <th>Giá</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table rows will be inserted here via JavaScript -->
                    </tbody>
                </table>
                <!-- Đặt điều khiển phân trang sau table -->
                <div id="paginationControls">
                    <button id="prevPageBtn" disabled>Trang trước</button>
                    <span id="currentPage">Trang 1</span>
                    <button id="nextPageBtn">Trang sau</button>
                </div>

            </div>
        </div>
    </div>
    <!-- PHẦN QUẢN LÝ SẢN PHẨM END -->
    
    <!-- PHẦN CHỈNH SỬA DANH MỤC -->
    <div class="category-edit-cont" id="categoryEditCont" style="display: none; padding: 20px;">
        <div class="category-edit">
            <h2>Chỉnh sửa Danh mục</h2>
            <!-- Dropdown chọn Subcategories -->
            <label for="editSubcategoriesSelect">Subcategories:</label>
            <select id="editSubcategoriesSelect">
                <option value="">-- Chọn Subcategory --</option>
                <!-- Các tùy chọn sẽ được chèn bằng JavaScript -->
            </select>
            
            <!-- Bảng hiển thị Items -->
            <table id="categoryEditTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Mô Tả</th>
                        <th>Ảnh</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Các dòng Items sẽ được chèn bằng JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- PHẦN CHỈNH SỬA DANH MỤC END -->

    <div id="imagePreview" class="image-preview" style="display: none;">
        <img src="" alt="Image Preview">
    </div>
    <script src="<?php echo asset('admin.js'); ?>"></script>
</body>
</html>

<?php
// Đóng kết nối
$pdo = null;
?>
