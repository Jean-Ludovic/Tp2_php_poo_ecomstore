<?php
// DATABASE/Database.php
class Database
{
    private $host = "localhost";
    private $db_name = "ecom_store";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->initializeUsers();
        } catch (PDOException $exception) {
            echo "Erreur de connexion: " . $exception->getMessage();
        }
        return $this->conn;
    }

    private function initializeUsers()
    {
        $users = [
            [
                'username' => 'JeanLudo',
                'email' => 'jeanludo@example.com',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'table' => 'utilisateurs'
            ],
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash('adminpassword', PASSWORD_BCRYPT),
                'table' => 'admins'
            ]
        ];

        foreach ($users as $user) {
            $query = "SELECT COUNT(*) FROM " . $user['table'] . " WHERE username = :username";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $user['username']);
            $stmt->execute();

            if ($stmt->fetchColumn() == 0) {
                $insertQuery = "INSERT INTO " . $user['table'] . " (username, email, password) VALUES (:username, :email, :password)";
                $insertStmt = $this->conn->prepare($insertQuery);
                $insertStmt->bindParam(':username', $user['username']);
                $insertStmt->bindParam(':email', $user['email']);
                $insertStmt->bindParam(':password', $user['password']);
                $insertStmt->execute();
            }
        }
    }
}
