<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ad_login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "diy_tutorials";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

$title = $_POST['title'];
$summary = $_POST['summary'];
$content = $_POST['content'] ?? ''; // 新增文章內容字段

// 使用參數化查詢插入文章資料
$stmt = $conn->prepare("INSERT INTO articles (title, summary, content, is_published) VALUES (?, ?, ?, 1)");
$stmt->bind_param("sss", $title, $summary, $content);

if ($stmt->execute()) {
    // 新增完成後跳轉回管理後台
    header("Location: admin.php?success=1");
    exit();
} else {
    // 顯示 SQL 錯誤訊息
    die("新增文章失敗: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>