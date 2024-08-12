<?php
session_start();

// Vérifier que l'utilisateur est administrateur
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die("Accès non autorisé");
}

// Inclure les classes nécessaires
require_once '../../DATABASE/Database.php';
require_once '../../Classes/User.php';

// Connexion à la base de données
$database = new Database();
$conn = $database->getConnection();

// Initialisation de l'objet User
$user = new User($conn);

// Récupération des utilisateurs
$users = $user->getAllUsers();

// Message de confirmation
$message = "";

// Gestion de l'ajout d'un nouvel utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user->username = $username;
    $user->email = $email;
    $user->password = $password;

    if ($user->create()) {
        $message = "Utilisateur ajouté avec succès!";
        // Actualiser la liste des utilisateurs
        $users = $user->getAllUsers();
    } else {
        $message = "Erreur lors de l'ajout de l'utilisateur.";
    }
}

// Gestion de la modification d'un utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    if ($user->update($id, $username, $email)) {
        $message = "Utilisateur modifié avec succès!";
        // Actualiser la liste des utilisateurs
        $users = $user->getAllUsers();
    } else {
        $message = "Erreur lors de la modification de l'utilisateur.";
    }
}

// Gestion de la suppression d'un utilisateur
if (isset($_GET['delete_id'])) {
    $user->id = $_GET['delete_id'];
    if ($user->delete()) {
        $message = "Utilisateur supprimé avec succès!";
        // Actualiser la liste des utilisateurs
        $users = $user->getAllUsers();
    } else {
        $message = "Erreur lors de la suppression de l'utilisateur.";
    }
}

$conn = null; // Fermer la connexion
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-container {
            margin-top: 20px;
        }

        .form-container {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Bouton de retour -->
        <a href="../../index.php" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i> Retour
        </a>

        <h1 class="mt-3">Gestion des Utilisateurs</h1>

        <?php if ($message) : ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Bouton pour afficher/cacher le formulaire -->
        <button class="btn btn-primary" id="toggleFormButton">Ajouter un utilisateur</button>

        <!-- Formulaire d'ajout d'utilisateur -->
        <div class="form-container" id="userFormContainer">
            <form method="POST" action="gestion_utilisateurs.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success" name="add_user">Ajouter</button>
            </form>
        </div>

        <!-- Tableau des utilisateurs -->
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Email</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)) : ?>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td contenteditable="true"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td contenteditable="true"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                                <td>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal-<?php echo $user['id']; ?>">Modifier</button>
                                    <a href="gestion_utilisateurs.php?delete_id=<?php echo $user['id']; ?>" class="btn btn-danger">Supprimer</a>
                                </td>
                            </tr>

                            <!-- Modal pour la modification de l'utilisateur -->
                            <div class="modal fade" id="editModal-<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel-<?php echo $user['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel-<?php echo $user['id']; ?>">Modifier Utilisateur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="gestion_utilisateurs.php">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="username-<?php echo $user['id']; ?>" class="form-label">Nom d'utilisateur</label>
                                                    <input type="text" class="form-control" id="username-<?php echo $user['id']; ?>" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email-<?php echo $user['id']; ?>" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email-<?php echo $user['id']; ?>" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary" name="update_user">Mettre à jour</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5">Aucun utilisateur trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script pour afficher/masquer le formulaire
        document.getElementById('toggleFormButton').addEventListener('click', function() {
            const formContainer = document.getElementById('userFormContainer');
            if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                formContainer.style.display = 'block';
            } else {
                formContainer.style.display = 'none';
            }
        });
    </script>
</body>

</html>