<?php
// Controller này sẽ xử lý logic cho trang Quản lý đánh giá
class AdminReviewController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        // 1. Nhận các tham số tìm kiếm và sắp xếp
        $search = isset($_GET['search']) ? mysqli_real_escape_string($this->db, $_GET['search']) : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'total_reviews'; // Mặc định sort theo số lượng
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC'; // Mặc định giảm dần

        $allowed_sort = ['avg_rating', 'total_reviews'];
        $sort = in_array($sort, $allowed_sort) ? $sort : 'total_reviews';
        $order = ($order == 'ASC') ? 'ASC' : 'DESC';

        $sql = "SELECT p.product_id, p.product_name, p.image, 
                       AVG(r.rating) as avg_rating, 
                       COUNT(r.review_id) as total_reviews 
                FROM products p 
                JOIN reviews r ON p.product_id = r.product_id 
                WHERE p.product_name LIKE '%$search%'
                GROUP BY p.product_id 
                ORDER BY $sort $order"; 
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