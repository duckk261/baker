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
}