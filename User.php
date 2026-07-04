<?php
// User.php
require_once 'Database.php';
require_once 'Security.php';

class User {
    // Encapsulation: Mali (properties) zote ziko private kulinda data dhidi ya ufikiaji wa nje wa moja kwa moja
    private $conn;
    private $table_name = "users";

    private $username;
    private $email;
    private $password;
    private $role;

    // Constructor: Inajiendesha yenyewe kutengeneza muunganisho wa database tunapounda Object[cite: 1]
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Setters na Getters (Sehemu ya Encapsulation)
    public function setUsername($username) { $this->username = htmlspecialchars(strip_tags($username)); }
    public function setEmail($email) { $this->email = htmlspecialchars(strip_tags($email)); }
    public function setPassword($password) { $this->password = $password; }
    public function setRole($role) { $this->role = htmlspecialchars(strip_tags($role)); }

    // 1. Mbinu ya Kusajili Mtumiaji Mpya (Register)
    public function register() {
        // Form Validation ya msingi (Kuhakikisha hakuna kilicho wazi)[cite: 1]
        if(empty($this->username) || empty($this->email) || empty($this->password)) {
            return "Tafadhali jaza nafasi zote.";
        }

        // Kulinda database dhidi ya SQL Injection kwa kutumia Prepared Statements (PDO)[cite: 1]
        $query = "INSERT INTO " . $this->table_name . " SET username = :username, email = :email, password = :password, role = :role";
        $stmt = $this->conn->prepare($query);

        // USALAMA: Kufunga (Encrypt) row data zote kabla ya kuzihifadhi kulingana na document[cite: 1]
        $enc_username = Security::encrypt($this->username);
        $enc_email = Security::encrypt($this->email);
        $enc_role = Security::encrypt($this->role);
        
        // Nenosiri (Password) tunaitumia password_hash (salama zaidi kuliko encryption ya kawaida)
        $hashed_password = password_hash($this->password, PASSWORD_BCRYPT);

        // Binding parameters kwenye PDO statement[cite: 1]
        $stmt->bindParam(':username', $enc_username);
        $stmt->bindParam(':email', $enc_email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $enc_role);

        if($stmt->execute()) {
            return true;
        }
        return "Kuna hitilafu imetokea wakati wa kusajili.";
    }

    // 2. Mbinu ya Kuingia Kwenye Mfumo (Login)
    public function login() {
        if(empty($this->email) || empty($this->password)) {
            return "Tafadhali jaza nafasi zote.";
        }

        // Kwenye database data ziko encrypted, hivyo inabidi tuchukue watumiaji wote na ku-decrypt ili kupata anayelingana
        // Hii ni kwa sababu encryption yetu inazalisha string tofauti kila wakati (kwa sababu ya IV ya kipekee)[cite: 1]
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Decrypt data ili tuzilinganishe na kile alichochapa mtumiaji[cite: 1]
            $decrypted_email = Security::decrypt($row['email']);

            if($decrypted_email === $this->email) {
                // Kuhakiki kama password aliyoingiza inalingana na ile hashed password
                if(password_verify($this->password, $row['password'])) {
                    
                    // Session Management: Kuanzisha session na kuhifadhi data za mtumiaji zilizofunguliwa[cite: 1]
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['username'] = Security::decrypt($row['username']);
                    $_SESSION['role'] = Security::decrypt($row['role']);
                    
                    return true;
                }
            }
        }
        return "Barua pepe (Email) au nenosiri si sahihi.";
    }
}
?>