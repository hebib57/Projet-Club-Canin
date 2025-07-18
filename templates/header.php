<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="../custom.css" />
</head>

<body>
    <header class="header2">
        <div class="logo">
            <img src="../interface_graphique/logo-dog-removebg-preview.png" alt="logo" />
        </div>
        <nav class="navbar">
            <ul class="navbar__burger-menu--closed">
                <?php if (basename($_SERVER["SCRIPT_FILENAME"]) == 'index.php'): ?>
                    <li>
                        <?php
                        if (isset($_SESSION['is_logged']) && isset($_SESSION['role_id'])) {

                            switch ($_SESSION['role_id']) {
                                case '1':
                                    $redirectUrl = '../admin/administratif.php';
                                    break;
                                case '2':
                                    $redirectUrl = '../coach.php';
                                    break;
                                case '3':
                                    $redirectUrl = '../user.php';
                                    break;
                                default:
                                    $redirectUrl = '../index.php';
                            }
                            echo '<a href="' . $redirectUrl . '" class="button">Mon Compte</a>';
                        } else {

                            echo '<a href="./admin/inscription.php" class="button">S\'inscrire maintenant</a>';
                        }
                        ?>
                    </li>
                    <li><a href="../index.php">Accueil</a></li>
                    <li><a href="#nos_activite">Activités</a></li>
                    <li><a href="#nos_horaires">Horaires</a></li>
                    <li><a href="#story">Notre histoire</a></li>
                    <li><a href="#nous_trouver">Nous trouver</a></li>
                    <li><a href="#nous_contacter">Nous contacter</a></li>
                    <!-- <li><a href="./admin/login.php" class="button">Se connecter</a></li> -->
                    <li>
                        <?php
                        if (isset($_SESSION['is_logged']) && isset($_SESSION['role_id'])) {

                            $redirectUrl = '/logout.php';


                            echo '<a href="' . $redirectUrl . '" class="button"> Déconnexion</a>';
                        } else {

                            echo '<a href="/login.php" class="button">Se connecter</a>';
                        }
                        ?>
                    </li>
                <?php else: ?>
                    <li><a href="../index.php">Accueil</a></li>
                    <li><a href="../logout.php">Déconnexion</a></li>

                <?php endif ?>


            </ul>
        </nav>
        <button class="navbar__burger-menu-toggle" id="burgerMenu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </header>
    <main>