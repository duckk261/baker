<?php
session_start();
include '../db_connect.php';
if (!isset($_SESSION['account_id'])) {
    echo json_encode(['status' => 'not_logged_in', 'message' => 'Please login to add items!']);
    exit();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    array_push($_SESSION['cart'], $id);
    
    $total_items = count($_SESSION['cart']);
    
    echo json_encode(['status' => 'success', 'cart_count' => $total_items]);
    exit();
}

echo json_encode(['status' => 'error']);
exit();
?>