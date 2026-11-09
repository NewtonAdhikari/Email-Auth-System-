<?php
require_once dirname(__FILE__) . '/config.php';

class Database {
    private $conn;
    
    public function connect() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, DB_USER, DB_PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        if (!$this->conn) {
            $this->connect();
        }
        return $this->conn;
    }
}
?>
