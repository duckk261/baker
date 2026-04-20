<?php 
// 1. XỬ LÝ LOGIN BẰNG PHP THUẦN
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Biến $db được tự động truyền từ file index.php tổng sang
    $username = mysqli_real_escape_string($db, trim($_POST['username']));
    $password = $_POST['password'];

    // Dùng đúng bảng Accounts và Customers tiếng Anh
    $sql = "SELECT a.*, c.full_name FROM Accounts a 
            JOIN Customers c ON a.customer_id = c.customer_id 
            WHERE a.username = '$username'";
    
    $result = mysqli_query($db, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // KIỂM TRA MẬT KHẨU ĐÃ ĐƯỢC MÃ HÓA (Đẳng cấp là ở đây)
        if (password_verify($password, $user['password'])) {
            $_SESSION['account_id'] = $user['customer_id'];
            $_SESSION['account_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role']; 
            $customer_id = $user['customer_id'];
            $_SESSION['cart'] = []; 
            $cart_query = mysqli_query($db, "SELECT product_id, quantity FROM Cart WHERE customer_id = '$customer_id'");
            
            while ($cart_row = mysqli_fetch_assoc($cart_query)) {
                $pid = $cart_row['product_id'];
                $qty = $cart_row['quantity'];
                for ($i = 0; $i < $qty; $i++) {
                    array_push($_SESSION['cart'], $pid);
                }
            }
            echo "<script>window.location.href='index.php?page=home';</script>";
            exit();
        } else {
            $error_msg = "Sai mật khẩu!";
        }
    } else {
        $error_msg = "Tài khoản không tồn tại!";
    }
}

include 'header.php'; 
?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 bg-light rounded p-5">

                <div id="login-box">
                    <h1 class="display-6 text-center mb-4">Login</h1>
                    <?php if($error_msg) echo "<div class='alert alert-danger'>$error_msg</div>"; ?>
                    
                    <form method="POST" action="index.php?page=login">
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="mb-4">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100 py-3">Login</button>
                        
                        <p class="text-center mt-3">
                            New member? <a href="javascript:void(0);" onclick="toggleForms()">Register here</a>
                        </p>
                    </form>
                </div>

                <div id="register-box" style="display: none;">
                    <h1 class="display-6 text-center mb-4">Register</h1>
                    
                    <div id="reg-msg" class="alert d-none"></div>

                    <form id="registerForm">
                        <div class="mb-3">
                            <input type="text" name="fullname" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="mb-4">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        
                        <button type="button" onclick="submitRegister()" class="btn btn-dark w-100 py-3">Create Account</button>
                        
                        <p class="text-center mt-3">
                            Already have an account? <a href="javascript:void(0);" onclick="toggleForms()">Login here</a>
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
// Hàm lật qua lật lại giữa Login và Register siêu mượt
function toggleForms() {
    let loginBox = document.getElementById('login-box');
    let regBox = document.getElementById('register-box');
    let regMsg = document.getElementById('reg-msg');

    if (loginBox.style.display === 'none') {
        loginBox.style.display = 'block';
        regBox.style.display = 'none';
    } else {
        loginBox.style.display = 'none';
        regBox.style.display = 'block';
        regMsg.classList.add('d-none'); // Ẩn thông báo cũ đi
    }
}

// Hàm gửi dữ liệu Đăng ký ngầm (AJAX)
function submitRegister() {
    let form = document.getElementById('registerForm');
    let formData = new FormData(form);
    let msgBox = document.getElementById('reg-msg');

    fetch('index.php?page=user&action=register', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Xóa các class màu sắc cũ
        msgBox.classList.remove('d-none', 'alert-danger', 'alert-success');

        if (data.status === 'success') {
            msgBox.classList.add('alert-success');
            msgBox.innerText = data.message;
            form.reset(); // Xóa trắng form nhập

            // Đợi 1.5 giây để người dùng đọc thông báo rồi tự động lật về Login
            setTimeout(() => {
                toggleForms();
            }, 1500);
        } else {
            msgBox.classList.add('alert-danger');
            msgBox.innerText = data.message;
        }
    })
    .catch(err => {
        console.error('Lỗi:', err);
    });
}
</script>

<?php include 'footer.php'; ?>