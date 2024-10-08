<!doctype html>
<html lang="fr">

<head>
    <title>Connexion Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <style>
        .btn-custom {
            background-color: black;
            border: 2px solid rgb(0, 47, 255);
            color: white;
            padding: 10px 20px;
            font-size: 15px;
            cursor: pointer;
            box-shadow: none !important;
        }

        .btn-custom:hover,
        .btn-custom:active,
        .btn-custom:focus {
            background: transparent;
            color: rgb(85, 0, 255);
            outline: none;
        }

        .link-custom {
            color: rgb(0, 47, 255);
        }

        .link-custom:hover {
            color: rgb(0, 47, 255);
        }

        .checkbox-wrap.checkbox-custom input[type="checkbox"]+.checkmark {
            background-color: black;
            border: 2px solid rgb(0, 47, 255);
        }

        .checkbox-wrap.checkbox-custom input[type="checkbox"]:checked+.checkmark:after {
            border: solid white;
        }
    </style>
</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">HEY ADMIN</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="wrap d-md-flex">
                        <div class="img" style="background-image: url('images/admin.webp');"></div>
                        <div class="login-wrap p-4 p-md-5">
                            <div class="col-md-14 text-right">
                                <a class="link-custom" href="index.html">as user</a>
                            </div>
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4">Sign In</h3>
                                </div>
                            </div>
                            <form id="adminLoginForm" class="signin-form" method="post" action="../../frontend_projet/functions/connection/login.admin.process.php">
                                <div class="form-group mb-3">
                                    <label class="label" for="username">Username</label>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-custom rounded submit px-3">Sign In</button>
                                </div>
                                <div class="form-group d-md-flex">
                                    <div class="w-50 text-left">
                                        <label class="checkbox-wrap checkbox-custom mb-0">Remember Me
                                            <input type="checkbox" checked>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </form>
                            <p class="text-center">Not a member? <a class="link-custom" href="signup.html">Sign Up</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>