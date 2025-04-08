<?php
session_start();
if (!isset($_SESSION['pass']) || $_SESSION['pass'] !== true) {
    header("Location: index.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "diy_tutorials";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("連線失敗: " . $conn->connect_error);

$id = $_GET['id'];
$sql = "DELETE FROM articles WHERE id = $id";
$conn->query($sql);

header("Location: admin.php");
$conn->close();
?>