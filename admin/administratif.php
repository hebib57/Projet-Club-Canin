<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';




//recup le compte total des messages reçus
$stmt = $db->prepare("SELECT COUNT(*) FROM message WHERE id_destinataire = ? ");
$stmt->execute([$_SESSION['user_id']]);
$total_messages = $stmt->fetchColumn();






// recup le nombre de reservations
$stmt = $db->prepare("SELECT COUNT(*) FROM reservation");
$stmt->execute();
$total_reservations = $stmt->fetchColumn();

// recup le nombre de cours(seance)
$stmt = $db->prepare("SELECT COUNT(*) FROM seance");
$stmt->execute();
$total_cours = $stmt->fetchColumn();

//recup le nombre total d'utilisateur
$stmt = $db->prepare("SELECT COUNT(*) FROM utilisateur");
$stmt->execute();
$total_utilisateurs = $stmt->fetchColumn();

//recup le nombre total de chiens
$stmt = $db->prepare("SELECT COUNT(*) FROM chien");
$stmt->execute();
$total_dogs = $stmt->fetchColumn();



// recup le nombre total des évènements
$stmt = $db->prepare("SELECT COUNT(*) FROM evenement");
$stmt->execute();
$total_event = $stmt->fetchColumn();



require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/sidebar.php';

?>




<div class="title">
  <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé des activités du Club Canin.</h2>
</div>

<!-- <div class="sidebar">
  <button class="sidebar__burger-menu-toggle" id="sidebarMenu">
    <span class="bar"></span>
    <span class="bar"></span>
    <span class="bar"></span>
  </button>
  <div class="sidebar-header">
    <div class="user-avatar">AD</div>
    <div class="user-info">
     

    </div>
  </div>

  <ul class="menu-list">
    <li><a href="administratif.php">Tableau de bord <img src="../interface_graphique/online-reservation.png" alt="dashboard" width="40px
          "></a></li>
    <li><a href="reservations-admin.php">Suivi des Réservations <img src="../interface_graphique/reservation.png" alt="reservations" width="40px
          "></a></li>
    <li><a href="cours_programmes-admin.php">Gestion des Cours <img src="../interface_graphique/training-program.png" alt="cours" width="40px
          "></a></li>
    <li><a href="users-admin.php">Gestion des Utilisateurs<img src="../interface_graphique/add.png" alt="users" width="40px
          "></a></li>
    <li><a href="#coachs">Gestion des Coachs <img src="../interface_graphique/coach.png" alt="coachs" width="40px
          "></a></li>
    <li><a href="dogs-admin.php">Gestion des Chiens <img src="../interface_graphique/corgi.png" alt="dogs" width="40px
          "></a></li>
    <li><a href="events_programmes-admin.php">Gestion des Evènements <img src="../interface_graphique/banner.png" alt="events" width="40px
          "></a></li>
    <li><a href="messagerie-admin.php">Messagerie <img src="../interface_graphique/mail.png" alt="messagerie" width="40px
          "></a></li>
    <li><a href="parameters_count-admin.php">Paramètres du Compte <img src="../interface_graphique/admin-panel.png" alt="parametres" width="40px
          "></a></li>
    <li><a href="../logout.php">Déconnexion <img src="../interface_graphique/img-exit.png" alt="logout" width="40px
          "></a></li>
  </ul>
</div> -->
<section class="admin_container">
  <div id="date"> </div>
  <div class="dashbord" id="dashbord">
    <h2>Tableau de Bord</h2>
    <div class="tab_bord-card">
      <div class="card">
        <h3>Cours à venir</h3>
        <p><?= hsc($total_cours) ?> </p>
        <a href="cours_programmes-admin.php" class="btn">Voir les cours</a>
      </div>

      <div class="card">
        <h3>Réservations en cours</h3>
        <p><?= hsc($total_reservations) ?> </p>
        <a href="reservations-admin.php#reservations" class="btn">Voir les réservations</a>

      </div>

      <div class="card">
        <h3>Utilisateurs Inscrits</h3>
        <p><?= hsc($total_utilisateurs) ?> </p>
        <a href="users-admin.php" class="btn">Voir les utilisateurs</a>
      </div>
    </div>
    <div class="tab_bord-card">
      <div class="card">
        <h3>Chiens Inscrits</h3>
        <p><?= hsc($total_dogs) ?> </p>
        <a href="dogs-admin.php" class="btn">Voir les chiens</a>
      </div>

      <div class="card">
        <h3>Messages reçus</h3>
        <p><?= hsc($total_messages) ?> </p>
        <a href="messagerie-admin.php" class="btn">Voir mes messages</a>
      </div>

      <div class="card">
        <h3>Evenements prévus</h3>
        <p><?= hsc($total_event) ?></p>
        <a href="events_programmes-admin.php" class="btn">Voir les évènements</a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../templates/footer.php' ?>