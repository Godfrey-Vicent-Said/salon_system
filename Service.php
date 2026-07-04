<?php
// Service.php
require_once 'Database.php';
require_once 'Security.php';

class Service {
    private $conn;
    private $table_name = "services";

    private $service_name;
    private $price;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Setters
    public function setServiceName($name) { $this->service_name = htmlspecialchars(strip_tags($name)); }
    public function setPrice($price) { $this->price = htmlspecialchars(strip_tags($price)); }

    // 1. CREATE: Kuongeza Huduma Mpya (Admin)
    public function create() {
        if(empty($this->service_name) || empty($this->price)) {
            return "Tafadhali jaza nafasi zote.";
        }

        $query = "INSERT INTO " . $this->table_name . " SET service_name = :name, price = :price";
        $stmt = $this->conn->prepare($query);

        // USALAMA: Kufunga data (Encryption) kabla ya kuhifadhi kwenye database[cite: 1]
        $enc_name = Security::encrypt($this->service_name);
        $enc_price = Security::encrypt($this->price);

        $stmt->bindParam(':name', $enc_name);
        $stmt->bindParam(':price', $enc_price);

        if($stmt->execute()) {
            return true;
        }
        return "Imeshindikana kuongeza huduma.";
    }

    // 2. READ: Kuchukua Huduma Zote na Kuzifungua (Decryption)
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $services = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Decrypt data wakati wa kuzitoa kwenye database ili zionekane vizuri[cite: 1]
            $services[] = [
                'service_id' => $row['service_id'],
                'service_name' => Security::decrypt($row['service_name']),
                'price' => Security::decrypt($row['price'])
            ];
        }
        return $services;
    }

    // 3. DELETE: Kufuta Huduma (Admin)
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE service_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>