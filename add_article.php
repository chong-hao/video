<?php
session_start();
if (!isset($_SESSION['pass']) || $_SESSION['pass'] !== true) {
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

// 插入文章資料到資料庫，並設置為上架狀態
$sql = "INSERT INTO articles (title, summary, content, is_published) VALUES ('$title', '$summary', '$content', 1)";
if ($conn->query($sql) === TRUE) {
    $articleId = $conn->insert_id; // 獲取剛插入的文章 ID

    // 新增完成後跳轉回管理後台
    header("Location: admin.php?success=1");
    exit();
} else {
    // 顯示 SQL 錯誤訊息
    die("新增文章失敗: " . $conn->error);
}

$conn->close();
?>