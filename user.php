<?php
// session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_user.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";




// Vérifier si l'utilisateur est connecté-------------------------------------------------------------------------------
$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$utilisateur = [];



//recup nombre de cours_programme
$stmt = $db->prepare("SELECT COUNT(*) FROM cours");
$stmt->execute();
$cours_programme = $stmt->fetchColumn();


// recup le nombre de messages reçu
$stmt = $db->prepare("SELECT COUNT(*) FROM message WHERE id_destinataire = ?");
$stmt->execute([$_SESSION['user_id']]);
$nombre_message = $stmt->fetchColumn();

// recup le nombre de chiens pour l'utilisateur connecté
$stmt = $db->prepare("SELECT COUNT(*) FROM chien WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$nombre_dogs = $stmt->fetchColumn();

// recup le nombre de cours réservés pour l'utilisateur connecté
$stmt = $db->prepare("SELECT COUNT(*) FROM reservation WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$nombre_cours_reserves = $stmt->fetchColumn();

// recup le nombre d'évènement programmés
$stmt = $db->prepare("SELECT COUNT(*) FROM evenement");
$stmt->execute();
$total_event = $stmt->fetchColumn();

// recup le nombre total des évènements pour l'utilisateur connecté
$stmt = $db->prepare("SELECT COUNT(*) FROM inscription_evenement WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$total_event_user = $stmt->fetchColumn();

//recup les events auxquel ce user est inscrit

$stmt = $db->prepare("SELECT id_event FROM inscription_evenement WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$events_inscrits = $stmt->fetchAll(PDO::FETCH_COLUMN);


//----------------------------------------------------------------------------------//

if ($id_utilisateur) {

  //recup chiens utilisateur
  $stmt = $db->prepare("SELECT c.id_dog, c.nom_dog, c.date_naissance, r.nom_race, c.age_dog, c.photo_dog, c.sexe_dog, c.date_inscription, c.categorie
                       FROM chien AS c 
                       INNER JOIN race AS r                       
                       ON c.id_race = r.id_race  
                      
                       WHERE c.id_utilisateur = ?");
  $stmt->execute([$id_utilisateur]);
  $dogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Gére l'inscription/désinscription
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cours'], $_POST['action'])) {
    $id_cours = intval($_POST['id_cours']);

    // Récup la séance correspondant au cours
    $stmt = $db->prepare("SELECT id_seance FROM seance WHERE id_cours = ? LIMIT 1");
    $stmt->execute([$id_cours]);
    $id_seance = $stmt->fetchColumn();

    $stmt = $db->prepare("SELECT nom_utilisateur FROM utilisateur WHERE id_utilisateur = ?");
    $stmt->execute([$id_utilisateur]);
    $user_name = $stmt->fetchColumn();

    // Si séance trouvée, inscrire ou désinscrire l'utilisateur
    if ($id_seance) {
      if ($_POST['action'] === 'inscrire') {
        // Récup les cours déjà inscrit
        $stmt = $db->prepare("
        SELECT s.id_cours 
        FROM reservation r
        INNER JOIN seance s ON r.id_seance = s.id_seance
        WHERE r.id_utilisateur = ?
        ");
        $stmt->execute([$id_utilisateur]);
        $utilisateur = $stmt->fetchAll(PDO::FETCH_COLUMN);
        // reserver
        $stmt = $db->prepare("INSERT INTO reservation (id_utilisateur, id_seance, date_reservation) VALUES (?, ?, NOW())");
        $stmt->execute([$id_utilisateur, $id_seance]);
      } elseif ($_POST['action'] === 'desinscrire') {
        // annuler reservation
        $stmt = $db->prepare("DELETE FROM reservation WHERE id_utilisateur = ? AND id_seance = ?");
        $stmt->execute([$id_utilisateur, $id_seance]);
      }
    }


    header("Location: user.php");
    exit();
  }
}


?>



<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="custom.css" />
</head>

<body>
  <header class="header2">
    <div class="logo">
      <img src="../interface_graphique/logo-dog-removebg-preview.png" alt="logo" />
    </div>
    <nav class="navbar">
      <ul class="navbar__burger-menu--closed">
        <li><a href="../index.php">Accueil</a></li>
      </ul>
    </nav>
    <button class="navbar__burger-menu-toggle" id="burgerMenu">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>
  </header>

  <div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
  </div>
  <span id="date">
  </span>

  <main class="container_bord">

    <section class="dashbord">


      <div class="sidebar">
        <button class="sidebar__burger-menu-toggle" id="sidebarMenu">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </button>
        <div class="sidebar-header">
          <div class="user-avatar">U</div>
          <div class="user-info">
            <h3><?= hsc(ucfirst($prenom_utilisateur)) ?></h3>

          </div>
        </div>

        <ul class="menu-list">
          <li><a href="user.php#dashbord">Tableau de bord <img src="../interface_graphique/online-reservation.png" alt="dashboard" width="40px
          "></a></li>
          <li><a href="cours_programmés-user.php">Cours programmés <img src="../interface_graphique/training-program.png" alt="cours" width="40px
          "></a></li>
          <li><a href="event_programmés-user.php">Évènements programmés <img src="../interface_graphique/banner.png" alt="events" width="40px
          "></a></li>
          <li><a href="dogs-user.php">Mes chiens <img src="../interface_graphique/corgi.png" alt="dogs" width="40px
          "></a></li>
          <li><a href="reservations-user.php">Mes réservations <img src="../interface_graphique/reservation.png" alt="reservations" width="40px
          "></a></li>
          <li><a href="progression.php">Progression <img src="../interface_graphique/img-progress.png" alt="progression" width="40px
          "></a></li>
          <li><a href="messagerie-user.php">Messagerie <img src="../interface_graphique/mail.png" alt="messagerie" width="40px
          "></a></li>
          <li><a href="#">Paramètres du compte <img src="../interface_graphique/admin-panel.png" alt="parametres" width="40px
          "></a></li>
          <li><a href="./admin/logout.php">Déconnexion <img src="../interface_graphique/img-exit.png" alt="logout" width="40px
          "></a></li>
        </ul>
      </div>

      <section class="tab_bord" id="dashbord">
        <h2>Tableau de Bord</h2>
        <div class="tab_bord-card">

          <div class="suiv-card">
            <h3><?= hsc($nombre_dogs) ?></h3>
            <p>Chiens enregistrés</p>
          </div>
          <div class="suiv-card">
            <h3><?= hsc($nombre_cours_reserves) ?></h3>
            <p>Cours réservés</p>
          </div>
          <div class="suiv-card">
            <h3><?= hsc($total_event_user) ?></h3>
            <p>Évènements réservés</p>
          </div>

        </div>
        <div class="tab_bord-card">
          <div class="card">
            <h3>Cours programmés</h3>
            <p><?= hsc($cours_programme) ?> </p>
            <button class="btn">Voir les détails</button>
          </div>

          <div class="card">
            <h3>Messages reçus</h3>
            <p><?= hsc($nombre_message) ?></p>
            <button class="btn">Voir mes messages</button>
          </div>

          <div class="card">
            <h3>Évènements programmés</h3>
            <p><?= hsc($total_event) ?></p>
            <button class="btn">Voir les évènements</button>
          </div>
        </div>
      </section>













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
  <script src="user.js"></script>




</body>

</html>