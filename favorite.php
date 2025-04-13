<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "diy_tutorials";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("連線失敗: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];
$article_id = intval($_POST['article_id']);

// 新增收藏記錄
$stmt = $conn->prepare("INSERT INTO favorites (user_id, article_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $article_id);
$stmt->execute();

header("Location: article_view.php?id=$article_id");
$stmt->close();
$conn->close();
?>