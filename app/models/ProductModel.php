<?php
class ProductModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllProducts() {
        return mysqli_query($this->db, "SELECT * FROM Products");
    }
    public function getFeaturedProducts($limit = 3) {
        $query = "SELECT * FROM Products LIMIT $limit"; 
        return mysqli_query($this->db, $query);
    }

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

    public function searchProducts($searchTerm, $limit = null, $offset = null) {
        $searchTerm = mysqli_real_escape_string($this->db, $searchTerm);
        $query = "SELECT * FROM Products WHERE product_name LIKE '%$searchTerm%'";
        
        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT $limit OFFSET $offset";
        }
        
        return mysqli_query($this->db, $query);
    }
    public function getTotalSearchProducts($searchTerm) {
        $searchTerm = mysqli_real_escape_string($this->db, $searchTerm);
        $query = mysqli_query($this->db, "SELECT COUNT(*) as total FROM Products WHERE product_name LIKE '%$searchTerm%'");
        $row = mysqli_fetch_assoc($query);
        return $row['total'];
    }
}
?>