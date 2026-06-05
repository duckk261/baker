<?php
class AdminProductModel {
    private $db;
    
    // Nhận kết nối DB từ bên ngoài truyền vào
    public function __construct($db) { 
        $this->db = $db; 
    }
    
   public function getAllProducts($search = '') {
        $sql = "SELECT * FROM products";    
        if (!empty($search)) {
            $search = mysqli_real_escape_string($this->db, $search);
            $sql .= " WHERE product_id = '$search' OR product_name LIKE '%$search%'";
        }
    
        $sql .= " ORDER BY product_id DESC";
        
        return mysqli_query($this->db, $sql);
    }
    
    public function deleteProduct($id) {
        $id = mysqli_real_escape_string($this->db, $id);
        mysqli_query($this->db, "DELETE FROM cart WHERE product_id = '$id'");
        mysqli_query($this->db, "DELETE FROM orderdetails WHERE product_id = '$id'");
        return mysqli_query($this->db, "DELETE FROM products WHERE product_id = '$id'");
    }
}
?>