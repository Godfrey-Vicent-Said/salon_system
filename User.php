<?php
// User.php
require_once 'Database.php';
require_once 'Security.php';

class User {
    // Encapsulation: Mali (properties) zote ziko private kulinda data
    private $conn;
    private $table_name = "users";

    private $username;
    private $email;
    private $password;
    private $role;

    // Constructor: Inatengeneza muunganisho wa database tunapounda Object
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Setters na Getters
    public function setUsername($username) { $this->username = htmlspecialchars(strip_tags($username)); }
    public function setEmail($email) { $this->email = htmlspecialchars(strip_tags($email)); }
    public function setPassword($password) { $this->password = $password; }
    public function setRole($role) { $this->role = htmlspecialchars(strip_tags($role)); }

    // 1. Mbinu ya Kusajili Mtumiaji Mpya (Register)
    public function register() {
        if(empty($this->username) || empty($this->email) || empty($this->password)) {
            return "Tafadhali jaza nafasi zote.";
        }

        $query = "INSERT INTO " . $this->table_name . " SET username = :username, email = :email, password = :password, role = :role";
        $stmt = $this->conn->prepare($query);

        // USALAMA: Kufunga (Encrypt) data kabla ya kuzihifadhi kulingana na muundo wa mfumo
        $enc_username = Security::encrypt($this->username);
        $enc_email = Security::encrypt($this->email);
        $enc_role = Security::encrypt($this->role);
        
        // Nenosiri (Password) tunaitumia password_hash
        $hashed_password = password_hash($this->password, PASSWORD_BCRYPT);

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

        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Decrypt email ili tuilinganishe na alichoandika mtumiaji
            $decrypted_email = Security::decrypt($row['email']);

            if($decrypted_email === $this->email) {
                // Kuhakiki kama password inalingana na ile hashed password
                if(password_verify($this->password, $row['password'])) {
                    
                    // Session Management: Kuanzisha session na kuhifadhi data zilizofunguliwa
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    // Tunasave jina la column kulingana na database yako (user_id au id)
                    $_SESSION['user_id'] = isset($row['user_id']) ? $row['user_id'] : (isset($row['id']) ? $row['id'] : null);
                    $_SESSION['username'] = Security::decrypt($row['username']);
                    
                    // MABORESHO: Tunalazimisha role kuwa herufi ndogo (admin/customer) ili dashboard isome vizuri
                    $_SESSION['role'] = strtolower(Security::decrypt($row['role']));
                    
                    return true;
                }
            }
        }
        return "Barua pepe (Email) au nenosiri si sahihi.";
    }
}
?>