<?php
// Database.php

class Database {
    // Kwenye AWS, hakikisha db_name, username na password zinalingana na ulivyoset kule MySQL Terminal
    private $host = "localhost";
    private $db_name = "salon_system";
    private $username = "root"; 
    private $password = ""; // Weka nenosiri halisi la MySQL ya AWS server yako hapa
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // MABORESHO: Imeongezwa charset=utf8mb4 kwa ajili ya utambuzi mzuri wa maandishi
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // MABORESHO: Kuzuia echo kwenye production ili kulinda usalama wa njia za server (paths)
            error_log("Database connection failure: " . $exception->getMessage());
            die("Samahani, tumepata hitilafu ya kiufundi. Tafadhali jaribu tena baadae.");
        }
        return $this->conn;
    }
}
?>