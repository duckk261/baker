<?php
class AdminUserModel {
    private $db;
    public function __construct($db) { $this->db = $db; }

    // Lấy danh sách nhân viên & admin
    public function getStaff() {
        return mysqli_query($this->db, "SELECT * FROM accounts WHERE role IN ('admin', 'staff', 'nhan_vien', 'nhanvien')");
    }

    // Lấy danh sách khách hàng
    public function getCustomers() {
        return mysqli_query($this->db, "SELECT * FROM customers");
    }
}
?>