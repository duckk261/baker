<?php
require_once '../app/models/AdminOrderModel.php';

class AdminOrderController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new AdminOrderModel($db);
        $this->db = $db; // Nạp đường kết nối Database

        // HACK ROUTER: Đón lõng lệnh hủy đơn ngay lập tức để chống trắng trang
        if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['id'])) {
            $this->cancel();
        }
    }

    // Hiển thị danh sách đơn
    public function index() {
        $filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
        $orders = $this->model->getAllOrders($filter_date);
        require_once 'views/order_list.php';
    }

    // Hiển thị chi tiết 1 đơn
    public function detail() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        $order_info_query = $this->model->getOrderById($id);
        $order_info = mysqli_fetch_assoc($order_info_query);
        
        $order_items = $this->model->getOrderItems($id);

        require_once 'views/order_detail.php';
    }

    // Duyệt đơn
    public function approve() {
        if (isset($_GET['id'])) {
            $this->model->updateStatus($_GET['id'], 'Dang_giao');
            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Duyệt thành công! Đơn hàng đang được vận chuyển.\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=orders'; });});</script>";
        }
    }

    // Hoàn tất đơn
    public function complete() {
        if (isset($_GET['id'])) {
            $this->model->updateStatus($_GET['id'], 'Hoan_tat');
            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Đơn hàng đã giao thành công và hoàn tất!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=orders'; });});</script>";
        }
    }

    // ==========================================================
    // HÀM XỬ LÝ HỦY ĐƠN HÀNG DÀNH CHO ADMIN
    // ==========================================================
    public function cancel() {
        $cancel_id = (int)$_GET['id'];
        
        try {
            // 1. Lấy chi tiết đơn hàng để biết số lượng bánh cần hoàn lại
            $details_sql = "SELECT product_id, quantity FROM orderdetails WHERE order_id = '$cancel_id'";
            $details_res = mysqli_query($this->db, $details_sql);

            if ($details_res) {
                // 2. Vòng lặp: Trả lại số lượng bánh vào kho
                while ($item = mysqli_fetch_assoc($details_res)) {
                    $pid = $item['product_id'];
                    $qty = $item['quantity'];
                    mysqli_query($this->db, "UPDATE products SET stock_quantity = stock_quantity + $qty WHERE product_id = '$pid'");
                }
            }

            // 3. Chuyển trạng thái đơn hàng thành Đã hủy
            mysqli_query($this->db, "UPDATE orders SET status = 'Da_huy' WHERE order_id = '$cancel_id'");

            // Bắn thông báo và chuyển hướng ngay, không cho trang load tiếp
            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Admin: Đã hủy đơn và hoàn bánh vào kho thành công!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=orders'; });});</script>";
            exit(); 
        } catch (Exception $e) {
            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Lỗi hệ thống khi hủy đơn!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=orders'; });});</script>";
            exit();
        }
    }
}
?>