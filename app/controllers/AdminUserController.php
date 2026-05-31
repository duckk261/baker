<?php
require_once '../app/models/AdminUserModel.php';

class AdminUserController {
    private $model;
    public function __construct($db) { $this->model = new AdminUserModel($db); }

    // Gọi trang Nhân sự
    public function staff() {
        $staff_list = $this->model->getStaff();
        require_once 'views/staff_list.php';
    }

    // Gọi trang Khách hàng
    public function customers() {
        $customers = $this->model->getCustomers();
        require_once 'views/customer_list.php';
    }
}
?>