<?php
header('Content-Type: application/json');
session_start();

// Vérifiez que l'utilisateur est administrateur
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    exit();
}

require_once '../../DATABASE/Database.php';
require_once '../../Classes/User.php';

// Connexion à la base de données
$database = new Database();
$conn = $database->getConnection();

// Récupérer les données JSON
$data = json_decode(file_get_contents('php://input'), true);

// Créer une instance de User
$user = new User($conn);

// Affecter les valeurs de l'objet user
$user->id = $data['id'];
$user->username = $data['username'];
$user->email = $data['email'];

// Mettre à jour l'utilisateur
if ($user->update()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'utilisateur.']);
}

$conn = null; // Pour fermer la connexion PDO
