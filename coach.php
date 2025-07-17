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

require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';


?>





<div class="title">
  <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
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




<?php require_once __DIR__ . '/templates/footer.php' ?>