<?php
require_once '../app/models/AdminDashboardModel.php';

class AdminDashboardController {
    private $model;
    
    public function __construct($db) { 
        $this->model = new AdminDashboardModel($db); 
    }

    public function index() {
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

        // Nhờ Model đi lấy dữ liệu
        $stats = $this->model->getStats($start_date, $end_date);
        $recent_orders = $this->model->getRecentOrders($start_date, $end_date, 5);
        $best_selling_products = $this->model->getBestSellingProducts($start_date, $end_date, 5);
        $chart_data_from_db = $this->model->getChartRevenue($start_date, $end_date);
        // Ném sang cho View hiển thị
        require_once 'views/dashboard.php';
    }
}
?>