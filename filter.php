<?php
require 'db-connect.php';

$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = 16;

if (strlen($searchQuery) < 2) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        // Yêu cầu AJAX
        $response = ['error' => "Vui lòng nhập ít nhất 2 ký tự."];
        echo json_encode($response);
        exit;
    } else {
        // Yêu cầu thông thường
        echo "Vui lòng nhập ít nhất 2 ký tự.";
        exit;
    }
}

// Phân tách từ khóa từ chuỗi tìm kiếm
$keywords = explode(' ', $searchQuery);

// Chuẩn bị các tham số và điều kiện tìm kiếm
$params = [];
$conditions = [];

// Điều kiện khớp chính xác cụm từ
$exactPhrase = '%' . $searchQuery . '%';
$params[':exactPhrase'] = $exactPhrase;
$conditions[] = "(name LIKE :exactPhrase COLLATE utf8_unicode_ci OR description LIKE :exactPhrase COLLATE utf8_unicode_ci)";

// Điều kiện khớp với từng từ khóa
$keywordConditions = [];
foreach ($keywords as $index => $word) {
    $word = trim($word);
    if ($word !== '') {
        $keywordParam = ':keyword' . $index;
        $keywordConditions[] = "(name LIKE $keywordParam COLLATE utf8_unicode_ci OR description LIKE $keywordParam COLLATE utf8_unicode_ci)";
        $params[$keywordParam] = '%' . $word . '%';
    }
}

// Nếu có từ khóa, thêm điều kiện
if (!empty($keywordConditions)) {
    // Kết hợp điều kiện bằng OR để sản phẩm chứa ít nhất một từ khóa
    $conditions[] = '(' . implode(' OR ', $keywordConditions) . ')';
}

// Xây dựng câu lệnh WHERE
$whereClause = implode(' OR ', $conditions);

// Truy vấn SQL
$sql = "SELECT * FROM items_detail 
        WHERE $whereClause
        ORDER BY 
            CASE 
                WHEN name LIKE :exactPhrase COLLATE utf8_unicode_ci THEN 1
                WHEN description LIKE :exactPhrase COLLATE utf8_unicode_ci THEN 1
                ELSE 2 
            END,
            name
        LIMIT :limit OFFSET :offset";

// Chuẩn bị truy vấn
$query = $pdo->prepare($sql);

// Gán giá trị cho các tham số tìm kiếm
foreach ($params as $key => $value) {
    $query->bindValue($key, $value, PDO::PARAM_STR);
}

// Gán giá trị cho limit và offset
$query->bindValue(':limit', $limit, PDO::PARAM_INT);
$query->bindValue(':offset', $offset, PDO::PARAM_INT);

// Thực thi truy vấn
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);

// Đếm tổng số sản phẩm phù hợp
$countSql = "SELECT COUNT(*) FROM items_detail WHERE $whereClause";
$countQuery = $pdo->prepare($countSql);

// Gán giá trị cho các tham số đếm
foreach ($params as $key => $value) {
    $countQuery->bindValue($key, $value, PDO::PARAM_STR);
}

$countQuery->execute();
$totalProducts = $countQuery->fetchColumn();

$hasMore = $offset + $limit < $totalProducts;

// Xử lý yêu cầu AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    $response = ['results' => $results, 'hasMore' => $hasMore];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Nếu không phải AJAX, hiển thị trang HTML

// Lấy danh sách ID của các sản phẩm đang hiển thị
$currentProductIDs = array_column($results, 'id');

// Tính giá trị trung bình của các ID
if (!empty($currentProductIDs)) {
    $averageID = array_sum($currentProductIDs) / count($currentProductIDs);
} else {
    $averageID = 0;
}

// Chuẩn bị placeholders cho câu lệnh SQL
$placeholders = implode(',', array_fill(0, count($currentProductIDs), '?'));

// Truy vấn lấy sản phẩm gợi ý
$suggestedSql = "SELECT * FROM items_detail
                 WHERE id NOT IN ($placeholders)
                 ORDER BY ABS(id - ?)
                 LIMIT 16";

$suggestedQuery = $pdo->prepare($suggestedSql);

// Gán giá trị cho placeholders
$i = 1;
foreach ($currentProductIDs as $id) {
    $suggestedQuery->bindValue($i++, $id, PDO::PARAM_INT);
}

// Gán giá trị cho averageID
$suggestedQuery->bindValue($i, $averageID, PDO::PARAM_INT);

// Thực thi truy vấn
$suggestedQuery->execute();
$suggestedProducts = $suggestedQuery->fetchAll(PDO::FETCH_ASSOC);
// Kiểm tra mảng $suggestedProducts
foreach ($suggestedProducts as $index => $product) {
    if (empty($product) || !isset($product['id'])) {
        echo "Sản phẩm tại vị trí $index bị rỗng hoặc không hợp lệ.";
    }
}

?>







<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiệm hoa MiMi</title>
    <link rel="stylesheet" href="filter.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="search-results-cont">
        <div class="search-results">
            <h1>Kết quả tìm kiếm cho: "<?= htmlspecialchars($searchQuery) ?>"</h1>
            <?php if (!empty($results)): ?>
                <div id="product-container">
                    <div class="product-grid">
                        <?php foreach ($results as $result): ?>
                            <div class="product-item">
                                <a href="product-detail.php?id=<?= htmlspecialchars($result['id']) ?>">
                                    <img src="<?= htmlspecialchars($result['img']) ?>" alt="<?= htmlspecialchars($result['name']) ?>">
                                    <h3><?= htmlspecialchars($result['name']) ?></h3>
                                    <p><?= htmlspecialchars(number_format($result['price'])) ?>đ</p>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php if ($hasMore): ?>
                    <button id="load-more" data-offset="<?= $offset + $limit ?>" data-query="<?= htmlspecialchars($searchQuery) ?>">Xem thêm</button>
                <?php endif; ?>
            <?php else: ?>
                <p>Không tìm thấy sản phẩm nào phù hợp.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="suggested-products">
        <h2>Sản phẩm gợi ý</h2>
        <div class="suggested-carousel">
            <button class="carousel-btn carousel-prev">&lt;</button>
            <div class="carousel-track-container">
                <ul class="carousel-track">
                <?php foreach ($suggestedProducts as $index => $product): ?>
                    <?php if (!empty($product) && isset($product['id'])): ?>
                        <li class="carousel-slide" data-index="<?= $index ?>">
                            <div class="carousel-slide-inner">
                                <a href="product-detail.php?id=<?= htmlspecialchars($product['id']) ?>">
                                    <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                                    <p><?= htmlspecialchars(number_format($product['price'])) ?>đ</p>
                                </a>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                </ul>
            </div>
            <button class="carousel-btn carousel-next">&gt;</button>
        </div>
    </div>

    

    <?php include 'footer.php'; ?>
    <script src="filter.js"></script>
</body>
</html>
