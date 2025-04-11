<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
?>




<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Coach</title>
  <link rel="stylesheet" href="custom.css" />
</head>

<body>
  <header class="header2">
    <div class="logo">
      <img src="./interface_graphique/logo-dog-removebg-preview.png" alt="logo" />
    </div>
    <nav class="navbar">
      <ul class="navbar__burger-menu--closed">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="user.php">utilisateur</a></li>
        <li><a href="./admin/administratif.php">Admin</a></li>
        <li><a href="coach.php">Coach</a></li>
      </ul>
    </nav>
    <button class="navbar__burger-menu-toggle" id="burgerMenu">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>
  </header>
  <section class="form-container creation">
    <h2>Ajouter un compte Coach</h2>
    <form action="./coach/create.php" method="POST">
      <label for="nom_coach">Nom</label>
      <input
        type="text"
        id="nom_coach"
        name="nom_coach"
        placeholder="Entrez le nom du Coach"
        required />
      <label for="prenom_coach">Prénom</label>
      <input type="text" id="prenom_coach" name="prenom_coach" placeholder="Entrez le prénom du Coach" required />
      <label for="email_coach">Email</label>
      <input type="email" id="email_coach" name="email_coach" placeholder="Entrez l'email du Coach" required />

      <label for="">Date d'inscription</label>
      <input
        type="date"
        id="date_inscription"
        name="date_inscription"
        placeholder=""
        required />

      </div>
      <button type="submit" class="button">Ajouter ce compte Coach</button>
    </form>
  </section>
  <footer>
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
          <div class="footer-info">
            Adresse : 86 rue aux arenes, 57000 Metz
          </div>
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
            <img
              src="./interface_graphique/logo-dog-removebg-preview.png"
              alt="Educa dog" />
          </div>
        </div>
      </div>
      <p>
        Copyright &copy; - 2025 Club CANIN "Educa Dog"- Tous droits réservés.
      </p>
    </section>
  </footer>
  <script src="../index.js"></script>
</body>

</html>