<?php
session_start();

// Inclure les classes nécessaires
require_once '../../DATABASE/Database.php';
require_once '../../Classes/Produits.php';

// Vérifiez que l'utilisateur est administrateur
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die("Accès non autorisé");
}

// Connexion à la base de données
$database = new Database();
$conn = $database->getConnection();

// Initialisation de l'objet Produits
$produit = new Produits($conn);

// Récupération des produits existants
$products = $produit->getAllProducts();
if (!$products) {
    $products = [];
}

// Dossier où les images sont stockées
$image_directory = '../../assets/images/';

// Liste des extensions d'images supportées
$valid_extensions = ['jpg', 'jpeg', 'png', 'webp'];

// Gestion de l'ajout d'un nouveau produit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $note = $_POST['note'];
    $image = $_FILES['image'];

    // Validation et téléchargement de l'image
    if ($image['error'] == 0) {
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $image_name = strtolower($nom) . '.' . $extension;
        $image_path = $image_directory . $image_name;

        if (in_array($extension, $valid_extensions)) {
            if (move_uploaded_file($image['tmp_name'], $image_path)) {
                $produit->nom = $nom;
                $produit->prix = $prix;
                $produit->note = $note;
                $produit->image = $image_name;

                if ($produit->create()) {
                    $message = "Produit ajouté avec succès!";
                    // Actualisation de la liste des produits
                    $products = $produit->getAllProducts();
                } else {
                    $message = "Erreur lors de l'ajout du produit.";
                }
            } else {
                $message = "Erreur lors du téléchargement de l'image.";
            }
        } else {
            $message = "Extension d'image non valide. Utilisez jpg, jpeg, png ou webp.";
        }
    } else {
        $message = "Erreur lors de l'upload de l'image.";
    }
}

// Gestion de la modification du produit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $id = $_POST['product_id'];
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $note = $_POST['note'];
    $image = $_FILES['image'];

    $produit->id = $id;
    $produit->nom = $nom;
    $produit->prix = $prix;
    $produit->note = $note;

    // Vérification si une nouvelle image a été uploadée
    if ($image['error'] == 0) {
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $image_name = strtolower($nom) . '.' . $extension;
        $image_path = $image_directory . $image_name;

        if (in_array($extension, $valid_extensions)) {
            if (move_uploaded_file($image['tmp_name'], $image_path)) {
                $produit->image = $image_name;
            } else {
                $message = "Erreur lors du téléchargement de la nouvelle image.";
            }
        } else {
            $message = "Extension d'image non valide pour la modification.";
        }
    }

    if ($produit->update()) {
        $message = "Produit modifié avec succès!";
        // Actualisation de la liste des produits
        $products = $produit->getAllProducts();
    } else {
        $message = "Erreur lors de la modification du produit.";
    }
}

// Gestion de la suppression du produit
if (isset($_GET['delete_id'])) {
    $produit->id = $_GET['delete_id'];
    if ($produit->delete()) {
        $message = "Produit supprimé avec succès!";
        // Actualisation de la liste des produits
        $products = $produit->getAllProducts();
    } else {
        $message = "Erreur lors de la suppression du produit.";
    }
}

$conn = null; // Fermer la connexion
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

        <h1 class="mt-3">Gestion des Produits</h1>

        <?php if (isset($message)) : ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Bouton pour afficher/cacher le formulaire -->
        <button class="btn btn-primary" id="toggleFormButton">Ajouter un produit</button>

        <!-- Formulaire d'ajout de produit -->
        <div class="form-container" id="productFormContainer">
            <form method="POST" action="gestion_produits.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du produit</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="mb-3">
                    <label for="prix" class="form-label">Prix</label>
                    <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
                </div>
                <div class="mb-3">
                    <label for="note" class="form-label">Note</label>
                    <input type="number" class="form-control" id="note" name="note" min="0" max="5" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image du produit</label>
                    <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png,.webp" required>
                </div>
                <button type="submit" class="btn btn-success" name="add_product">Ajouter</button>
            </form>
        </div>

        <!-- Tableau des produits -->
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Note</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)) : ?>
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['id']); ?></td>
                                <td contenteditable="true"><?php echo htmlspecialchars($product['nom']); ?></td>
                                <td contenteditable="true"><?php echo htmlspecialchars($product['prix']); ?>€</td>
                                <td contenteditable="true"><?php echo htmlspecialchars($product['note']); ?></td>
                                <td>
                                    <img src="../../assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" style="width:50px; height:auto;">
                                </td>
                                <td>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal-<?php echo $product['id']; ?>">Modifier</button>
                                    <a href="gestion_produits.php?delete_id=<?php echo $product['id']; ?>" class="btn btn-danger">Supprimer</a>
                                </td>
                            </tr>

                            <!-- Modal pour la modification du produit -->
                            <div class="modal fade" id="editModal-<?php echo $product['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel-<?php echo $product['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel-<?php echo $product['id']; ?>">Modifier Produit</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="gestion_produits.php" enctype="multipart/form-data">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="nom-<?php echo $product['id']; ?>" class="form-label">Nom du produit</label>
                                                    <input type="text" class="form-control" id="nom-<?php echo $product['id']; ?>" name="nom" value="<?php echo htmlspecialchars($product['nom']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="prix-<?php echo $product['id']; ?>" class="form-label">Prix</label>
                                                    <input type="number" step="0.01" class="form-control" id="prix-<?php echo $product['id']; ?>" name="prix" value="<?php echo htmlspecialchars($product['prix']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="note-<?php echo $product['id']; ?>" class="form-label">Note</label>
                                                    <input type="number" class="form-control" id="note-<?php echo $product['id']; ?>" name="note" value="<?php echo htmlspecialchars($product['note']); ?>" min="0" max="5" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="image-<?php echo $product['id']; ?>" class="form-label">Image du produit</label>
                                                    <input type="file" class="form-control" id="image-<?php echo $product['id']; ?>" name="image" accept=".jpg,.jpeg,.png,.webp">
                                                </div>
                                                <button type="submit" class="btn btn-primary" name="update_product">Mettre à jour</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6">Aucun produit trouvé.</td>
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
            const formContainer = document.getElementById('productFormContainer');
            if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                formContainer.style.display = 'block';
            } else {
                formContainer.style.display = 'none';
            }
        });
    </script>
</body>

</html>