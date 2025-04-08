<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // 確保只有在 Session 尚未啟動時才啟動
}

// 檢查是否已登入
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ad_login.php");
    exit();
}

// 檢查登入時間是否超過 1 小時
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 3600)) {
    session_unset();
    session_destroy();
    setcookie("admin_logged_in", "", time() - 3600, "/");
    header("Location: ad_login.php");
    exit();
}

// 連接到 MySQL 資料庫
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "diy_tutorials";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 查詢 `articles` 表格中的所有文章，並依照創建時間降序排序
$sql = "SELECT id, title, summary, content, is_published FROM articles ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>管理後台</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">管理後台</a>
        <a href="index.php" class="btn btn-primary ml-auto mr-2">首頁</a> <!-- 新增首頁連結 -->
        <a href="ad_logout.php" class="btn btn-danger">登出</a>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">文章管理</h2>

        <!-- 顯示成功訊息 -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success" role="alert">
                文章新增成功！
            </div>
        <?php endif; ?>

        <!-- 新增文章表單 -->
        <div class="card mb-4">
            <div class="card-header">新增文章</div>
            <div class="card-body">
                <form action="add_article.php" method="POST">
                    <div class="form-group">
                        <label for="title">標題</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="summary">摘要</label>
                        <textarea class="form-control" id="summary" name="summary" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="content">內容</label>
                        <textarea class="form-control" id="content" name="content" rows="10"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">新增文章</button>
                </form>
            </div>
        </div>

        <!-- 顯示文章列表 -->
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>標題</th>
                    <th>摘要</th>
                    <th>狀態</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['summary']); ?></td>
                        <td>
                            <?php if ($row['is_published']): ?>
                                <span class="badge badge-success">上架</span>
                                <a href="toggle_status.php?id=<?php echo $row['id']; ?>&status=0" class="btn btn-sm btn-warning">下架</a>
                            <?php else: ?>
                                <span class="badge badge-danger">未上架</span>
                                <a href="toggle_status.php?id=<?php echo $row['id']; ?>&status=1" class="btn btn-sm btn-success">上架</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="delete_article.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('確定要刪除這篇文章嗎？');">刪除</a>
                            <a href="article_view.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm" target="_blank">預覽</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
