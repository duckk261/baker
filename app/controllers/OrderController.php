<?php
require_once 'app/models/OrderModel.php';
require_once 'app/models/ProductModel.php';
require_once 'app/classes/Mailer.php';

class OrderController {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function processOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=checkout");
            exit();
        }

        if (!isset($_SESSION['account_id'])) {
            header("Location: index.php?page=login");
            exit();
        }

        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            echo "<script>alert('Giỏ hàng trống!'); window.location.href='index.php?page=product';</script>";
            exit();
        }

        $customer_id = (int)$_SESSION['account_id'];

        $fullname = mysqli_real_escape_string($this->db, trim($_POST['fullname'] ?? ''));
        $email = mysqli_real_escape_string($this->db, trim($_POST['email'] ?? ''));
        $phone = mysqli_real_escape_string($this->db, trim($_POST['phone'] ?? ''));
        $address = mysqli_real_escape_string($this->db, trim($_POST['address'] ?? ''));
        $notes = mysqli_real_escape_string($this->db, trim($_POST['notes'] ?? ''));
        $payment = trim($_POST['payment'] ?? 'COD');

        if ($fullname === '' || $phone === '' || $address === '') {
            echo "<script>alert('Vui lòng nhập đầy đủ họ tên, số điện thoại và địa chỉ!'); window.location.href='index.php?page=checkout';</script>";
            exit();
        }

        $item_counts = array_count_values($_SESSION['cart']); // product_id => quantity
        $productModel = new ProductModel($this->db);
        $orderModel = new OrderModel($this->db);

        // Recalculate totals from DB prices to avoid tampering
        $subtotal = 0.0;
        $cart_lines = [];
        foreach ($item_counts as $pid_raw => $qty_raw) {
            $pid = (int)$pid_raw;
            $qty = (int)$qty_raw;
            if ($pid <= 0 || $qty <= 0) continue;

            $p = $productModel->getProductById($pid);
            if (!$p) {
                echo "<script>alert('Có sản phẩm không tồn tại trong giỏ hàng. Vui lòng thử lại!'); window.location.href='index.php?page=cart';</script>";
                exit();
            }

            $unit_price = (float)$p['price'];
            $line_total = $unit_price * $qty;
            $subtotal += $line_total;

            $cart_lines[] = [
                'product_id' => $pid,
                'quantity' => $qty,
                'unit_price' => $unit_price,
                'product_name' => $p['product_name'],
                'stock_quantity' => (int)$p['stock_quantity'],
            ];
        }

        if ($subtotal <= 0 || empty($cart_lines)) {
            echo "<script>alert('Giỏ hàng không hợp lệ!'); window.location.href='index.php?page=cart';</script>";
            exit();
        }

        $tax_amount = $subtotal * 0.08;
        $shipping_fee = 30000.0;
        $total_amount = $subtotal + $tax_amount + $shipping_fee;

        $pay_now = ($payment === 'Bank Transfer');
        $status = $pay_now ? 'Da_thanh_toan' : 'Cho_duyet';

        mysqli_begin_transaction($this->db);
        try {
            // If paying now, validate stock and decrement atomically
            if ($pay_now) {
                foreach ($cart_lines as $line) {
                    $ok = $orderModel->decrementStockIfAvailable($line['product_id'], $line['quantity']);
                    if (!$ok) {
                        throw new Exception("Sản phẩm '{$line['product_name']}' không đủ tồn kho.");
                    }
                }
            }

            $order_id = $orderModel->createOrder($customer_id, $subtotal, $shipping_fee, $total_amount, $status);
            if (!$order_id) {
                throw new Exception("Không thể tạo đơn hàng.");
            }

            foreach ($cart_lines as $line) {
                $ok = $orderModel->addOrderDetail($order_id, $line['product_id'], $line['quantity'], $line['unit_price']);
                if (!$ok) {
                    throw new Exception("Không thể tạo chi tiết đơn hàng.");
                }
            }

            // Payment record when pay-now, or keep note for COD (optional)
            if ($pay_now) {
                $payment_note = "Bank transfer - {$fullname} - {$phone}. {$notes}";
                $ok = $orderModel->createPayment($order_id, $payment, $total_amount, $payment_note);
                if (!$ok) {
                    throw new Exception("Không thể lưu thông tin thanh toán.");
                }
            }

            // Clear cart (session + DB)
            $orderModel->clearCartForCustomer($customer_id);
            $_SESSION['cart'] = [];

            mysqli_commit($this->db);

            // Best-effort email notification (do not fail the order if email sending fails)
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mailer = new Mailer();

                $subject = "Baker - Xác nhận đơn hàng #" . (int)$order_id;
                $body = "
                    <h2>Cảm ơn bạn đã đặt hàng!</h2>
                    <p>Xin chào <b>{$fullname}</b>,</p>
                    <p>Đơn hàng <b>#{$order_id}</b> đã được tạo thành công.</p>
                    <p><b>Hình thức thanh toán:</b> {$payment}</p>
                    <p><b>Tổng tiền:</b> " . number_format($total_amount, 0, ',', '.') . "đ</p>
                    <p><b>Địa chỉ giao hàng:</b> {$address}</p>
                    <hr />
                    <p>Baker Store</p>
                ";
                $mailer->send($email, $fullname, $subject, $body);
            }

            header("Location: index.php?page=order_success&order_id=" . (int)$order_id);
            exit();
        } catch (Exception $e) {
            mysqli_rollback($this->db);
            $msg = addslashes($e->getMessage());
            echo "<script>alert('Thanh toán thất bại: {$msg}'); window.location.href='index.php?page=checkout';</script>";
            exit();
        }
    }
}
?>
