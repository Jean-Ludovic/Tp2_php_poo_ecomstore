<?php
// Classes/Admin.php

include_once 'User.php';

class Admin extends User
{
    protected $table_name = "admins";

    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function authenticate($username, $password)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                return true;
            }
        }
        return false;
    }

    public function deleteUser($user_id)
    {
        $query = "DELETE FROM utilisateurs WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $user_id);

        return $stmt->execute();
    }
}
