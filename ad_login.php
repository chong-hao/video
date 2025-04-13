<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // 確保只有在 Session 尚未啟動時才啟動
}

date_default_timezone_set('Asia/Taipei'); // 設置為台北時間

// 獲取當前的月/日/時
$currentTime = date('mdH'); // 格式為 月日時，例如 040312（4月3日12時）
$generatedCode = $currentTime . "123"; // 加上固定數字 123

// 檢查是否已登入
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // 延長 Session 有效期
    $_SESSION['login_time'] = time();
    setcookie("admin_logged_in", "true", time() + 3600, "/");
    header("Location: admin.php"); // 已登入直接跳轉
    exit();
}

// 檢查是否為 HTTP 請求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputCode = $_POST['authCode'] ?? ''; // 使用者輸入的授權碼

    // 驗證授權碼
    if ($inputCode === $generatedCode) {
        // 登入成功，設置 session 和 cookie
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['login_time'] = time(); // 記錄登入時間

        // 設置一個有效期為 1 小時的 Cookie
        setcookie("admin_logged_in", "true", time() + 3600, "/"); // 延長 Cookie 有效期

        header("Location: admin.php"); // 登入後跳轉到管理員頁面
        exit();
    } else {
        // 登入失敗，返回首頁並顯示錯誤
        header("Location: index.php?error=invalid_code");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>管理後台登入</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">管理後台登入</h2>
        <form method="POST" action="ad_login.php">
            <div class="form-group">
                <label for="authCode">授權碼</label>
                <input type="text" class="form-control" id="authCode" name="authCode" required>
            </div>
            <button type="submit" class="btn btn-primary">登入</button>
        </form>
    </div>
</body>
</html>