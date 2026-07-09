<?php
// User.php
require_once 'Database.php';
require_once 'Security.php';

class User {
    private $conn;
    private $table_name = "users";

    private $username;
    private $email;
    private $password;
    private $role;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function setUsername($username) { $this->username = htmlspecialchars(strip_tags($username)); }
    public function setEmail($email) { $this->email = htmlspecialchars(strip_tags($email)); }
    public function setPassword($password) { $this->password = $password; }
    public function setRole($role) { $this->role = htmlspecialchars(strip_tags($role)); }

    public function register() {
        if(empty($this->username) || empty($this->email) || empty($this->password)) {
            return "Tafadhali jaza nafasi zote.";
        }

        $query = "INSERT INTO " . $this->table_name . " SET username = :username, email = :email, password = :password, role = :role";
        $stmt = $this->conn->prepare($query);

        $enc_username = Security::encrypt($this->username);
        $enc_email = Security::encrypt($this->email);
        $enc_role = Security::encrypt($this->role);
        
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

    public function login() {
        if(empty($this->email) || empty($this->password)) {
            return "Tafadhali jaza nafasi zote.";
        }

        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $decrypted_email = trim(Security::decrypt($row['email']));

            if($decrypted_email === trim($this->email)) {
                if(password_verify($this->password, $row['password'])) {
                    
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    // Kulinda ishu ya user_id tuseme inasoma column sahihi ya table yako
                    $_SESSION['user_id'] = isset($row['user_id']) ? $row['user_id'] : null;
                    $_SESSION['username'] = trim(Security::decrypt($row['username']));
                    
                    // Kusafisha Decrypted Role kuwa safi kabisa kwa ajili ya dashboard
                    $clean_role = trim(Security::decrypt($row['role']));
                    $_SESSION['role'] = strtolower($clean_role);
                    
                    return true;
                }
            }
        }
        return "Barua pepe (Email) au nenosiri si sahihi.";
    }
}
?>