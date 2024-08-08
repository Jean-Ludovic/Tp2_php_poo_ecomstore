<?php
class Paiements
{
    private $conn;
    private $table_name = "paiements";

    public $id;
    public $utilisateur_id;
    public $montant;
    public $date_paiement;
    public $statut;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Méthode pour créer un paiement
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " (utilisateur_id, montant, statut) VALUES (:utilisateur_id, :montant, :statut)";
        $stmt = $this->conn->prepare($query);

        $this->utilisateur_id = htmlspecialchars(strip_tags($this->utilisateur_id));
        $this->montant = htmlspecialchars(strip_tags($this->montant));
        $this->statut = htmlspecialchars(strip_tags($this->statut));

        $stmt->bindParam(':utilisateur_id', $this->utilisateur_id);
        $stmt->bindParam(':montant', $this->montant);
        $stmt->bindParam(':statut', $this->statut);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Méthode pour lire tous les paiements
    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Méthode pour mettre à jour un paiement
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET utilisateur_id = :utilisateur_id, montant = :montant, statut = :statut WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->utilisateur_id = htmlspecialchars(strip_tags($this->utilisateur_id));
        $this->montant = htmlspecialchars(strip_tags($this->montant));
        $this->statut = htmlspecialchars(strip_tags($this->statut));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':utilisateur_id', $this->utilisateur_id);
        $stmt->bindParam(':montant', $this->montant);
        $stmt->bindParam(':statut', $this->statut);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Méthode pour supprimer un paiement
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
