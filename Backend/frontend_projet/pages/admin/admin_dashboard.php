<?php
session_start();

// Vérifiez que l'utilisateur est administrateur
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die("Accès non autorisé");
}

require_once '../../DATABASE/Database.php';
require_once '../../Classes/Produits.php';
require_once '../../Classes/User.php';

// Connexion à la base de données
$database = new Database();
$conn = $database->getConnection();

// Initialisation des objets Produits et User
$produit = new Produits($conn);
$user = new User($conn);

// Récupération des utilisateurs
$users = $user->getAllUsers();
if (!$users) {
    $users = []; // Assurez-vous que $users est un tableau vide si aucune donnée n'est récupérée
}

// Récupération des produits
$products = $produit->getAllProducts();
if (!$products) {
    $products = []; // Assurez-vous que $products est un tableau vide si aucune donnée n'est récupérée
}

// Requête pour récupérer tous les paiements
$sql_payments = "SELECT id, utilisateur_id, montant, date_paiement, statut FROM paiements";
$stmt_payments = $conn->prepare($sql_payments);
$stmt_payments->execute();
$payments = $stmt_payments->fetchAll(PDO::FETCH_ASSOC);
if (!$payments) {
    $payments = []; // Assurez-vous que $payments est un tableau vide si aucune donnée n'est récupérée
}

// Fermer explicitement l'objet PDO en le mettant à null
$conn = null;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Tableau de Bord - GANGSATASHIT PROJET</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .d-flex {
            display: flex;
            min-height: 100vh;
        }

        .app-sidebar {
            width: 250px;
            background-color: #343a40;
            min-height: 100vh;
            padding-top: 20px;
            flex-shrink: 0;
        }

        .app-sidebar .app-menu__item {
            color: #fff;
            padding: 15px;
            display: block;
            text-decoration: none;
            cursor: pointer;
        }

        .app-sidebar .app-menu__item:hover {
            background-color: #495057;
        }

        .app-sidebar input[type="radio"] {
            display: none;
        }

        .app-content {
            flex-grow: 1;
            padding: 20px;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        footer {
            width: 100%;
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: relative;
        }

        /* CSS pour montrer et cacher les sections selon le bouton radio sélectionné */
        .sections>.section {
            display: none;
        }

        #dashboard-radio:checked~.app-content .sections #dashboard,
        #gestion-utilisateurs-radio:checked~.app-content .sections #gestion-utilisateurs,
        #gestion-produits-radio:checked~.app-content .sections #gestion-produits,
        #gestion-commandes-radio:checked~.app-content .sections #gestion-commandes,
        #gestion-paiements-radio:checked~.app-content .sections #gestion-paiements {
            display: block;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#!">GANGSTER ONLINE SHOP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link" href="../../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../documentation/rapport.html">About</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="d-flex">
        <aside class="app-sidebar">
            <ul class="app-menu">
                <li>
                    <input type="radio" name="section" id="dashboard-radio" checked>
                    <label class="app-menu__item" for="dashboard-radio">
                        <i class="app-menu__icon fa fa-dashboard"></i>
                        <span class="app-menu__label">Dashboard</span>
                    </label>
                </li>
                <li>
                    <input type="radio" name="section" id="gestion-utilisateurs-radio">
                    <label class="app-menu__item" for="gestion-utilisateurs-radio">
                        <i class="app-menu__icon fa fa-users"></i>
                        <span class="app-menu__label">Gestion Utilisateurs</span>
                    </label>
                </li>
                <li>
                    <input type="radio" name="section" id="gestion-produits-radio">
                    <label class="app-menu__item" for="gestion-produits-radio">
                        <i class="app-menu__icon fa fa-product-hunt"></i>
                        <span class="app-menu__label">Gestion Produits</span>
                    </label>
                </li>
                <li>
                    <input type="radio" name="section" id="gestion-commandes-radio">
                    <label class="app-menu__item" for="gestion-commandes-radio">
                        <i class="app-menu__icon fa fa-shopping-cart"></i>
                        <span class="app-menu__label">Gestion Commandes</span>
                    </label>
                </li>
                <li>
                    <input type="radio" name="section" id="gestion-paiements-radio">
                    <label class="app-menu__item" for="gestion-paiements-radio">
                        <i class="app-menu__icon fa fa-credit-card"></i>
                        <span class="app-menu__label">Gestion Paiements</span>
                    </label>
                </li>
            </ul>
        </aside>
        <main class="app-content">
            <div class="sections">
                <!-- Section Dashboard -->
                <div class="section" id="dashboard">
                    <h2>Dashboard</h2>
                    <p>Bienvenue sur le tableau de bord !</p>
                </div>

                <!-- Section Gestion Utilisateurs -->
                <div class="section" id="gestion-utilisateurs">
                    <div class="table-container">
                        <h2>Gestion Utilisateurs</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom d'utilisateur</th>
                                    <th>Email</th>
                                    <th>Date de Création</th>
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
                                                <button class="btn btn-warning" onclick="updateUser(<?php echo $user['id']; ?>, this)">Modifier</button>
                                                <button class="btn btn-danger" onclick="deleteUser(<?php echo $user['id']; ?>)">Supprimer</button>
                                            </td>
                                        </tr>
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

                <!-- Section Gestion Produits -->
                <div class="section" id="gestion-produits">
                    <div class="table-container">
                        <h2>Gestion Produits</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Prix</th>
                                    <th>Image</th>
                                    <th>Note</th>
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
                                            <td><img src="/e_comerce_2/frontend/frontend_projet/assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" style="width:50px; height:auto;"></td>
                                            <td contenteditable="true"><?php echo htmlspecialchars($product['note']); ?></td>
                                            <td>
                                                <button class="btn btn-warning" onclick="updateProduct(<?php echo $product['id']; ?>, this)">Modifier</button>
                                                <button class="btn btn-danger" onclick="deleteProduct(<?php echo $product['id']; ?>)">Supprimer</button>
                                            </td>
                                        </tr>
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

                <!-- Section Gestion Commandes -->
                <div class="section" id="gestion-commandes">
                    <div class="table-container">
                        <h2>Gestion Commandes</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Prix</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td contenteditable='true'>Produit A</td>
                                    <td contenteditable='true'>2</td>
                                    <td contenteditable='true'>200€</td>
                                    <td>
                                        <button class='btn btn-warning' onclick='updateOrder(1, this)'>Modifier</button>
                                        <button class='btn btn-danger' onclick='deleteOrder(1)'>Supprimer</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section Gestion Paiements -->
                <div class="section" id="gestion-paiements">
                    <div class="table-container">
                        <h2>Gestion Paiements</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ID Utilisateur</th>
                                    <th>Montant</th>
                                    <th>Date de Paiement</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($payments)) : ?>
                                    <?php foreach ($payments as $payment) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($payment['id']); ?></td>
                                            <td contenteditable="true"><?php echo htmlspecialchars($payment['utilisateur_id']); ?></td>
                                            <td contenteditable="true"><?php echo htmlspecialchars($payment['montant']); ?>€</td>
                                            <td><?php echo htmlspecialchars($payment['date_paiement']); ?></td>
                                            <td contenteditable="true"><?php echo htmlspecialchars($payment['statut']); ?></td>
                                            <td>
                                                <button class="btn btn-warning" onclick="updatePayment(<?php echo $payment['id']; ?>, this)">Modifier</button>
                                                <button class="btn btn-danger" onclick="deletePayment(<?php echo $payment['id']; ?>)">Supprimer</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6">Aucun paiement trouvé.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <footer class="py-5 bg-dark mt-auto">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Votre Site 2023</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>