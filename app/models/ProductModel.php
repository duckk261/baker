<?php
class ProductModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Lấy tất cả sản phẩm
    public function getAllProducts() {
        return mysqli_query($this->db, "SELECT * FROM Products");
    }
    public function getFeaturedProducts($limit = 3) {
        $query = "SELECT * FROM Products LIMIT $limit"; 
        return mysqli_query($this->db, $query);
    }

    // Lấy 1 sản phẩm theo ID
    public function getProductById($id) {
        $query = mysqli_query($this->db, "SELECT * FROM Products WHERE product_id = '$id'");
        return mysqli_fetch_assoc($query);
    }
    public function getTotalProducts() {
        $query = mysqli_query($this->db, "SELECT COUNT(*) as total FROM Products");
        $row = mysqli_fetch_assoc($query);
        return $row['total'];
    }

    public function getProductsPaginated($limit, $offset) {
        $query = "SELECT * FROM Products LIMIT $limit OFFSET $offset";
        return mysqli_query($this->db, $query);
    }
}
?>