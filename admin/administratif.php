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