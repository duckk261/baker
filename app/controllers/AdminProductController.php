<?php
require_once '../app/models/AdminProductModel.php';

class AdminProductController {
    private $model;

    public function __construct($db) {
        $this->model = new AdminProductModel($db);
    }

    // Hiển thị danh sách
    public function index() {

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // 2. Truyền từ khóa vào hàm của Model để lọc dữ liệu
        $products = $this->model->getAllProducts($search);
        
        require_once 'views/product_list.php';
    }

    // Xử lý Xóa
    public function delete($id) {
        if ($this->model->deleteProduct($id)) {
            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Đã xóa sản phẩm!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=products'; });});</script>";
        } else {
            echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><style>body { font-family: sans-serif; }</style><script>document.addEventListener(\"DOMContentLoaded\", function() {Swal.fire({title: \"Thông báo\", text: \"Lỗi xóa sản phẩm!\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}).then((result) => { window.location.href = 'index.php?page=products'; });});</script>";
        }
    }
}
?>