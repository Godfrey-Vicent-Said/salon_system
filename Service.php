<?php
// Service.php
require_once 'Database.php';

class Service {
    public $conn; // Imebadilishwa kuwa public ili dashboard.php iweze kuifikia connection ya PDO moja kwa moja
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

        // MAREKEBISHO: Orodha ya huduma na bei hazihitaji encryption ili ziendane na mfumo wetu wa SQL na Admin JOIN query
        $stmt->bindParam(':name', $this->service_name);
        $stmt->bindParam(':price', $this->price);

        if($stmt->execute()) {
            return true;
        }
        return "Imeshindikana kuongeza huduma.";
    }

    // 2. READ: Kuchukua Huduma Zote
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $services = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // MAREKEBISHO: Tunazisoma moja kwa moja bila decryption
            $services[] = [
                'service_id' => $row['service_id'],
                'service_name' => $row['service_name'],
                'price' => $row['price']
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