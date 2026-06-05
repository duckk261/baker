<?php
class UserController {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

   public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username  = mysqli_real_escape_string($this->db, trim($_POST['username']));
            $fullname  = mysqli_real_escape_string($this->db, trim($_POST['full_name']));
            // CHÚ Ý CHỖ NÀY: Phải hứng đúng 'email' và 'phone' từ form gửi lên
            $email     = mysqli_real_escape_string($this->db, trim($_POST['email']));
            $phone     = mysqli_real_escape_string($this->db, trim($_POST['phone']));
            $password  = mysqli_real_escape_string($this->db, trim($_POST['password']));
            $address   = mysqli_real_escape_string($this->db, trim($_POST['address']));

            $check_user = mysqli_query($this->db, "SELECT username FROM accounts WHERE username = '$username'");
            $check_email = mysqli_query($this->db, "SELECT email FROM customers WHERE email = '$email'");

            if (mysqli_num_rows($check_user) > 0) {
                echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Error: Username already exists!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                exit();
            }
            if (mysqli_num_rows($check_email) > 0) {
                echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Error: Email address already registered!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                exit();
            }

            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Đưa đầy đủ $phone và $email vào câu lệnh SQL
            $customer_sql = "INSERT INTO customers (full_name, phone_number, address, email) 
                             VALUES ('$fullname', '$phone', '$address', '$email')";
            
            if (mysqli_query($this->db, $customer_sql)) {
                $customer_id = mysqli_insert_id($this->db);

                $account_sql = "INSERT INTO accounts (username, password, customer_id) 
                                VALUES ('$username', '$hashed_password', '$customer_id')";
                
                if (mysqli_query($this->db, $account_sql)) {
                    echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Registration successful! Please login to your account.\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=login'; });});</script>";
                    exit();
                } else {
                    echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Error: Could not create login account.\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                    exit();
                }
            } else {
                echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Error: Could not save customer profile.\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                exit();
            }
        }
    }
public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Ép trình duyệt hiểu đây là dữ liệu JSON
            header('Content-Type: application/json');

            if (!isset($_SESSION['account_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'Please login first!']);
                exit();
            }

            $customer_id = $_SESSION['account_id'];
            
            // 2. Dùng isset() để bắt dữ liệu an toàn 100%
            $fullname = isset($_POST['ten_kh']) ? mysqli_real_escape_string($this->db, trim($_POST['ten_kh'])) : '';
            $email    = isset($_POST['email']) ? mysqli_real_escape_string($this->db, trim($_POST['email'])) : '';
            $phone    = isset($_POST['phone']) ? mysqli_real_escape_string($this->db, trim($_POST['phone'])) : '';
            $address  = isset($_POST['address']) ? mysqli_real_escape_string($this->db, trim($_POST['address'])) : '';

            $sql_customer = "UPDATE customers 
                             SET full_name = '$fullname', email = '$email', phone_number = '$phone', address = '$address' 
                             WHERE customer_id = '$customer_id'";
            $update_info = mysqli_query($this->db, $sql_customer);

            $pass_msg = ""; 

            // 3. Xử lý đổi mật khẩu
            $current_password = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
            $new_password     = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
            $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

            if ($current_password != '' || $new_password != '') {
                if ($new_password !== $confirm_password) {
                    echo json_encode(['status' => 'error', 'message' => 'Error: New passwords do not match!']);
                    exit();
                }

                $acc_query = mysqli_query($this->db, "SELECT password FROM accounts WHERE customer_id = '$customer_id'");
                $acc_row = mysqli_fetch_assoc($acc_query);

                if (password_verify($current_password, $acc_row['password'])) {
                    $hashed_new = password_hash($new_password, PASSWORD_BCRYPT);
                    mysqli_query($this->db, "UPDATE accounts SET password = '$hashed_new' WHERE customer_id = '$customer_id'");
                    $pass_msg = " and password"; 
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error: Current password is incorrect!']);
                    exit();
                }
            }

            // 4. Trả về thông báo thành công
            if ($update_info) {
                $_SESSION['account_name'] = $fullname; 
                echo json_encode(['status' => 'success', 'message' => 'Profile' . $pass_msg . ' updated successfully!']);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating profile database!']);
                exit();
            }
        }
    }
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = mysqli_real_escape_string($this->db, trim($_POST['email']));
            $sql = "SELECT a.username, c.full_name FROM Customers c
                    JOIN Accounts a ON c.customer_id = a.customer_id
                    WHERE c.email = '$email'";
            $result = mysqli_query($this->db, $sql);

            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                $username = $user['username'];
                $fullname = $user['full_name'];

                // 2. Generate a random 6-digit password
                $new_password = rand(100000, 999999);
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                // 3. Update password in Database
                mysqli_query($this->db, "UPDATE Accounts SET password = '$hashed_password' WHERE username = '$username'");

                // 4. Send Email sử dụng chính biến $email (tương ứng với gmail của khách)
                require_once 'app/classes/Mailer.php';
                $mailer = new Mailer();
                $subject = "Baker Store - Password Reset Request";
                $body = "<h3>Password Reset Successful!</h3>
                         <p>Hello $fullname,</p>
                         <p>Your password has been successfully reset. Your new login password is: <b style='color:red; font-size:20px;'>$new_password</b></p>
                         <p>Please log in and change your password immediately to secure your account.</p>
                         <hr><p>Baker Store Support Team</p>";

                $mailer->send($email, $fullname, $subject, $body);

                echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"A new password has been sent to your email. Please check your inbox!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=login'; });});</script>";
                exit();
            } else {
                echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Error: This email address is not registered in our system!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                exit();
            }
        }
    }
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = mysqli_real_escape_string($this->db, trim($_POST['username']));
            $password = trim($_POST['password']);

            // 1. Tìm tài khoản trong Database
            $sql = "SELECT a.*, c.full_name FROM accounts a 
                    JOIN customers c ON a.customer_id = c.customer_id 
                    WHERE a.username = '$username'";
            $result = mysqli_query($this->db, $sql);

            if ($row = mysqli_fetch_assoc($result)) {
                // 2. Đối chiếu mật khẩu
                if (password_verify($password, $row['password'])) {
                    
                    // 3. Đăng nhập thành công -> Lưu Session
                    $_SESSION['account_id'] = $row['customer_id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['account_name'] = $row['full_name'];
                    $_SESSION['role'] = $row['role'];
                    
                    // 4. Phục hồi giỏ hàng cũ từ DB vào Session
                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = [];
                    }
                    $customer_id = $row['customer_id'];
                    $cart_query = mysqli_query($this->db, "SELECT product_id, quantity FROM Cart WHERE customer_id = '$customer_id'");
                    
                    while ($cart_row = mysqli_fetch_assoc($cart_query)) {
                        $pid = $cart_row['product_id'];
                        $qty = $cart_row['quantity'];
                        for ($i = 0; $i < $qty; $i++) {
                            array_push($_SESSION['cart'], $pid);
                        }
                    }

                    // 5. Đá về trang chủ
                    echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Login successful! Welcome back.\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=home'; });});</script>";
                    exit();
                } else {
                    echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Error: Incorrect password!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                    exit();
                }
            } else {
                echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Error: Username does not exist!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.history.back(); });});</script>";
                exit();
            }
        }
    }
}
?>