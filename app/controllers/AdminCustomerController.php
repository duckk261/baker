<?php
class AdminCustomerController {
    private $db;
    public function __construct($db) { $this->db = $db; }

  public function index() {
    // Lấy danh sách khách hàng từ bảng customers
    $sql = "SELECT * FROM customers ORDER BY customer_id DESC";
    $customers = mysqli_query($this->db, $sql); // Đặt tên biến là $customers để khớp với view
    
    // Nạp view
    require_once '../admin/views/customer_list.php';
}
}