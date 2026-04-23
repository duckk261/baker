<?php
class OrderModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function createOrder($customer_id, $subtotal, $shipping_fee, $total_amount, $status) {
        $sql = "INSERT INTO Orders (customer_id, subtotal, shipping_fee, total_amount, status)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->db, $sql);
        if (!$stmt) return false;

        mysqli_stmt_bind_param($stmt, "iddds", $customer_id, $subtotal, $shipping_fee, $total_amount, $status);
        $ok = mysqli_stmt_execute($stmt);
        if (!$ok) return false;

        return mysqli_insert_id($this->db);
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

    // Atomic stock decrement: only succeeds if enough stock.
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
