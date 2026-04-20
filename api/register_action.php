<?php
include '../db_connect.php';

$response = ['status' => 'error', 'message' => 'Something went wrong!'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_kh = mysqli_real_escape_string($conn, $_POST['ten_kh']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // 1. Check if username exists in tai_khoan
    $check = mysqli_query($conn, "SELECT * FROM tai_khoan WHERE Ten_dang_nhap = '$username'");
    if (mysqli_num_rows($check) > 0) {
        $response['message'] = "Username already exists!";
    } else {
        // 2. Insert into khach_hang
        $sql_kh = "INSERT INTO khach_hang (Ten_KH, Dia_chi, Dien_thoai, Email) VALUES ('$ten_kh', '$address', '$phone', '$email')";
        if (mysqli_query($conn, $sql_kh)) {
            $ma_kh = mysqli_insert_id($conn); 
            // 3. Insert into tai_khoan
            $sql_tk = "INSERT INTO tai_khoan (Ten_dang_nhap, Mat_khau, Ma_KH, Quyen) VALUES ('$username', '$password', '$ma_kh', 'User')";
            if (mysqli_query($conn, $sql_tk)) {
                $response = ['status' => 'success', 'message' => 'Registration successful!'];
            }
        }
    }
}
echo json_encode($response);
exit();