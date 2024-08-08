<?php
session_start();
include_once '../../Classes/User.php';
include_once '../../DATABASE/Database.php';

// Connexion à la base de données
$database = new Database();
$conn = $database->getConnection();

// Créer une instance de la classe User
$user = new User($conn);

// Récupérer les informations du formulaire
$username = $_POST['username'];
$password = $_POST['password'];

// Vérifier si l'utilisateur existe
if ($user->authenticate($username, $password)) {
    $_SESSION['username'] = $username;
    $_SESSION['is_admin'] = false;  // Assurez-vous que ce n'est pas un admin

    // Redirection vers l'index après connexion réussie
    header("Location: ../../index.php");
    exit();
} else {
    // Si les informations sont incorrectes, rediriger vers la page de connexion
    $_SESSION['login_error'] = "Nom d'utilisateur ou mot de passe incorrect.";
    header("Location: ../../login-form-14/login-form-14/index.html");
    exit();
}
