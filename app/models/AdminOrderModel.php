<?php
class AdminOrderModel {
    private $db;
    public function __construct($db) { $this->db = $db; }

    // 1. Lấy danh sách đơn hàng (có hỗ trợ lọc theo ngày)
    public function getAllOrders($filter_date = '') {
        $where = ($filter_date != '') ? "WHERE DATE(order_date) = '$filter_date'" : "";
        return mysqli_query($this->db, "SELECT * FROM orders $where ORDER BY order_id DESC");
    }

    // 2. Lấy thông tin chung của 1 đơn hàng + Thông tin khách
    public function getOrderById($id) {
        $id = mysqli_real_escape_string($this->db, $id);
        return mysqli_query($this->db, "SELECT o.*, c.full_name, c.phone_number, c.address, c.email FROM orders o LEFT JOIN customers c ON o.customer_id = c.customer_id WHERE o.order_id = '$id'");
    }

  // HÀM LẤY CHI TIẾT SẢN PHẨM TRONG ĐƠN HÀNG (KÈM GIÁ TIỀN)
    public function getOrderItems($id) {
        $id = mysqli_real_escape_string($this->db, $id);
        
        // Câu lệnh SQL đã được JOIN để lấy p.price chuẩn xác
        $sql = "SELECT od.*, p.product_name, p.price 
                FROM orderdetails od 
                JOIN products p ON od.product_id = p.product_id 
                WHERE od.order_id = '$id'";
                
        return mysqli_query($this->db, $sql);
    }

    // 4. Cập nhật trạng thái đơn
    public function updateStatus($id, $status) {
        $id = mysqli_real_escape_string($this->db, $id);
        $status = mysqli_real_escape_string($this->db, $status);
        return mysqli_query($this->db, "UPDATE orders SET status = '$status' WHERE order_id = '$id'");
    }
}
?>