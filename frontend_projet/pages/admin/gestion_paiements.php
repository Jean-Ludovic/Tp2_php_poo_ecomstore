<?php
session_start();

// Inclure les classes nécessaires
require_once '../../DATABASE/Database.php';

// Vérifiez que l'utilisateur est administrateur
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die("Accès non autorisé");
}

// Connexion à la base de données
$database = new Database();
$conn = $database->getConnection();

// Requête pour récupérer tous les paiements
$sql_payments = "SELECT id, utilisateur_id, montant, date_paiement, statut FROM paiements";
$stmt_payments = $conn->prepare($sql_payments);
$stmt_payments->execute();
$payments = $stmt_payments->fetchAll(PDO::FETCH_ASSOC);
if (!$payments) {
    $payments = []; // Assurez-vous que $payments est un tableau vide si aucune donnée n'est récupérée
}

$conn = null; // Fermer la connexion
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Paiements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .table-container {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="../../index.php" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i> Retour
        </a>

        <h1 class="mt-5">Gestion des Paiements</h1>

        <!-- Tableau des paiements -->
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID Utilisateur</th>
                        <th>Montant</th>
                        <th>Date de Paiement</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($payments)) : ?>
                        <?php foreach ($payments as $payment) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['id']); ?></td>
                                <td><?php echo htmlspecialchars($payment['utilisateur_id']); ?></td>
                                <td><?php echo htmlspecialchars($payment['montant']); ?>€</td>
                                <td><?php echo htmlspecialchars($payment['date_paiement']); ?></td>
                                <td><?php echo htmlspecialchars($payment['statut']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5">Aucun paiement trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>