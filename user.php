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



//recup nombre de (seances)cours_programme
$stmt = $db->prepare("SELECT COUNT(*) FROM seance");
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

require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';
?>

<div class="title">
  <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
</div>

<span id="date">
</span>

<section class="tab_bord" id="dashbord">
  <h2>Tableau de Bord</h2>
  <div class="tab_bord-card">

    <div class="card">
      <h3><?= hsc($nombre_dogs) ?></h3>
      <p>Chiens enregistrés</p>
      <a href="dogs-user.php" class="btn">Voir mes chiens</a>
    </div>
    <div class="card">
      <h3><?= hsc($nombre_cours_reserves) ?></h3>
      <p>Cours réservés</p>
      <a href="reservations-user.php#reservations" class="btn">Voir mes cours réservés</a>
    </div>
    <div class="card">
      <h3><?= hsc($total_event_user) ?></h3>
      <p>Évènements réservés</p>
      <a href="reservations-user.php#events" class="btn">Voir mes évènements réservés</a>
    </div>

  </div>
  <div class="tab_bord-card">
    <div class="card">
      <h3>Cours programmés</h3>
      <p><?= hsc($cours_programme) ?> </p>
      <a href="cours_programmes-user.php" class="btn">Voir les détails</a>
    </div>

    <div class="card">
      <h3>Messages reçus</h3>
      <p><?= hsc($nombre_message) ?></p>
      <a href="messagerie-user.php" class="btn">Voir mes messages</a>
    </div>

    <div class="card">
      <h3>Évènements programmés</h3>
      <p><?= hsc($total_event) ?></p>
      <a href="event_programmes-user.php" class="btn">Voir les évènements</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/templates/footer.php' ?>