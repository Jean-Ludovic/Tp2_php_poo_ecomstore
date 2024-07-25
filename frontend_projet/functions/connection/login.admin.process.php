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

    // Vérification des informations d'identification fixes
    if ($username == 'admin' && $password == 'adminpassword') {
        // Stocke l'utilisateur dans la session et redirige
        $_SESSION['username'] = $username;
        $response['status'] = 'success';
        $response['redirect'] = '/e_comerce_2/frontend/frontend_projet/';
    } else {
        // Utiliser la table des administrateurs pour la vérification
        $login->setTable('admins');

        // Chemin de redirection pour les administrateurs
        $admin_dashboard_path = '/e_comerce_2/frontend/frontend_projet/';

        // Vérification des informations de connexion pour les administrateurs
        if ($login->authenticate($username, $password)) {
            $_SESSION['username'] = $username; // Stocke l'utilisateur dans la session
            $response['status'] = 'success';
            $response['redirect'] = $admin_dashboard_path;
        } else {
            $response['status'] = 'error';
            $response['message'] = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }

    echo json_encode($response);
    exit;
}
