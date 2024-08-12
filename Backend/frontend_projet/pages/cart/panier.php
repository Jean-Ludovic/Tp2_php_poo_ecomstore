<?php
session_start();

// Inclure les classes nécessaires
require_once '../../Classes/Panier.php';

// Initialisation des objets
$panier = new Panier();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Invité';

// Vérifier si une mise à jour, une suppression ou un vidage du panier est demandé
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['produit_id']) && isset($_POST['action'])) {
        $produitId = $_POST['produit_id'];
        if ($_POST['action'] == 'update' && isset($_POST['quantite'])) {
            $quantite = $_POST['quantite'];
            $panier->mettreAJourQuantite($produitId, $quantite);
        } elseif ($_POST['action'] == 'delete') {
            $panier->supprimerProduit($produitId);
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'clear_cart') {
        $panier->viderPanier(); // Fonction à implémenter dans la classe Panier pour vider le panier
    }
}

// Obtenir les produits dans le panier
$items = $panier->obtenirPanier();
$totalPrix = 0;
$image_directory = '../../assets/images/';
$supported_extensions = ['webp', 'png', 'jpg', 'jpeg'];

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
    <style>
        .btn-custom {
            background-color: #343a40;
            color: #ffffff;
        }

        .btn-custom:hover {
            background-color: #23272b;
            color: #ffffff;
        }

        .btn-shop {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- Button to return to shop -->
        <button class="btn btn-secondary btn-shop">
            <i class="fa-solid fa-people-pulling"></i>
            <a href="../../index.php" style="color: white; text-decoration: none;">Continue to shop</a>
        </button>

        <h2>Votre Panier</h2>
        <h1 style="text-align: center;">
            Utilisateur: <?php echo htmlspecialchars($username); ?>
        </h1>
        <div class="row">
            <div class="col-12">
                <?php if (!empty($items)) : ?>
                    <?php foreach ($items as $produitId => $details) : ?>
                        <?php
                        $totalPrix += $details['prix'] * $details['quantite'];

                        // Déterminer l'image du produit
                        $image_src = '../../assets/images/default.webp'; // Fallback image
                        foreach ($supported_extensions as $extension) {
                            $image_path = $image_directory . strtolower($details['nom']) . '.' . $extension;
                            if (file_exists($image_path)) {
                                $image_src = $image_path;
                                break;
                            }
                        }
                        ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="<?php echo htmlspecialchars($image_src); ?>" class="img-fluid rounded-3" alt="<?php echo htmlspecialchars($details['nom']); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <h5><?php echo htmlspecialchars($details['nom']); ?></h5>
                                        <p>Prix: <?php echo htmlspecialchars($details['prix']); ?>€</p>
                                        <p>Quantité: <?php echo htmlspecialchars($details['quantite']); ?></p>
                                    </div>
                                    <div class="col-md-3 d-flex">
                                        <form method="POST" action="">
                                            <input type="hidden" name="produit_id" value="<?php echo $produitId; ?>">
                                            <input type="hidden" name="action" value="update">
                                            <input type="number" name="quantite" value="<?php echo $details['quantite']; ?>" class="form-control" min="1">
                                            <button type="submit" class="btn btn-primary" style="padding-left: 50X;">
                                                <i class="fa-solid fa-pen-nib"></i> Mettre à jour
                                            </button>
                                        </form>
                                        <form method="POST" action="">
                                            <input type="hidden" name="produit_id" value="<?php echo $produitId; ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn btn-danger" style="padding-right: 250X;">
                                                <i class="fa-solid fa-trash-can"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="card">
                        <div class="card-body">
                            <!-- Centered and Larger Total Amount -->
                            <h2 style="text-align: center; font-size: 2.5rem;">Total: <?php echo $totalPrix; ?>€</h2>

                            <!-- Centered Clear Cart Button -->
                            <div style="text-align: center; margin-bottom: 20px;">
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="clear_cart">
                                    <button type="submit" class="btn btn-warning mt-2" style="font-size: 1.25rem; padding: 10px 20px;">
                                        <i class="fa-solid fa-trash-can"></i> Vider le Panier
                                    </button>
                                </form>
                            </div>

                            <!-- Centered Text -->
                            <h3 style="text-align: center; margin-top: 50px;">PAYES TA CAM GANGSTER</h3>

                            <!-- Centered PayPal Button -->
                            <div style="text-align: center; margin-top: 50px;padding-left: 250px;">
                                <div id="paypal-button-container"></div>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <p style="text-align: center; font-size: 1.5rem;">Votre panier est vide.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"></script>

    <!-- PayPal JS SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=AWA59fffyY5gMozpT3Nz-w1cTJEZPteyeRy1Gw6iWD5lGHN0cN1GSXJc-Y6wYmBt_qSy2JCFCDXCyC0R&buyer-country=US&currency=USD&components=buttons&enable-funding=venmo"></script>

    <!-- PayPal Button Render -->
    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $totalPrix; ?>' // The total price
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);
                });
            }
        }).render('#paypal-button-container');
    </script>

</body>

</html>