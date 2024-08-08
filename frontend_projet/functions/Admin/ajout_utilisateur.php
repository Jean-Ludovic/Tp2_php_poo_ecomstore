<?php
// ajout_utilisateur.php

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Connexion à la base de données
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Utiliser une requête préparée pour éviter les injections SQL
    $stmt = $conn->prepare("INSERT INTO utilisateurs (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Utilisateur ajouté avec succès!'); window.location.href='http://localhost/e_comerce_2/frontend/frontend_projet';</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'ajout de l\'utilisateur : " . $conn->error . "');</script>";
    }
    $stmt->close();
}

// Fermer la connexion
$conn->close();
?>

<form action="" method="POST">
    <div class="form-group">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="email">Email :</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Mot de passe :</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter l'utilisateur</button>
</form>