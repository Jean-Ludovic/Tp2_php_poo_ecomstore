<?php
session_start();
include_once '../../Classes/Login.php';
include_once '../../DATABASE/Database.php';

$database = new Database();
$conn = $database->getConnection();
$login = new Login($conn);

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Définir le chemin relatif pour la page utilisateur
    $user_accueil_path = '/e_comerce_2/frontend/frontend_projet/';

    // Vérification des informations de connexion pour les utilisateurs
    if ($login->authenticate($username, $password)) {
        $_SESSION['username'] = $username; // Stocke l'utilisateur dans la session
        $response['status'] = 'success';
        $response['redirect'] = $user_accueil_path;
    } else {
        $response['status'] = 'error';
        $response['message'] = "Nom d'utilisateur ou mot de passe incorrect.";
    }

    echo json_encode($response);
    exit;
}
