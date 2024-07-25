<?php
// index.php
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Invité';
$is_admin = ($username == 'admin'); // Déterminer si l'utilisateur est un administrateur
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>GANGSATASHIT PROJET</title>
    <link rel="icon" type="image/x-icon" href="assets/icon.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
                        <li class="nav-item"><a class="nav-link" href="./pages/admin/admin_dashboard.html">Dashboard</a></li>
                    <?php endif; ?>
                </ul>
                <form class="d-flex">
                    <button class="btn btn-outline-dark" type="submit">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
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
                <!-- Exemple de produit -->
                <div class="col mb-5">
                    <div class="card h-100">
                        <img class="card-img-top" src="assets/images/ak-47.webp" alt="..." />
                        <div class="card-body p-4">
                            <div class="text-center">
                                <h5 class="fw-bolder">Popular Item</h5>
                                <div class="d-flex justify-content-center small text-warning mb-2">
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                </div>
                                $40.00
                            </div>
                        </div>
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center"><a class="btn btn-outline-dark mt-auto add-to-cart" href="#">Add to cart</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Votre Site 2023</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script>
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function(event) {
                <?php if ($username == 'Invité') : ?>
                    event.preventDefault();
                    alert('Veuillez vous connecter pour commencer');
                <?php endif; ?>
            });
        });
    </script>
</body>

</html>
<style>
    /* css/styles.css */
    .btn-custom {
        background-color: #343a40;
        color: #ffffff;
    }

    .btn-custom:hover {
        background-color: #23272b;
        color: #ffffff;
    }

    /* Ajoutez d'autres styles personnalisés ici */
</style>