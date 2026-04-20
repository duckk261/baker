<?php
class UserController {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = mysqli_real_escape_string($this->db, trim($_POST['fullname']));
            $email = mysqli_real_escape_string($this->db, trim($_POST['email']));
            $phone = mysqli_real_escape_string($this->db, trim($_POST['phone']));
            $username = mysqli_real_escape_string($this->db, trim($_POST['username']));
            $password = $_POST['password'];
            $address = "";

            $check_user = mysqli_query($this->db, "SELECT * FROM Accounts WHERE username = '$username'");
            if (mysqli_num_rows($check_user) > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập đã tồn tại!']);
                exit();
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            mysqli_begin_transaction($this->db);

            try {
                // Đã cập nhật tên cột: full_name, phone_number
                $sql_customer = "INSERT INTO Customers (full_name, email, phone_number, address) 
                           VALUES ('$fullname', '$email', '$phone', '$address')";
                mysqli_query($this->db, $sql_customer);
                
                $customer_id = mysqli_insert_id($this->db);

                $sql_account = "INSERT INTO Accounts (username, password, role, customer_id) 
                           VALUES ('$username', '$hashed_password', 'User', '$customer_id')";
                mysqli_query($this->db, $sql_account);

                mysqli_commit($this->db);
                echo json_encode(['status' => 'success', 'message' => 'Đăng ký thành công! Bạn có thể đăng nhập.']);
            } catch (Exception $e) {
                mysqli_rollback($this->db);
                echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
            }
            exit();
        }
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['account_id'])) {
            $customer_id = $_SESSION['account_id'];
            
            $customer_name = mysqli_real_escape_string($this->db, $_POST['ten_kh']);
            $email = mysqli_real_escape_string($this->db, $_POST['email']);
            $phone = mysqli_real_escape_string($this->db, $_POST['phone']);
            $address = mysqli_real_escape_string($this->db, $_POST['address']);

            // Đã cập nhật tên cột
            $sql = "UPDATE Customers SET 
                    full_name = '$customer_name', 
                    email = '$email', 
                    phone_number = '$phone', 
                    address = '$address' 
                    WHERE customer_id = '$customer_id'";

            if (mysqli_query($this->db, $sql)) {
                $_SESSION['account_name'] = $customer_name; 
                echo json_encode(['status' => 'success', 'message' => 'Cập nhật hồ sơ thành công!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi cơ sở dữ liệu: ' . mysqli_error($this->db)]);
            }
            exit();
        }
    }
}
?>