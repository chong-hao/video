<?php
session_start();
session_unset();
session_destroy();
setcookie("admin_logged_in", "", time() - 3600, "/"); // 清除 Cookie
header("Location: index.php");
exit();
?>