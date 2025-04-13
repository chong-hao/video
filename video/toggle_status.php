<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "diy_tutorials";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("連線失敗: " . $conn->connect_error);

$id = intval($_GET['id']);
$status = intval($_GET['status']);

// 使用參數化查詢更新文章狀態
$stmt = $conn->prepare("UPDATE articles SET is_published = ? WHERE id = ?");
$stmt->bind_param("ii", $status, $id);
$stmt->execute();

header("Location: admin.php");
$stmt->close();
$conn->close();
?>
