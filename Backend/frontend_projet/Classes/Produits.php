<?php
class Produits
{
    private $conn;
    private $table_name = "produits";

    public $id;
    public $nom;
    public $prix;
    public $note;
    public $image;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET nom=:nom, prix=:prix, note=:note, image=:image";

        $stmt = $this->conn->prepare($query);

        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prix = htmlspecialchars(strip_tags($this->prix));
        $this->note = htmlspecialchars(strip_tags($this->note));
        $this->image = htmlspecialchars(strip_tags($this->image));

        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prix", $this->prix);
        $stmt->bindParam(":note", $this->note);
        $stmt->bindParam(":image", $this->image);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET nom=:nom, prix=:prix, note=:note, image=:image WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prix = htmlspecialchars(strip_tags($this->prix));
        $this->note = htmlspecialchars(strip_tags($this->note));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prix", $this->prix);
        $stmt->bindParam(":note", $this->note);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getAllProducts()
    {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
