<?php
// Bật session và nhúng kết nối Database
session_start();
require_once '../app/classes/Database.php'; // Đảm bảo đường dẫn này đúng với cấu trúc của ông
$db = Database::getInstance();

// 1. Đếm số thông báo CHƯA ĐỌC
$count_query = mysqli_query($db, "SELECT COUNT(id) as unread_count FROM notifications WHERE is_read = 0");
$count_res = mysqli_fetch_assoc($count_query);
$unread = $count_res['unread_count'] ?? 0;

// 2. Lấy 5 thông báo mới nhất (cả đã đọc và chưa đọc) để thả vào danh sách xổ xuống
$list_query = mysqli_query($db, "SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5");
$notifications = [];
if ($list_query) {
    while($row = mysqli_fetch_assoc($list_query)) {
        $notifications[] = $row;
    }
}

// 3. Đóng gói thành định dạng JSON và trả về cho AJAX
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'unread' => $unread,
    'data' => $notifications
]);
?>