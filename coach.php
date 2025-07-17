<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_coach.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour afficher

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';













$stmt = $db->prepare("
        SELECT c.*, u.prenom_utilisateur, u.nom_utilisateur, d.nom_dog 
        FROM commentaire c
        JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
        JOIN chien d ON c.id_dog = d.id_dog
      
        ORDER BY c.date_commentaire DESC
      ");
$stmt->execute();
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/header.php'

?>





<div class="title">
  <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
</div>


<div class="sidebar">
  <button class="sidebar__burger-menu-toggle" id="sidebarMenu">
    <span class="bar"></span>
    <span class="bar"></span>
    <span class="bar"></span>
  </button>
  <div class="sidebar-header">
    <div class="user-avatar">C</div>
    <div class="user-info">
      <h3><?= hsc(ucfirst($prenom_utilisateur)) ?></h3>
    </div>
  </div>

  <ul class="menu-list">
    <li><a href="coach.php">Tableau de bord <img src="../interface_graphique/online-reservation.png" alt="dashboard" width="40px
          "></a></li>
    <li><a href="cours_programmes-coach.php">Gestion des Cours <img src="../interface_graphique/training-program.png" alt="cours" width="40px
          "></a></li>
    <li><a href="event_programmes-coach.php">Gestion des Évènements <img src="../interface_graphique/banner.png" alt="events" width="40px
          "></a></li>
    <li><a href="reservations-coach.php">Suivi des réservations <img src="../interface_graphique/reservation.png" alt="reservations" width="40px
          "></a></li>
    <li><a href="evaluations-coach.php">Evaluation <img src="../interface_graphique/img-eval.png" alt="evaluations" width="40px
          "></a></li>
    <li><a href="messagerie-coach.php">Messagerie <img src="../interface_graphique/mail.png" alt="messagerie" width="40px
          "></a></li>
    <li><a href="#">Paramètres du compte <img src="../interface_graphique/admin-panel.png" alt="parametres" width="40px
          "></a></li>
    <li><a href="./admin/logout.php">Déconnexion <img src="../interface_graphique/img-exit.png" alt="logout" width="40px
          "></a></li>
  </ul>
</div>
<div>
  <span id="date">
  </span>
</div>
<!-- <section class="container-coach" id="dashbord"> -->

<section class="card-coach">
  <div>
    <h2>Tableau de bord</h2>

  </div>
  <div class="notification">
    <strong>Rappel :</strong> Vous avez 3 séances aujourd'hui et 2
    évaluations à terminer.
  </div>
  <section id="rdv" class="rdv active">
    <div class="sessions-grid">
      <div class="session-card">
        <div class="session-header">
          École du chiot <span class="stage complet">Complet</span>
        </div>
        <div class="session-detail">
          <strong>Horaire :</strong> 10h00 - 11h00
        </div>
        <div class="session-detail"><strong>Lieu :</strong> MNS</div>
        <div class="session-detail">
          <strong>Participants :</strong> 8/8
        </div>
        <div class="session-detail">
          <strong>Âge :</strong>
          < 5 mois
            </div>
            <button class="btn_coach">Détails</button>
        </div>

        <div class="session-card">
          <div class="session-header">
            Éducation canine
            <span class="stage dispo">6/8</span>
          </div>
          <div class="session-detail">
            <strong>Horaire :</strong> 14h00 - 15h30
          </div>
          <div class="session-detail"><strong>Lieu :</strong> MNS</div>
          <div class="session-detail">
            <strong>Participants :</strong> 6/8
          </div>
          <div class="session-detail">
            <strong>Âge :</strong> 6 mois - 1 an
          </div>
          <button class="btn_coach">Détails</button>
        </div>

        <div class="session-card">
          <div class="session-header">
            Agilité<span class="stage dispo">4/6</span>
          </div>
          <div class="session-detail">
            <strong>Horaire :</strong> 16h00 - 17h30
          </div>
          <div class="session-detail"><strong>Lieu :</strong> MNS</div>
          <div class="session-detail">
            <strong>Participants :</strong> 4/6
          </div>
          <div class="session-detail">
            <strong>Âge :</strong> > 1 an
          </div>
          <button class="btn_coach">Détails</button>
        </div>
      </div>
  </section>
</section>




<?php require_once __DIR__ . '/footer.php' ?>