<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

if (isset($_POST['admin_mail']) && isset($_POST['admin_password'])) {

  $stmt = $db->prepare("
  SELECT u.*, ur.id_role, r.nom_role
  FROM utilisateur u
  LEFT JOIN utilisateur_role ur ON u.id_utilisateur = ur.id_utilisateur
  LEFT JOIN role r ON ur.id_role = r.id_role
  WHERE u.admin_mail = :admin_mail
");

  $stmt->bindValue(':admin_mail', $_POST['admin_mail']);
  $stmt->execute();



  if ($utilisateur = $stmt->fetch(PDO::FETCH_ASSOC)) {

    if (password_verify($_POST['admin_password'], $utilisateur['admin_password'])) {

      session_start();

      $_SESSION["is_logged"] = "oui";
      $_SESSION["user_id"] = $utilisateur["id_utilisateur"];
      $_SESSION["role_id"] = $utilisateur["id_role"];
      $_SESSION["role_name"] = $utilisateur["nom_role"];
      $_SESSION["user_email"] = $utilisateur["admin_mail"];
      $_SESSION['prenom_utilisateur'] = $utilisateur['prenom_utilisateur'];


      switch ($utilisateur["id_role"]) {
        case 1:
          header("Location: /admin/administratif.php");
          break;
        case 2:
          header("Location: /coach.php");
          break;
        case 3:
          header("Location: /user.php");
          break;
        default:
          echo "Rôle inconnu. Contactez un administrateur.";
          exit;
      }


      exit();
    } else {
      echo "id ou mot de passe incorrect;";
    }
  } else {
    echo "id ou mot de passe incorrect;";
  }
}
?>




<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Se connecter</title>
  <link rel="stylesheet" href="../custom.css" />
</head>

<body>
  <header class="header2">
    <div class="logo">
      <img src="../interface_graphique/logo-dog-removebg-preview.png" alt="logo" />
    </div>
    <nav class="navbar">
      <ul class="navbar__burger-menu--closed">
        <li><a href="../index.php">Accueil</a></li>
        <li><a href="../coach.php">coach</a></li>
        <li><a href="../admin/administratif.php">administratif</a></li>
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
      <h2>Se connecter</h2>
      <form action="login.php" method="POST">
        <label for="admin_mail">Email</label>
        <input type="email" id="admin_mail" name="admin_mail" />
        <label for="admin_password">Mot de passe</label>
        <input type="password" id="admin_password" name="admin_password" />
        <button type="submit" class="button" value="ok">Se connecter
        </button>
      </form>
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