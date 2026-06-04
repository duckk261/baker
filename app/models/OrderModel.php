<?php
class OrderModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

   // Bổ sung thêm biến $payment_method vào tham số của hàm
    public function createOrder($customer_id, $subtotal, $shipping_fee, $total_amount, $status, $payment_method = 'COD') {
        
        // Nhớ chống SQL Injection cho cẩn thận
        $payment_method = mysqli_real_escape_string($this->db, $payment_method);
        
        // Bổ sung cột payment_method vào lệnh INSERT
        $sql = "INSERT INTO orders (customer_id, subtotal, shipping_fee, total_amount, status, payment_method, order_date) 
                VALUES ('$customer_id', '$subtotal', '$shipping_fee', '$total_amount', '$status', '$payment_method', NOW())";
                
        if (mysqli_query($this->db, $sql)) {
            return mysqli_insert_id($this->db);
        }
        return false;
    }

    public function addOrderDetail($order_id, $product_id, $quantity, $unit_price) {
        $sql = "INSERT INTO OrderDetails (order_id, product_id, quantity, unit_price)
                VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->db, $sql);
        if (!$stmt) return false;

        mysqli_stmt_bind_param($stmt, "iiid", $order_id, $product_id, $quantity, $unit_price);
        return mysqli_stmt_execute($stmt);
    }

    public function createPayment($order_id, $payment_method, $amount, $note) {
        $sql = "INSERT INTO Payments (order_id, payment_method, amount, note)
                VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->db, $sql);
        if (!$stmt) return false;

        mysqli_stmt_bind_param($stmt, "isds", $order_id, $payment_method, $amount, $note);
        return mysqli_stmt_execute($stmt);
    }
    public function decrementStockIfAvailable($product_id, $quantity) {
        $sql = "UPDATE Products
                SET stock_quantity = stock_quantity - ?
                WHERE product_id = ? AND stock_quantity >= ?";
        $stmt = mysqli_prepare($this->db, $sql);
        if (!$stmt) return false;

        mysqli_stmt_bind_param($stmt, "iii", $quantity, $product_id, $quantity);
        $ok = mysqli_stmt_execute($stmt);
        if (!$ok) return false;

        return mysqli_stmt_affected_rows($stmt) === 1;
    }

    public function clearCartForCustomer($customer_id) {
        $sql = "DELETE FROM Cart WHERE customer_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        if (!$stmt) return false;

        mysqli_stmt_bind_param($stmt, "i", $customer_id);
        return mysqli_stmt_execute($stmt);
    }
}
?>
