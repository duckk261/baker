<?php
session_start();
include '../db_connect.php';

$response = ['status' => 'error', 'message' => 'Update failed!'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['account_id'])) {
    $ma_kh = $_SESSION['account_id'];
    $ten_kh = mysqli_real_escape_string($conn, $_POST['ten_kh']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Cập nhật thông tin trong bảng khach_hang
    $sql = "UPDATE khach_hang SET 
            Ten_KH = '$ten_kh', 
            Email = '$email', 
            Dien_thoai = '$phone', 
            Dia_chi = '$address' 
            WHERE Ma_KH = '$ma_kh'";

    if (mysqli_query($conn, $sql)) {
        // Cập nhật lại tên trong Session để Header hiển thị đúng tên mới
        $_SESSION['account_name'] = $ten_kh; 
        $response = ['status' => 'success', 'message' => 'Profile updated successfully!'];
    } else {
        $response['message'] = "Database error: " . mysqli_error($conn);
    }
}
echo json_encode($response);
exit();