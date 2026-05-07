<?php
class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "bakerystore";  

        $this->conn = mysqli_connect($host, $username, $password, $database);

        if (!$this->conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        mysqli_set_charset($this->conn, "utf8mb4");
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
?>