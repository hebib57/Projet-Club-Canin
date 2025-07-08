<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
?>



<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter un utilisateur</title>
  <link rel="stylesheet" href="../custom.css" />
</head>

<body>
  <header class="header2">
    <div class="logo">
      <img src="../interface_graphique/logo-dog-removebg-preview.png" alt="logo" />
    </div>
    <nav class="navbar">
      <ul class="navbar__burger-menu--closed">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="login.php">Se connecter</a></li>
        <li><a href="reservation.html">Réservation</a></li>
        <li><a href="suivi.html">Suivi </a></li>
        <li><a href="evenement.html">Evènements</a></li>
      </ul>
    </nav>
    <button class="navbar__burger-menu-toggle" id="burgerMenu">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>
  </header>
  <main>
    <section class="form-container creation">
      <h2>Créer un compte utilisateur</h2>
      <form action="../users/process.php" method="POST">
        <label for="nom_utilisateur">Nom</label>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur" required>
        <label for="prenom_utilisateur">Prénom</label>
        <input type="text" id="prenom_utilisateur" name="prenom_utilisateur" required />
        <label for="admin_mail">Email</label>
        <input type="email" id="admin_mail" name="admin_mail" required />
        <label for="password">Mot de passe</label>
        <input type="password" id="admin_password" name="admin_password" required />
        <label for="telephone_utilisateur">Téléphone</label>
        <input type="number" id="telephone_utilisateur" name="telephone_utilisateur" required />
        <label for="text">Rôle</label>
        <select name="id_role" required>
          <option value="1">Admin</option>
          <option value="2">Coach</option>
          <option value="3">Utilisateur</option>
        </select>
        <button type="submit">Ajouter ce compte utilisateur</button>
      </form>
      <?php
      switch ($_SESSION['role_name']) {
        case 'admin':
          $redirectUrl = '../admin/administratif.php#users';
          break;
        case 'coach':
          $redirectUrl = '../coach.php#users';
          break;
        case 'utilisateur':
          $redirectUrl = '../user.php#cusers';
          break;
        default:
          $redirectUrl = '../index.php';
      }
      ?>
      <button class="btn2__modif">
        <a href="<?= $redirectUrl ?>">Retour</a>
      </button>
    </section>
  </main>
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
              src="./images/logo-dog-removebg-preview.png"
              alt="Educa dog" />
          </div>
        </div>
      </div>
      <p>
        Copyright &copy; - 2025 Club CANIN "Educa Dog"- Tous droits réservés.
      </p>
    </section>
  </footer>
  <script src="./index.js"></script>
</body>

</html>