<?php
class AdminDashboardModel {
    private $db;
    public function __construct($db) { $this->db = $db; }

    // 1. Gom tất cả số liệu thống kê vào 1 hàm
    public function getStats() {
        $stats = [];
        $stats['customers'] = mysqli_fetch_assoc(mysqli_query($this->db, "SELECT COUNT(*) as count FROM customers"))['count'] ?? 0;
        $stats['products'] = mysqli_fetch_assoc(mysqli_query($this->db, "SELECT COUNT(*) as count FROM products"))['count'] ?? 0;
        $stats['orders'] = mysqli_fetch_assoc(mysqli_query($this->db, "SELECT COUNT(*) as count FROM orders"))['count'] ?? 0;
        
        $rev_query = mysqli_query($this->db, "SELECT SUM(total_amount) as total FROM orders WHERE status IN ('Completed', 'Đã giao', 'Hoàn thành', 'Hoan_tat')");
        $stats['revenue'] = mysqli_fetch_assoc($rev_query)['total'] ?? 0;
        
        return $stats;
    }

    // 2. Lấy 5 đơn hàng mới nhất
    public function getRecentOrders($limit = 5) {
        return mysqli_query($this->db, "SELECT * FROM orders ORDER BY order_id DESC LIMIT $limit");
    }

    // 3. Lấy doanh thu 12 tháng của năm hiện tại cho biểu đồ
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