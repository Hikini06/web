<?php
// Thông tin kết nối database
$host = '45.252.249.25';
$dbname = 'zutfakaz_mimi';
$username = 'zutfakaz_truonggiang';
$password = 'Jimih2815!@';

// Kết nối đến database
try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    
    // Thiết lập chế độ lỗi PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // echo "Kết nối thành công!";
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>
