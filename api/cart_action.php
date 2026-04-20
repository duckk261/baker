<?php
session_start();
include '../db_connect.php';

if (isset($_POST['action']) && isset($_POST['id'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];

    // 1. XỬ LÝ DỮ LIỆU (XÓA HOẶC SỬA SỐ LƯỢNG)
    if ($action == 'remove') {
        // Lọc bỏ ID khỏi mảng
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function($val) use ($id) {
            return $val != $id;
        });
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    } 
    elseif ($action == 'update') {
        $qty = (int)$_POST['qty'];
        // Xóa ID cũ đi
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function($val) use ($id) {
            return $val != $id;
        });
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        // Bơm lại ID với đúng số lượng mới
        for ($i = 0; $i < $qty; $i++) {
            $_SESSION['cart'][] = $id;
        }
    }

    // 2. TÍNH TOÁN LẠI TỔNG TIỀN
    $item_counts = isset($_SESSION['cart']) ? array_count_values($_SESSION['cart']) : [];
    $subtotal = 0;
    $row_total = 0;

    foreach ($item_counts as $pid => $quantity) {
        $sql = "SELECT Gia FROM San_Pham WHERE Ma_SP = '$pid'";
        $query = mysqli_query($conn, $sql);
        if ($item = mysqli_fetch_assoc($query)) {
            $tien_mon_nay = $item['Gia'] * $quantity;
            $subtotal += $tien_mon_nay;
            if ($pid == $id) {
                $row_total = $tien_mon_nay; // Lưu lại tiền của đúng cái dòng vừa đổi
            }
        }
    }

$tax_rate = 0.08;
$tax_amount = $subtotal * $tax_rate;
$shipping = 30000;
$final_total = ($subtotal > 0) ? ($subtotal + $tax_amount + $shipping) : 0;

echo json_encode([
    'status' => 'success',
    'row_total' => number_format($row_total, 0, ',', '.') . 'đ',
    'subtotal' => number_format($subtotal, 0, ',', '.') . 'đ',
    'tax_amount' => number_format($tax_amount, 0, ',', '.') . 'đ',
    'final_total' => number_format($final_total, 0, ',', '.') . 'đ',
    'cart_count' => count($_SESSION['cart']),
    'is_empty' => empty($_SESSION['cart'])
]);
    exit();
}
?>