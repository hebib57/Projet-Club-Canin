 <!-- // $_SERVER['DOCUMENT_ROOT'] pour s'assurer que le chemin est correct -->
 <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
  // Vérifie si les champs 'admin_mail' et 'admin_password' ont été soumis via la méthode POST.
  if (isset($_POST['admin_mail']) && isset($_POST['admin_password'])) {

    // Prépare une requête SQL pour sélectionner l'administrateur dont l'email correspond à celui saisi.
    $stmt = $db->prepare("SELECT * FROM utilisateur WHERE admin_mail = :admin_mail");

    // Lie la valeur de l'email saisi au paramètre :admin_mail dans la requête préparée.	//bindvalue relie les 2.
    $stmt->bindValue(':admin_mail', $_POST['admin_mail']);
    // Exécute la requête SQL
    $stmt->execute();
    // Vérifie si un administrateur a été trouvée dans la base de données.
    if ($row = $stmt->fetch()) {

      // Vérifie si le mot de passe saisi correspond au mot de passe haché stocké dans la base de données.
      if (password_verify($_POST['admin_password'], $row['admin_password'])) {
        // Démarre une session. Indispensable pour maintenir l'état de connexion de l'utilisateur.		
        session_start();
        // Définit une variable de session pour indiquer que l'utilisateur est connecté.
        $_SESSION["is_logged"] = "oui";
        header("Location:administratif.php"); // permet de rediriger l'utilisateur vers la page
        exit(); // Arrête l'exécution du script après la redirection.
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
       <img src="../images/logo-dog-removebg-preview.png" alt="logo" />
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

   <section class="form-container creation">
     <h2>Créer un compte</h2>
     <form action="login.php" method="post">
       <label for="admin_mail">Email</label>
       <input type="email" id="admin_mail" name="admin_mail" />
       <label for="password">Mot de passe</label>
       <input type="password" id="admin_password" name="admin_password" />
       <button type="submit" class="button" value="ok">Se connecter
       </button>
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
   <script src="../index.js"></script>
 </body>

 </html>