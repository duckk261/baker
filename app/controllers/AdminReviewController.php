<?php
// Controller này sẽ xử lý logic cho trang Quản lý đánh giá
class AdminReviewController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Hiển thị danh sách đánh giá tổng hợp
    public function index() {
        $search = isset($_GET['search']) ? mysqli_real_escape_string($this->db, $_GET['search']) : '';
        
        // Câu lệnh SQL lấy thông tin sản phẩm kèm điểm trung bình và số lượt đánh giá
   $sql = "SELECT p.product_id, p.product_name, p.image, 
               AVG(r.rating) as avg_rating, 
               COUNT(r.review_id) as total_reviews 
        FROM products p 
        JOIN reviews r ON p.product_id = r.product_id  -- Đã đổi thành JOIN
        WHERE p.product_name LIKE '%$search%'
        GROUP BY p.product_id 
        ORDER BY p.product_id DESC";
        
        $reviews_data = mysqli_query($this->db, $sql);
        
        // Gọi view hiển thị bảng
        require_once 'views/reviews.php';
    }

    // Xem chi tiết đánh giá của một sản phẩm
    public function detail($product_id) {
        $product_id = mysqli_real_escape_string($this->db, $product_id);
        
     $sql = "SELECT r.*, c.full_name 
                FROM reviews r 
                JOIN customers c ON r.account_id = c.customer_id 
                WHERE r.product_id = '$product_id' 
                ORDER BY r.created_at DESC";
        
        $reviews_detail = mysqli_query($this->db, $sql);
        
        require_once 'views/review_detail.php';
    }
}