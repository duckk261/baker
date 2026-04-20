<?php
class StatsModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Đếm tổng số bánh đang có
    public function getTotalProducts() {
        $query = mysqli_query($this->db, "SELECT COUNT(*) as total FROM Products");
        return mysqli_fetch_assoc($query)['total'];
    }

    // Đếm tổng số đơn hàng đã đặt
    public function getTotalOrders() {
        $query = mysqli_query($this->db, "SELECT COUNT(*) as total FROM Orders");
        return mysqli_fetch_assoc($query)['total'];
    }

    // Đếm tổng số lượng khách hàng
    public function getTotalCustomers() {
        $query = mysqli_query($this->db, "SELECT COUNT(*) as total FROM Customers");
        return mysqli_fetch_assoc($query)['total'];
    }
}
?>