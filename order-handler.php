<?php
include 'db-connect.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = isset($_POST['ten']) ? trim($_POST['ten']) : '';
    $sdt = isset($_POST['sdt']) ? trim($_POST['sdt']) : '';
    $diachi = isset($_POST['diachi']) ? trim($_POST['diachi']) : '';

    // Kiểm tra dữ liệu
    if (empty($ten) || empty($sdt) || empty($diachi)) {
        $response['message'] = 'Vui lòng nhập đầy đủ thông tin!';
    } elseif (!preg_match('/^\d{9,10}$/', $sdt)) {
        $response['message'] = 'Số điện thoại không hợp lệ!';
    } else {
        try {
            $query = "INSERT INTO khachhang (ten, sdt, diachi, thoigiandathang) VALUES (:ten, :sdt, :diachi, CURRENT_TIMESTAMP)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':ten', $ten, PDO::PARAM_STR);
            $stmt->bindParam(':sdt', $sdt, PDO::PARAM_STR);
            $stmt->bindParam(':diachi', $diachi, PDO::PARAM_STR);
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
