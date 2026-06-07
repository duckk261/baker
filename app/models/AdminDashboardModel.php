<?php
class AdminDashboardModel {
    private $db;
    public function __construct($db) { $this->db = $db; }

    // 1. Gom tất cả số liệu thống kê vào 1 hàm
    public function getStats($start_date = '', $end_date = '') {
        $stats = [];
        $order_filter = "";
        $rev_filter = "";
        
        if (!empty($start_date) && !empty($end_date)) {
            $start = mysqli_real_escape_string($this->db, $start_date) . " 00:00:00";
            $end = mysqli_real_escape_string($this->db, $end_date) . " 23:59:59";
            $order_filter = " WHERE order_date >= '$start' AND order_date <= '$end'";
            $rev_filter = " AND order_date >= '$start' AND order_date <= '$end'";
        }

        $stats['customers'] = mysqli_fetch_assoc(mysqli_query($this->db, "SELECT COUNT(*) as count FROM customers"))['count'] ?? 0;
        $stats['products'] = mysqli_fetch_assoc(mysqli_query($this->db, "SELECT COUNT(*) as count FROM products"))['count'] ?? 0;
        $stats['orders'] = mysqli_fetch_assoc(mysqli_query($this->db, "SELECT COUNT(*) as count FROM orders" . $order_filter))['count'] ?? 0;
        
        $rev_query = mysqli_query($this->db, "SELECT SUM(total_amount) as total FROM orders WHERE status IN ('Completed', 'Đã giao', 'Hoàn thành', 'Hoan_tat')" . $rev_filter);
        $stats['revenue'] = mysqli_fetch_assoc($rev_query)['total'] ?? 0;
        
        return $stats;
    }

    // 2. Lấy 5 đơn hàng mới nhất
    public function getRecentOrders($start_date = '', $end_date = '', $limit = 5) {
        $date_filter = "";
        if (!empty($start_date) && !empty($end_date)) {
            $start = mysqli_real_escape_string($this->db, $start_date) . " 00:00:00";
            $end = mysqli_real_escape_string($this->db, $end_date) . " 23:59:59";
            $date_filter = " WHERE order_date >= '$start' AND order_date <= '$end'";
        }
        return mysqli_query($this->db, "SELECT * FROM orders $date_filter ORDER BY order_id DESC LIMIT $limit");
    }

    // Lấy top 5 sản phẩm bán chạy nhất
    public function getBestSellingProducts($start_date = '', $end_date = '', $limit = 5) {
        $date_filter = "";
        if (!empty($start_date) && !empty($end_date)) {
            $start = mysqli_real_escape_string($this->db, $start_date) . " 00:00:00";
            $end = mysqli_real_escape_string($this->db, $end_date) . " 23:59:59";
            $date_filter = " AND o.order_date >= '$start' AND o.order_date <= '$end'";
        }

        $query = "SELECT p.product_id, p.product_name, p.image, p.price, SUM(od.quantity) as total_sold 
                  FROM orderdetails od 
                  JOIN orders o ON od.order_id = o.order_id 
                  JOIN products p ON od.product_id = p.product_id 
                  WHERE o.status IN ('Hoan_tat', 'Completed', 'Đã giao', 'Hoàn thành') $date_filter 
                  GROUP BY p.product_id 
                  ORDER BY total_sold DESC 
                  LIMIT $limit";
                  
        return mysqli_query($this->db, $query);
    }

    // 3. Lấy dữ liệu biểu đồ (Tháng nếu không lọc, Ngày nếu có lọc)
    public function getChartRevenue($start_date = '', $end_date = '') {
        $labels = [];
        $data = [];
        
        if (!empty($start_date) && !empty($end_date)) {
            // Lọc theo ngày -> Gom nhóm theo Từng Ngày
            $start = mysqli_real_escape_string($this->db, $start_date) . " 00:00:00";
            $end = mysqli_real_escape_string($this->db, $end_date) . " 23:59:59";
            
            $query = "SELECT DATE(order_date) as date_val, SUM(total_amount) as revenue 
                      FROM orders 
                      WHERE status IN ('Hoan_tat', 'Completed', 'Đã giao', 'Hoàn thành') 
                      AND order_date >= '$start' AND order_date <= '$end'
                      GROUP BY DATE(order_date)
                      ORDER BY DATE(order_date)";
                      
            $result = mysqli_query($this->db, $query);
            $daily_data = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $daily_data[$row['date_val']] = (int)$row['revenue'];
                }
            }
            
            // Điền các ngày bị thiếu (có doanh thu = 0)
            $current_time = strtotime($start_date);
            $end_time = strtotime($end_date);
            
            // Giới hạn hiển thị nếu khoảng thời gian quá dài (ví dụ: chỉ hiện các ngày trong khoảng, nếu xa quá thì cũng đành)
            // Tuy nhiên, thường filter ngày là đủ
            while ($current_time <= $end_time) {
                $date_str = date('Y-m-d', $current_time);
                $labels[] = date('d/m', $current_time);
                $data[] = isset($daily_data[$date_str]) ? $daily_data[$date_str] : 0;
                $current_time = strtotime('+1 day', $current_time);
            }
        } else {
            // Không lọc -> Gom nhóm theo 12 Tháng của năm hiện tại
            $year = date('Y');
            $query = "SELECT MONTH(order_date) as month, SUM(total_amount) as revenue 
                      FROM orders 
                      WHERE status IN ('Hoan_tat', 'Completed', 'Đã giao', 'Hoàn thành') 
                      AND YEAR(order_date) = '$year'
                      GROUP BY MONTH(order_date)";
            $result = mysqli_query($this->db, $query);
            $monthly_data = array_fill(1, 12, 0); 
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $monthly_data[(int)$row['month']] = (int)$row['revenue'];
                }
            }
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = "Tháng $i";
                $data[] = $monthly_data[$i];
            }
        }
        
        return ['labels' => $labels, 'data' => $data];
    }
}
?>