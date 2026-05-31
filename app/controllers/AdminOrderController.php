<?php
require_once '../app/models/AdminOrderModel.php';

class AdminOrderController {
    private $model;

    public function __construct($db) {
        $this->model = new AdminOrderModel($db);
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
            echo "<script>alert('Duyệt thành công! Đơn hàng đang được vận chuyển.'); window.location.href='index.php?page=orders';</script>";
        }
    }

    // Hoàn tất đơn
    public function complete() {
        if (isset($_GET['id'])) {
            $this->model->updateStatus($_GET['id'], 'Hoan_tat');
            echo "<script>alert('Đơn hàng đã giao thành công và hoàn tất!'); window.location.href='index.php?page=orders';</script>";
        }
    }
}
?>