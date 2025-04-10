<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
?>




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
            <img src="./images/logo-dog-removebg-preview.png" alt="logo" />
        </div>
        <nav class="navbar">
            <ul class="navbar__burger-menu--closed">
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="../coach.php">coach</a></li>
                <li><a href="../user.php">utilisateur</a></li>
            </ul>
        </nav>
        <button class="navbar__burger-menu-toggle" id="burgerMenu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </header>

    <!-- <section>
        <form action="login.php" method="post">
            <label for="admin_mail">Email</label>
            <input type="email" id="admin_mail" name="admin_mail" required />
            <label for="password">Mot de passe</label>
            <input type="password" id="admin_password" name="admin_password" />
            <button type="submit" class="button" value="ok">Se connecter</button>
        </form>
    </section> -->




    <section class="form-container creation">
        <h2>Ajouter un cours</h2>
        <form action="./cours/create.php" method="POST">
            <label for="type_cours">Type de cours</label>
            <select id="type_cours" name="type_cours">
                <option value="ecole du chiot">Ecole du chiot</option>
                <option value="education canine">Ecole canine</option>
                <option value="agilite">Agilité</option>
                <option value="pistage">Pistage</option>
                <option value="flyball">Flyball</option>
                <option value="protection-defense">Protection & Défense</option>
            </select>
            <label for="nom_cours">Nom du cours</label>
            <input type="text" id="nom_cours" name="nom_cours" required />
            <label for="description_cours">Description du cours</label>
            <input type="text" id="description_cours" name="description_cours" required>
            <label for="age_min">Âge minimum requis(en mois) </label>
            <input type="number" id="age_min" name="age_min" required>
            <label for="age_max">Âge maximum requis(en mois)</label>
            <input type="number" id="age_max" name="age_max" required>
            <label for="race_dog">Race</label>
            <input type="text" id="race_dog" name="race_dog" required />
            <label for="sexe_dog">Sexe :</label>
            <select id="sexe_dog" name="sexe_dog">
                <option value="male">Mâle</option>
                <option value="femelle">Femelle</option>
                <option value="mixte"></option>
            </select>
            <label for="date_cours">Date du cours</label>
            <input type="date" id="date_cours" name="date_cours" required />
            <label for="place_max">Nombre de places disponibles</label>
            <input type="place" id="place_max" name="place_max" required />

            <button type="submit">Ajouter ce cours</button>
        </form>
    </section>

    <section class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3 class="footer-title">Coordonnées</h3>
                <div class="footer-info">Club Canin "Educa Dog"</div>
                <div class="footer-info">Téléphone : 03-87-30-30-30</div>
                <div class="footer-info">
                    Email:
                    <a href="">toto@gmail.com</a>
                </div>
                <div class="footer-info">Adresse : 86 rue aux arenes, 57000 Metz</div>
            </div>
            <div class="footer-section">
                <h3 class="footer-title">Plan du site</h3>
                <div class="footer-info"><a href="#accueil">Accueil</a></div>
                <div class="footer-info">
                    <a href="inscription.html">S'inscrire</a>
                </div>
                <div class="footer-info">
                    <a href="utilisateur.html">Mon compte</a>
                </div>
                <div class="footer-info">
                    <a href="#nos_horaires">Horaires</a>
                </div>
                <div class="footer-info">
                    <a href="#nous_trouver">Nous trouver</a>
                </div>
                <div class="footer-info">
                    <a href="#story">Notre histoire</a>
                </div>
                <div class="footer-info">
                    <a href="#nos_activite">Nos Activités</a>
                </div>
            </div>
            <div class="footer-section">
                <h3 class="footer-title">Mentions légales</h3>
                <div class="footer-info">
                    <a href="#">Politique de confidentialité</a>
                </div>
                <div class="footer-info"><a href="#">Mentions légales</a></div>
            </div>
            <div class="footer-section">
                <h3 class="footer-title">Club Canin "Educa Dog"</h3>
                <div class="logo-container">
                    <img src="./images/logo-dog-removebg-preview.png" alt="Educa dog" />
                </div>
            </div>
        </div>
        <p>
            Copyright &copy; - 2025 Club CANIN "Educa Dog"- Tous droits réservés.
        </p>
    </section>
    <script src="../index.js"></script>
</body>

</html>