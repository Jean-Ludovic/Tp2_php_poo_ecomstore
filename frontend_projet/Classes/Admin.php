<?php
include_once 'User.php';

class Admin extends User
{
    protected $table_name = "admins";

    public function __construct($db)
    {
        parent::__construct($db);
    }

    // Méthode pour créer un nouvel administrateur
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Méthode spécifique aux administrateurs
    public function deleteUser($user_id)
    {
        $query = "DELETE FROM utilisateurs WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $user_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Ajoutez d'autres méthodes spécifiques aux administrateurs ici
}
