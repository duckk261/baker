<?php
class AdminAccountController {
    private $db;
    public function __construct($db) { $this->db = $db; }

public function index() {
    // Đảm bảo dùng LEFT JOIN để lấy email từ bảng customers
    $sql = "SELECT a.*, c.email 
            FROM accounts a 
            LEFT JOIN customers c ON a.customer_id = c.customer_id 
            ORDER BY a.created_at DESC";
    
    $accounts_data = mysqli_query($this->db, $sql);
    require_once 'views/accounts.php';
}
public function add() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = mysqli_real_escape_string($this->db, $_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu
        $role = $_POST['role'];
        $sql = "INSERT INTO accounts (username, password, role) VALUES ('$username', '$password', '$role')";
        mysqli_query($this->db, $sql);
        header("Location: index.php?page=accounts");
    }
    require_once '../admin/views/add_account.php';
}

public function edit($id) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $role = $_POST['role'];
        $sql = "UPDATE accounts SET role = '$role' WHERE customer_id = '$id'";
        mysqli_query($this->db, $sql);
        header("Location: index.php?page=accounts");
    }
    $account = mysqli_fetch_assoc(mysqli_query($this->db, "SELECT * FROM accounts WHERE customer_id = '$id'"));
    require_once '../admin/views/edit_account.php';
}
}