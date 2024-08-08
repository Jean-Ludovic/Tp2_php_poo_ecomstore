<?php
session_start();

// Inclure les classes nécessaires
require_once 'Classes/Produits.php';
require_once 'Classes/Panier.php';
require_once 'DATABASE/Database.php';

// Connexion à la base de données
$database = new Database();
$conn = $database->getConnection();

// Initialisation des objets Produits et Panier
$produit = new Produits($conn);
$panier = new Panier();

// Récupération des produits
$products = $produit->getAllProducts();

// Dossier où les images sont stockées
$image_directory = 'assets/images/';

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Invité';
$is_admin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;

// Vérifier si un produit est ajouté au panier
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['produit_id'])) {
    if ($username != 'Invité') {
        $produitId = $_POST['produit_id'];
        foreach ($products as $product) {
            if ($product['id'] == $produitId) {
                $panier->ajouterProduit($produitId, $product['nom'], $product['prix'], $product['note']);
                break;
            }
        }
    } else {
        echo "<script>alert('Veuillez vous connecter pour ajouter des produits au panier');</script>";
    }
}

// Obtenir le nombre total d'articles dans le panier
$totalArticles = $panier->obtenirNombreTotal();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>GANGSTER ONLINE SHOP</title>
    <link rel="icon" type="image/x-icon" href="assets/icon.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#!">GANGSTER ONLINE SHOP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="./documentation/rapport.html">About</a></li>
                    <?php if ($is_admin) : ?>
                        <li class="nav-item"><a class="nav-link" href="./pages/admin/admin_dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="./pages/admin/gestion_utilisateurs.php">Manage-users</a></li>
                        <li class="nav-item"><a class="nav-link" href="./pages/admin/gestion_produits.php">Manage-Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="./pages/admin/gestion_paiements.php">Manage-Payments</a></li>
                    <?php endif; ?>
                </ul>
                <form class="d-flex">
                    <button class="btn btn-outline-dark" type="button">
                        <i class="bi-cart-fill me-1"><a href="./pages/cart/panier.php">Cart</a></i>
                        <span class="badge bg-dark text-white ms-1 rounded-pill"><?php echo $totalArticles; ?></span>
                    </button>
                </form>
                <?php if ($username == 'Invité') : ?>
                    <a href="../login-form-14/login-form-14/index.html">
                        <button class="btn btn-custom">
                            <i class="bi bi-people"></i> Login
                        </button>
                    </a>
                <?php else : ?>
                    <a href="./functions/connection/process_logout.php">
                        <button class="btn btn-custom">
                            <i class="fa-solid fa-gun" style="color: #000000;"></i> Logout
                        </button>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Shop in gang style</h1>
                <p class="lead fw-normal text-white-50 mb-0">With Ludo & Maissata</p>
                <h2 class="display-6 fw-bolder">Bienvenue, <?php echo htmlspecialchars($username); ?>!</h2>
            </div>
        </div>
    </header>
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php foreach ($products as $product) : ?>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Image du produit -->
                            <?php
                            $supported_extensions = ['webp', 'png', 'jpg', 'jpeg'];
                            $image_src = 'assets/images/default.webp';

                            foreach ($supported_extensions as $extension) {
                                $image_path = $image_directory . strtolower($product['nom']) . '.' . $extension;
                                if (file_exists($image_path)) {
                                    $image_src = $image_path;
                                    break;
                                }
                            }
                            ?>
                            <img class="card-img-top" src="<?php echo htmlspecialchars($image_src); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" />
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <h5 class="fw-bolder"><?php echo htmlspecialchars($product['nom']); ?></h5>
                                    <div class="d-flex justify-content-center small text-warning mb-2">
                                        <?php for ($i = 0; $i < $product['note']; $i++) : ?>
                                            <div class="bi-star-fill"></div>
                                        <?php endfor; ?>
                                    </div>
                                    <?php echo htmlspecialchars($product['prix']); ?>€
                                </div>
                            </div>
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center">
                                    <form method="POST" action="">
                                        <input type="hidden" name="produit_id" value="<?php echo $product['id']; ?>">
                                        <button class="btn btn-outline-dark mt-auto add-to-cart" type="submit">Add to cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Votre Site 2023</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>