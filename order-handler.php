<?php
include 'db-connect.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = isset($_POST['ten']) ? trim($_POST['ten']) : '';
    $sdt = isset($_POST['sdt']) ? trim($_POST['sdt']) : '';
    $diachi = isset($_POST['diachi']) ? trim($_POST['diachi']) : '';
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;

    // Kiểm tra product_id
    if ($product_id === null || $product_id <= 0) {
        $response['message'] = 'Thông tin sản phẩm không hợp lệ!';
    } elseif (empty($ten) || empty($sdt) || empty($diachi)) {
        $response['message'] = 'Vui lòng nhập đầy đủ thông tin!';
    } elseif (!preg_match('/^\d{9,10}$/', $sdt)) {
        $response['message'] = 'Số điện thoại không hợp lệ!';
    } else {
        try {
            $query = "INSERT INTO khachhang (ten, sdt, diachi, thoigiandathang, product_id) VALUES (:ten, :sdt, :diachi, CURRENT_TIMESTAMP, :product_id)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':ten', $ten, PDO::PARAM_STR);
            $stmt->bindParam(':sdt', $sdt, PDO::PARAM_STR);
            $stmt->bindParam(':diachi', $diachi, PDO::PARAM_STR);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
    
            $response['success'] = true;
            $response['message'] = 'Đặt hàng thành công!';
        } catch (PDOException $e) {
            $response['message'] = 'Lỗi khi lưu dữ liệu: ' . $e->getMessage();
        }
    }
} 

header('Content-Type: application/json');
echo json_encode($response);
?>
