<?php
class StatsModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }
    public function getTotalProducts() {
        $query = mysqli_query($this->db, "SELECT COUNT(*) as total FROM Products");
        return mysqli_fetch_assoc($query)['total'];
    }

    public function getTotalOrders() {
        $query = mysqli_query($this->db, "SELECT COUNT(*) as total FROM Orders");
        return mysqli_fetch_assoc($query)['total'];
    }

    public function getTotalCustomers() {
        $query = mysqli_query($this->db, "SELECT COUNT(*) as total FROM Customers");
        return mysqli_fetch_assoc($query)['total'];
    }
}
?>