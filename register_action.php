<?php
// 設置資料庫連接
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "diy_tutorials"; // 假設您的資料庫名稱是 diy_tutorials

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查資料庫連接
if ($conn->connect_error) {
    die("資料庫連接失敗: " . $conn->connect_error);
}

// 取得用戶輸入
$user = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// 檢查密碼是否一致
if ($password !== $confirm_password) {
    echo "<script>alert('密碼與確認密碼不一致！'); window.location.href='register.php';</script>";
    exit;
}

// 檢查帳號格式：台灣手機號碼或電子郵件
if (!preg_match('/^(09\d{8})$/', $user) && !filter_var($user, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('帳號必須是有效的台灣手機號碼（09開頭+8位數字）或電子郵件格式！'); window.location.href='register.php';</script>";
    exit;
}

// 密碼加密
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 插入資料庫
$sql = "INSERT INTO users (username, password) VALUES ('$user', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('註冊成功！請登入。'); window.location.href='login.php';</script>";
} else {
    echo "錯誤: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
