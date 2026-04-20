<?php
session_start();
// Xóa toàn bộ dữ liệu session
session_unset();
session_destroy();

// Quay về trang chủ
header("Location: index.php");
exit();
?>