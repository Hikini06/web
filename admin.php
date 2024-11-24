<?php
session_start();

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href = "admin.css" > 
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>
<body>
    <div class="sidebar">
        <!-- Thêm nội dung của bạn ở đây -->
        <h1>Chào đại ca <div class="user"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></div></h1>
        
    </div>
    
   
    <a href="logout.php">Đăng xuất</a>
</body>
</html>
