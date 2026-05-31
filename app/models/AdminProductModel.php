<?php
class AdminProductModel {
    private $db;
    
    // Nhận kết nối DB từ bên ngoài truyền vào
    public function __construct($db) { 
        $this->db = $db; 
    }
    
    public function getAllProducts() {
        return mysqli_query($this->db, "SELECT * FROM products ORDER BY product_id DESC");
    }
    
    public function deleteProduct($id) {
        $id = mysqli_real_escape_string($this->db, $id);
        mysqli_query($this->db, "DELETE FROM cart WHERE product_id = '$id'");
        mysqli_query($this->db, "DELETE FROM orderdetails WHERE product_id = '$id'");
        return mysqli_query($this->db, "DELETE FROM products WHERE product_id = '$id'");
    }
}
?>