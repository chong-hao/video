<?php
// 連接 MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "diy_tutorials";

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 獲取文章 ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 查詢文章內容
$sql = "SELECT title, summary, content, created_at FROM articles WHERE id = $id AND is_published = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $title = htmlspecialchars($row['title']);
    $summary = htmlspecialchars($row['summary']);
    $content = nl2br(htmlspecialchars($row['content']));
    $created_at = date('Y-m-d', strtotime($row['created_at']));
} else {
    die("文章不存在或尚未上架");
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- 導覽列 -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">文章平台</a>
    </nav>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title"><?php echo $title; ?></h1>
                <div class="text-muted">發布日期：<?php echo $created_at; ?></div>
            </div>
            <div class="card-body">
                <div class="card-text mb-4">
                    <h5>摘要：</h5>
                    <p class="font-italic"><?php echo $summary; ?></p>
                    <hr>
                </div>
                <div class="card-text">
                    <?php 
                    if (!empty($content)) {
                        echo $content; 
                    } else {
                        echo "<p>本文章尚無完整內容。</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="index.php" class="btn btn-secondary">返回首頁</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>