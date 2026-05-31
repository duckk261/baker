<?php
require_once '../app/models/AdminProductModel.php';

class AdminProductController {
    private $model;

    public function __construct($db) {
        $this->model = new AdminProductModel($db);
    }

    // Hiển thị danh sách
    public function index() {
        $products = $this->model->getAllProducts();
        
        require_once 'views/product_list.php';
    }

    // Xử lý Xóa
    public function delete($id) {
        if ($this->model->deleteProduct($id)) {
            echo "<script>alert('Đã xóa sản phẩm!'); window.location.href='index.php?page=products';</script>";
        } else {
            echo "<script>alert('Lỗi xóa sản phẩm!'); window.location.href='index.php?page=products';</script>";
        }
    }
}
?>