<?php
session_start();

// Inclure les classes nécessaires
require_once '../../Classes/Panier.php';

// Initialisation des objets
$panier = new Panier();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Invité';

// Vérifier si une mise à jour ou une suppression est demandée
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['produit_id']) && isset($_POST['action'])) {
        $produitId = $_POST['produit_id'];
        if ($_POST['action'] == 'update' && isset($_POST['quantite'])) {
            $quantite = $_POST['quantite'];
            $panier->mettreAJourQuantite($produitId, $quantite);
        } elseif ($_POST['action'] == 'delete') {
            $panier->supprimerProduit($produitId);
        }
    }
}

// Obtenir les produits dans le panier
$items = $panier->obtenirPanier();
$totalPrix = 0;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-custom {
            background-color: #343a40;
            color: #ffffff;
        }

        .btn-custom:hover {
            background-color: #23272b;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Votre Panier</h2>
        <h3>Utilisateur: <?php echo htmlspecialchars($username); ?></h3>
        <div class="row">
            <div class="col-12">
                <?php if (!empty($items)) : ?>
                    <?php foreach ($items as $produitId => $details) : ?>
                        <?php
                        $totalPrix += $details['prix'] * $details['quantite'];
                        ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="../../assets/images/<?php echo htmlspecialchars($details['nom']); ?>.webp" class="img-fluid rounded-3" alt="<?php echo htmlspecialchars($details['nom']); ?>">
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
                                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                        </form>
                                        <form method="POST" action="">
                                            <input type="hidden" name="produit_id" value="<?php echo $produitId; ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="card">
                        <div class="card-body">
                            <h5>Total: <?php echo $totalPrix; ?>€</h5>
                            <form method="POST" action="process_payment.php">
                                <button type="submit" class="btn btn-success">Procéder au paiement</button>
                            </form>

                            <!-- PayPal Button Container -->
                            <div id="paypal-button-container"></div>

                        </div>
                    </div>
                <?php else : ?>
                    <p>Votre panier est vide.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

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
                    // Show a success message to the buyer
                    alert('Transaction completed by ' + details.payer.name.given_name);
                    // Redirect or process order further
                });
            }
        }).render('#paypal-button-container');
    </script>

</body>

</html>