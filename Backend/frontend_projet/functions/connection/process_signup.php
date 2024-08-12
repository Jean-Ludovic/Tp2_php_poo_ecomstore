<?php
include_once '../../DATABASE/Database.php';
include_once '../../Classes/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user->username = $_POST['username'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($user->password == $password_confirm) {
        if ($user->create()) {
            echo "<script>
                    alert('Inscription réussie!');
                    window.location.href='../../../login-form-14/login-form-14/index.html';
                  </script>";
        } else {
            echo "<script>alert('Une erreur est survenue lors de l\'inscription.');</script>";
        }
    } else {
        echo "<script>alert('Les mots de passe ne correspondent pas.');</script>";
    }
}
