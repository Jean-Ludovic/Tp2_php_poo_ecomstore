<?php
header('Content-Type: application/json');
session_start();

// Vérifiez que l'utilisateur est administrateur
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    exit();
}

require_once '../../DATABASE/Database.php';
require_once '../../Classes/Produits.php';

// Connexion à la base de données
$database = new Database();
$conn = $database->getConnection();

// Récupérer l'ID du produit à supprimer
$data = json_decode(file_get_contents('php://input'), true);

// Créer une instance de Produits
$produit = new Produits($conn);

// Définir l'ID du produit à supprimer
$produit->id = $data['id'];

// Supprimer le produit
if ($produit->delete()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression du produit.']);
}

$conn = null; // Pour fermer la connexion PDO
