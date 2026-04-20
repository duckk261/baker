<?php
class Database {
    private static $instance = null;
    private $conn;

    // Private constructor to prevent multiple instances
    private function __construct() {
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "bakerystore"; // Update this if you translated your DB name to English

        $this->conn = mysqli_connect($host, $username, $password, $database);

        if (!$this->conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        mysqli_set_charset($this->conn, "utf8mb4");
    }

    // Single point of access to the database connection
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
?>