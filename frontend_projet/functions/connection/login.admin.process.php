<?php
session_start();

// Simuler la vérification de l'authentification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simuler l'authentification (remplacer par votre logique réelle)
    if ($username === 'admin' && $password === 'adminpassword') {
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = true;
        header('Location: ../../index.php');
        exit();
    } else {
        echo "<script>alert('Nom d\'utilisateur ou mot de passe incorrect.'); window.location.href = 'admin.form.php';</script>";
    }
}
