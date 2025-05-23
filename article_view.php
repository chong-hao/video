<?php
session_start();

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

// 使用參數化查詢查詢文章內容
$stmt = $conn->prepare("SELECT title, summary, content, created_at FROM articles WHERE id = ? AND is_published = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $title = htmlspecialchars($row['title']);
    $summary = htmlspecialchars($row['summary']);
    $content = nl2br(htmlspecialchars($row['content']));
    $created_at = date('Y-m-d', strtotime($row['created_at']));
} else {
    die("文章不存在或尚未上架");
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $is_favorited = false;

    // 檢查是否已收藏
    $stmt = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND article_id = ?");
    $stmt->bind_param("ii", $user_id, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $is_favorited = true;
    }
    $stmt->close();
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($is_favorited): ?>
                        <form action="unfavorite.php" method="POST" style="display:inline;">
                            <input type="hidden" name="article_id" value="<?php echo $id; ?>">
                            <button type="submit" class="btn btn-danger">取消收藏</button>
                        </form>
                    <?php else: ?>
                        <form action="favorite.php" method="POST" style="display:inline;">
                            <input type="hidden" name="article_id" value="<?php echo $id; ?>">
                            <button type="submit" class="btn btn-primary">加入收藏</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php" class="btn btn-secondary">登入以收藏</a>
                <?php endif; ?>
                <a href="index.php" class="btn btn-secondary">返回首頁</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>