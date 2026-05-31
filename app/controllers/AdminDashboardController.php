<?php
require_once '../app/models/AdminDashboardModel.php';

class AdminDashboardController {
    private $model;
    
    public function __construct($db) { 
        $this->model = new AdminDashboardModel($db); 
    }

    public function index() {
        // Nhờ Model đi lấy dữ liệu
        $stats = $this->model->getStats();
        $recent_orders = $this->model->getRecentOrders(5);
        $monthly_revenue = $this->model->getMonthlyRevenue();
        // Ném sang cho View hiển thị
        require_once 'views/dashboard.php';
    }
    public function getMonthlyRevenue() {
        $year = date('Y'); // Năm hiện tại
        // Lấy tổng tiền của các đơn đã hoàn tất, gom nhóm theo tháng
        $query = "SELECT MONTH(order_date) as month, SUM(total_amount) as revenue 
                  FROM orders 
                  WHERE status IN ('Hoan_tat', 'Completed', 'Đã giao', 'Hoàn thành') 
                  AND YEAR(order_date) = '$year'
                  GROUP BY MONTH(order_date)";
                  
        $result = mysqli_query($this->db, $query);
        
        // Tạo sẵn mảng 12 tháng, mặc định doanh thu là 0đ
        $monthly_data = array_fill(1, 12, 0); 
        
        // Đổ dữ liệu thật từ Database vào mảng
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $monthly_data[(int)$row['month']] = (int)$row['revenue'];
            }
        }
        
        return $monthly_data;
    }
}
?>