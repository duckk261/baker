<?php
class UserModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getUserProfile($customer_id) {
        $sql = "SELECT * FROM Customers WHERE customer_id = '$customer_id'";
        $result = mysqli_query($this->db, $sql);
        return mysqli_fetch_assoc($result);
    }
}
?>