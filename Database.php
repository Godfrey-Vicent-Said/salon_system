<?php
// Database.php

class Database {
    private $host = "localhost";
    private $db_name = "salon_system";
    private $username = "root"; 
    private $password = "";     // Kama una password kwenye XAMPP weka hapa, la sivyo acha wazi ""
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Shida ya kuunganisha database: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>