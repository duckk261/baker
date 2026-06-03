<?php
class AdminContactController {
    private $db;
    public function __construct($db) { $this->db = $db; }

   public function index() {
    $search = isset($_GET['search']) ? mysqli_real_escape_string($this->db, $_GET['search']) : '';
    
    $sql = "SELECT * FROM contacts WHERE name LIKE '%$search%' ORDER BY created_at DESC";
    $contacts_data = mysqli_query($this->db, $sql);
    
    // Đảm bảo $contacts_data là một đối tượng kết quả hợp lệ, dù là rỗng
    if (!$contacts_data) {
        $contacts_data = []; // Tránh lỗi undefined nếu query lỗi
    }
    
    require_once 'views/contacts.php';
}
}