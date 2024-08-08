<?php
// ajout_produit.php

// Informations de connexion à la base de données

// Connexion à la base de données
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $note = $_POST['note'];

    // Traiter l'upload de l'image
    $target_dir = "../../assets/images/"; // Chemin de destination
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Vérifier si le fichier image est une image réelle
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('Le fichier n\'est pas une image.');</script>";
        $uploadOk = 0;
    }

    // Vérifier la taille du fichier
    if ($_FILES["image"]["size"] > 500000) {
        echo "<script>alert('Désolé, votre fichier est trop volumineux.');</script>";
        $uploadOk = 0;
    }

    // Autoriser certains formats de fichier
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp") {
        echo "<script>alert('Désolé, seuls les fichiers JPG, JPEG, PNG et webp sont autorisés.');</script>";
        $uploadOk = 0;
    }

    // Vérifier si $uploadOk est à 0 à cause d'une erreur
    if ($uploadOk == 0) {
        echo "<script>alert('Désolé, votre fichier n\'a pas été téléchargé.');</script>";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file; // Chemin de l'image
            // Insérer le produit dans la base de données
            $sql_insert_product = "INSERT INTO produits (nom, prix, image, note) VALUES ('$nom', '$prix', '$image', '$note')";
            if ($conn->query($sql_insert_product) === TRUE) {
                echo "<script>alert('Produit ajouté avec succès!');</script>";
            } else {
                echo "<script>alert('Erreur lors de l\'ajout du produit : " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Désolé, une erreur s\'est produite lors du téléchargement de votre fichier.');</script>";
        }
    }
}
?>

<form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="nom">Nom du produit :</label>
        <input type="text" class="form-control" id="nom" name="nom" required>
    </div>
    <div class="form-group">
        <label for="prix">Prix :</label>
        <input type="number" class="form-control" id="prix" name="prix" required>
    </div>
    <div class="form-group">
        <label for="image">Image :</label>
        <input type="file" class="form-control" id="image" name="image" required>
    </div>
    <div class="form-group">
        <label for="note">Note :</label>
        <input type="number" class="form-control" id="note" name="note" step="1" min="0" max="5">
    </div>
    <button type="submit" class="btn btn-primary">Ajouter le produit</button>
</form>