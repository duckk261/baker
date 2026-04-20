<?php
class CartController {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function addToCart() {
        if (!isset($_SESSION['account_id'])) {
            echo json_encode(['status' => 'not_logged_in', 'message' => 'Bạn cần đăng nhập để mua hàng!']);
            exit();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            if (!isset($_SESSION['cart'])) { 
                $_SESSION['cart'] = []; 
            }
            
            array_push($_SESSION['cart'], $id);
            $this->syncCartToDB();
            echo json_encode([
                'status' => 'success', 
                'cart_count' => count($_SESSION['cart'])
            ]);
            exit();
        }
    }
    private function syncCartToDB() {
        if (!isset($_SESSION['account_id'])) return;
        $customer_id = $_SESSION['account_id'];
        mysqli_query($this->db, "DELETE FROM Cart WHERE customer_id = '$customer_id'");
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $item_counts = array_count_values($_SESSION['cart']);
            foreach ($item_counts as $product_id => $quantity) {
                $sql = "INSERT INTO Cart (customer_id, product_id, quantity) 
                        VALUES ('$customer_id', '$product_id', '$quantity')";
                mysqli_query($this->db, $sql);
            }
        }
    }
    public function getCartDetails() {
        $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $item_counts = array_count_values($cart_items);
        $cart_details = [];
        $subtotal = 0;

        foreach ($item_counts as $pid => $qty) {
            $sql = "SELECT * FROM Products WHERE product_id = '$pid'";
            $query = mysqli_query($this->db, $sql);
            
            if ($item = mysqli_fetch_assoc($query)) {
                $item['quantity'] = $qty;
                $item['item_total'] = $item['price'] * $qty;
                $subtotal += $item['item_total'];
                $cart_details[] = $item;
            }
        }

        $tax_amount = $subtotal * 0.08;
        $shipping = empty($cart_details) ? 0 : 30000;
        $final_total = $subtotal > 0 ? ($subtotal + $tax_amount + $shipping) : 0;
        return [
            'details' => $cart_details,
            'subtotal' => $subtotal,
            'tax_amount' => $tax_amount,
            'shipping' => $shipping,
            'final_total' => $final_total
        ];
    }
    public function updateCart() {
        if (!isset($_SESSION['account_id']) || !isset($_SESSION['cart'])) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi phiên đăng nhập']);
            exit();
        }

        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $action = isset($_POST['action']) ? $_POST['action'] : '';

        if ($action == 'remove') {
            $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($id) {
                return $item != $id;
            });
        } 
        elseif ($action == 'update' && isset($_POST['quantity'])) {
            $quantity = (int)$_POST['quantity'];
            
            $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($id) {
                return $item != $id;
            });
            
            for ($i = 0; $i < $quantity; $i++) {
                array_push($_SESSION['cart'], $id);
            }
        }

        $item_counts = array_count_values($_SESSION['cart']);
        $subtotal = 0;
        $row_total = 0;
        $this->syncCartToDB();
        $item_counts = array_count_values($_SESSION['cart']);
        foreach ($item_counts as $pid => $qty) {
            $sql = "SELECT price FROM products WHERE product_id = '$pid'";
            $query = mysqli_query($this->db, $sql);
            if ($item = mysqli_fetch_assoc($query)) {
                $item_total = $item['price'] * $qty;
                $subtotal += $item_total;
                if ($pid == $id) {
                    $row_total = $item_total;
                }
            }
        }

        $tax_rate = 0.08;
        $tax_amount = $subtotal * $tax_rate;
        $shipping = 30000;
        $final_total = ($subtotal > 0) ? ($subtotal + $tax_amount + $shipping) : 0;

        echo json_encode([
            'status' => 'success',
            'row_total' => number_format($row_total, 0, ',', '.') . 'đ',
            'subtotal' => number_format($subtotal, 0, ',', '.') . 'đ',
            'tax_amount' => number_format($tax_amount, 0, ',', '.') . 'đ',
            'final_total' => number_format($final_total, 0, ',', '.') . 'đ',
            'cart_count' => count($_SESSION['cart']),
            'is_empty' => empty($_SESSION['cart'])
        ]);
        exit();
    }
}
?>