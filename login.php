<?php 
include 'header.php'; 
include 'db_connect.php'; 

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Truy vấn dựa trên bảng tai_khoan và kết nối với khach_hang để lấy tên
    $sql = "SELECT tk.*, kh.Ten_KH FROM tai_khoan tk 
            JOIN khach_hang kh ON tk.Ma_KH = kh.Ma_KH 
            WHERE tk.Ten_dang_nhap = '$username' AND tk.Mat_khau = '$password'";
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['account_id'] = $user['Ma_KH'];
        $_SESSION['account_name'] = $user['Ten_KH'];
        $_SESSION['role'] = $user['Quyen']; // Lưu quyền User/Admin
        
        echo "<script>window.location.href='index.php';</script>";
        exit();
    } else {
        $error_msg = "Invalid username or password!";
    }
}
?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 bg-light rounded p-5">
                <h1 class="display-6 text-center mb-4">Login</h1>
                <?php if($error_msg) echo "<div class='alert alert-danger'>$error_msg</div>"; ?>
                
                <form method="POST">
                    <div class="mb-3"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
                    <div class="mb-4"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                    <button type="submit" name="login" class="btn btn-primary w-100 py-3">Login</button>
                    <p class="text-center mt-3">New member? <a href="register.php">Register here</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>