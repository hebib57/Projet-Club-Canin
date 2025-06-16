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

//recup des cours
// $stmt = $db->prepare("SELECT * FROM cours");
// $stmt->execute();
// $recordset_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

//recup des cours_programme
$stmt = $db->prepare("SELECT COUNT(*) FROM cours");
$stmt->execute();
$cours_programme = $stmt->fetchColumn();

//recup des seances
$stmt = $db->prepare("SELECT s.id_seance, s.id_cours, c.nom_cours, u.nom_utilisateur, u.prenom_utilisateur, s.date_seance, s.heure_seance, s.places_disponibles, c.id_cours
                      FROM seance s 
                      LEFT JOIN cours c ON s.id_cours = c.id_cours
                      LEFT JOIN utilisateur u ON u.id_utilisateur = s.id_utilisateur
                    ");
$stmt->execute();
$recordset_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// recup le nombre de messages reçu
$stmt = $db->prepare("SELECT COUNT(*) FROM message WHERE id_destinataire = ?");
$stmt->execute([$_SESSION['user_id']]);
$nombre_message = $stmt->fetchColumn();

// $stmt = $db->prepare("INSERT INTO chien (nom_dog, race_dog, age_dog, id_utilisateur, photo_dog) VALUES (?, ?, ?, ?, ?)");
// $stmt->execute([$nom_dog, $race_dog, $age_dog, $id_utilisateur, $photo_dog]);



// Récupérer les messages reçus
$sql = "SELECT m.*, u.prenom_utilisateur, u.nom_utilisateur 
FROM message m
JOIN utilisateur u ON m.id_expediteur = u.id_utilisateur
WHERE m.id_destinataire = :id_utilisateur
AND m.contenu IS NOT NULL 
ORDER BY m.date_envoi DESC";

$stmt = $db->prepare($sql);
$stmt->execute([':id_utilisateur' => $_SESSION['user_id']]);
$recordset_messages = $stmt->fetchAll();

// recup le nombre de chiens pour l'utilisateur connecté
$stmt = $db->prepare("SELECT COUNT(*) FROM chien WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$nombre_dogs = $stmt->fetchColumn();

// recup le nombre de cours réservés pour l'utilisateur connecté
$stmt = $db->prepare("SELECT COUNT(*) FROM reservation WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$nombre_cours_reserves = $stmt->fetchColumn();

//---------------------------------------------------------------------------//
$query = "
SELECT 
        r.id_reservation,
        r.date_reservation,
        u.nom_utilisateur,
        r.id_dog,
        d.nom_dog,
        s.id_seance,
        s.date_seance,
        s.heure_seance,
        s.places_disponibles,
        s.duree_seance,
        s.statut_seance,
        co.nom_cours
    FROM 
        reservation r
        JOIN seance s ON r.id_seance = s.id_seance
        JOIN cours co ON s.id_cours = co.id_cours
        JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur
        JOIN chien d ON r.id_dog = d.id_dog
    WHERE r.id_utilisateur = ?
    ORDER BY r.date_reservation DESC
    
    ";

// Exécution de la requête
$stmt = $db->prepare($query);
$stmt->execute([$id_utilisateur]);
$recordset_reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);

// recup tous les évènements
$stmt = $db->prepare("SELECT * FROM evenement");
$stmt->execute();
$recordset_event = $stmt->fetchAll(PDO::FETCH_ASSOC);

// recup le nombre total des évènements pour l'utilisateur connecté
$stmt = $db->prepare("SELECT COUNT(*) FROM inscription_evenement WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$total_event_user = $stmt->fetchColumn();

//recup les events auxquel ce user est inscrit

$stmt = $db->prepare("SELECT id_event FROM inscription_evenement WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$events_inscrits = $stmt->fetchAll(PDO::FETCH_COLUMN);

$sql = "
SELECT 
    e.id_event,
    e.nom_event, 
    e.date_event, 
    e.heure_event, 
    e.places_disponibles,
    c.nom_dog,
    c.id_dog
FROM 
    inscription_evenement ie
JOIN 
    evenement e ON ie.id_event = e.id_event
JOIN 
    chien c ON ie.id_dog = c.id_dog
WHERE 
    c.id_utilisateur = ?
ORDER BY 
    e.date_event DESC
";
$stmt = $db->prepare($sql);
$stmt->execute([$id_utilisateur]);
$event_user_dog = $stmt->fetchAll(PDO::FETCH_ASSOC);


//----------------------------------------------------------------------------------//

if ($id_utilisateur) {

  //recup chiens utilisateur
  $stmt = $db->prepare("SELECT c.id_dog, c.nom_dog, r.nom_race, c.age_dog, c.photo_dog, c.sexe_dog, c.date_inscription, c.categorie
                       FROM chien AS c INNER JOIN race AS r ON c.id_race = r.id_race WHERE c.id_utilisateur = ?");
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
          <li><a href="#dashbord">Tableau de bord <img src="../interface_graphique/online-reservation.png" alt="dashboard" width="40px
          "></a></li>
          <li><a href="#cours_programmé">Cours programmés <img src="../interface_graphique/training-program.png" alt=cours" width="40px
          "></a></li>
          <li><a href="#events">Évènements programmés <img src="../interface_graphique/banner.png" alt="events" width="40px
          "></a></li>
          <li><a href="#dogs">Mes chiens <img src="../interface_graphique/corgi.png" alt="dogs" width="40px
          "></a></li>
          <li><a href="#">Mes réservations <img src="../interface_graphique/reservation.png" alt="reservations" width="40px
          "></a></li>
          <li><a href="#suivi">Progression <img src="../interface_graphique/img-progress.png" alt="progression" width="40px
          "></a></li>
          <li><a href="#messagerie">Messagerie <img src="../interface_graphique/mail.png" alt="messagerie" width="40px
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
            <p><?= hsc($cours_programme) ?> cours programmés </p>
            <button class="btn">Voir les détails</button>
          </div>

          <div class="card">
            <h3>Messages reçus</h3>
            <p><?= hsc($nombre_message) ?></p>
            <button class="btn">Voir mes messages</button>
          </div>

          <div class="card">
            <h3>Participants Inscrits</h3>
            <p>150 participants inscrits</p>
            <button class="btn">Voir les participants</button>
          </div>
        </div>
      </section>

      <section class="tab_bord" id="cours_programmé">
        <h2>Cours programmés</h2>
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom du Cours</th>
              <th>Nom Coach</th>
              <th>Date de séance</th>
              <th>Heure de séance</th>
              <th>Places disponibles</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recordset_cours as $row) { ?>
              <tr>

                <td><?= hsc($row['id_seance']); ?></td>
                <td><?= hsc($row['nom_cours']); ?></td>
                <td><?= hsc($row['prenom_utilisateur'] . ' ' . $row['nom_utilisateur']); ?></td>
                <td><?= hsc($row['date_seance']); ?></td>
                <td><?= hsc($row['heure_seance']); ?></td>
                <td><?= hsc($row['places_disponibles']); ?></td>

                <td>
                  <form method="post" action="./reservations/process_reservation-u.php" style="display: inline;">
                    <input type="hidden" name="id_dog" value="<?= hsc($row["id_seance"]); ?>">
                    <input type="hidden" name="id_cours" value="<?= hsc($row["id_cours"]); ?>">
                    <?php if (!in_array($row["id_cours"], $utilisateur)): ?>
                      <button type="button" class="btn" onclick="openCoursModal(<?= hsc($row['id_cours']) ?>)">S'inscrire</button>
                    <?php else: ?>
                      <button type="submit" name="action" value="desinscrire" class="btn">Se désinscrire</button>
                    <?php endif;
                    ?>

                  </form>
                </td>

              </tr>
            <?php }; ?>
          </tbody>
        </table>
      </section>

      <section class="reservations-user" id="reservations">
        <h2>Cours Réservés</h2>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Nom du chien</th>
                <th>Nom Cours</th>
                <th>Date Séance</th>
                <th>Heure Séance</th>
                <th>Date Réservation</th>
                <th>Action</th>
              </tr>
            </thead><?php foreach ($recordset_reservation as $reserv): ?>

              <tbody>
                <tr>
                  <td><?= hsc($reserv['id_reservation']); ?></td>
                  <td><?= hsc($reserv['nom_utilisateur']); ?></td>
                  <td><?= hsc($reserv['nom_dog']); ?></td>
                  <td><?= hsc($reserv['nom_cours']); ?></td>
                  <td><?= hsc($reserv['date_seance']); ?></td>
                  <td><?= hsc($reserv['heure_seance']); ?></td>
                  <td><?= hsc($reserv['date_reservation']); ?></td>
                  <td>

                    <form method="post" action="../reservations/delete_reservation.php" style="display: inline;">
                      <input type="hidden" name="id_reservation" value="<?= hsc($reserv['id_reservation']); ?>">
                      <button type="submit" class="btn" onclick=" return confirmationDelete();">Supprimer</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
          </table>
        </div>
      </section>



      <section class="events" id="events">
        <h2>Événements programmés</h2>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom de l'Événement</th>
              <th>Date</th>
              <th>Heure</th>
              <th>Places disponibles</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recordset_event as $row) { ?>
              <tr>
                <td><?= hsc($row['id_event']); ?></td>
                <td><?= hsc($row['nom_event']); ?></td>
                <td><?= hsc($row['date_event']); ?></td>
                <td><?= hsc($row['heure_event']); ?></td>
                <td><?= hsc($row['places_disponibles']); ?></td>
                <td>
                  <form method="post" action="./inscription_event/process_inscription_event.php" style="display:inline;">
                    <input type="hidden" name="id_event" value="<?= hsc($row['id_event']); ?>">
                    <?php if (!in_array($row["id_event"], $utilisateur)): ?>
                      <button type="button" class="btn" onclick="openEventModal(<?= hsc($row['id_event']) ?>)">S'inscrire</button>
                    <?php else: ?>
                      <button type="submit" name="action" value="desinscrire" class="btn">Se désinscrire</button>
                    <?php endif;
                    ?>
                  </form>
                </td>
              </tr>
            <?php }; ?>
          </tbody>
        </table>
      </section>



      <section class="events" id="events">
        <h2>Événements réservés</h2>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom de l'Événement</th>
              <th>Date</th>
              <th>Heure</th>
              <th>Places disponibles</th>
              <th>Nom du chien</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($event_user_dog as $row) { ?>
              <tr>
                <td><?= hsc($row['id_event']); ?></td>
                <td><?= hsc($row['nom_event']); ?></td>
                <td><?= hsc($row['date_event']); ?></td>
                <td><?= hsc($row['heure_event']); ?></td>
                <td><?= hsc($row['places_disponibles']); ?></td>
                <td><?= hsc($row['nom_dog']); ?></td>
                <td>
                  <form method="post" action="./inscription_event/process_inscription_event.php" onsubmit="return confirmDesinscriptionEvent();" style="display:inline;">
                    <input type="hidden" name="id_event" value="<?= hsc($row['id_event']); ?>">
                    <input type="hidden" name="id_dog" value="<?= hsc($row['id_dog']); ?>">
                    <input type="hidden" name="action" value="desinscrire">
                    <button type="submit" class="btn">Se désinscrire</button>
                  </form>
                </td>
              </tr>
            <?php }; ?>
          </tbody>
        </table>
      </section>






      <section class="content">
        <div class="contenair" id="dogs">
          <h2>Mes chiens</h2>
          <div class="card">
            <div class="card-header">
              <h3>Mes chiens</h3>
              <a href="../dogs/form.php" class="btn">+ Ajouter un chien</a>
            </div>
            <div class="card-body">
              <ul class="dog-list">
                <?php foreach ($dogs as $dog): ?>
                  <li class="dog-item">
                    <div class="dog-avatar">
                      <img src=" <?= "../upload/xs_" . hsc($dog['photo_dog']) ?>" alt="photo chien">
                    </div>
                    <div class="dog-info">
                      <h4><?= hsc($dog['nom_dog']) ?></h4>
                      <p>Catégorie : <?= hsc($dog['categorie']) ?></p>
                      <p>Râce : <?= hsc($dog['nom_race']) ?></p>
                      <p>Age : <?= hsc($dog['age_dog']) ?> mois</p>
                      <p>Sexe : <?= hsc($dog['sexe_dog']) ?></p>
                    </div>
                    <div class="dog-actions">
                      <button class="btn btn-details"
                        data-categorie="<?= hsc($dog['categorie']) ?>"
                        data-nom="<?= hsc($dog['nom_dog']) ?>"
                        data-race="<?= hsc($dog['nom_race']) ?>"
                        data-age="<?= hsc($dog['age_dog']) ?>"
                        data-sexe="<?= hsc($dog['sexe_dog']) ?>"
                        data-photo="<?= hsc($dog['photo_dog']) ?>"
                        data-date_inscription="<?= hsc($dog['date_inscription']) ?>">
                        Détails
                      </button>
                      <button class="btn"><a href="../dogs/form.php?id=<?= hsc($dog['id_dog']) ?>">Modifier</a></button>
                      <button class="btn"><a href="../dogs/delete.php?id=<?= hsc($dog['id_dog']) ?>" onclick="return confirmationDeleteDog();">Supprimer</a></button>
                    </div>
                  </li>

                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
        <div id="dogModal" class="modal_dog" style="display: none;">
          <div class="modal_detail">
            <span class="close">X</span>
            <img id="modal_photo-dog" alt="chien" style="max-width: 100%;">
            <h4 id="modal-nom"></h4>
            <p><strong>Race :</strong> <span id="modal-race"></span></p>
            <p><strong>Âge :</strong> <span id="modal-age"></span> mois</p>
            <p><strong>Sexe :</strong> <span id="modal-sexe"></span></p>
            <p><strong>Date d'inscription :</strong> <span id="modal-date_inscription"></span></p>
            <p><strong>Catégorie :</strong> <span id="modal-categorie"></span></p>

          </div>

        </div>
      </section>
      <section class="suivi" id="suivi">

        <h2>Suivi et Progression</h2>
        <div class="selection">
          <label for="dog-select">Sélectionner un chien :</label>
          <select name="id_dog" id="id_dog_user" required>
            <option value="">-- Sélectionner un chien --</option>
            <?php foreach ($dogs as $dog): ?>
              <option
                value="<?= hsc($dog['id_dog']) ?>"
                data-nom="<?= hsc($dog['nom_dog']) ?>"
                data-race="<?= hsc($dog['nom_race']) ?>"
                data-categorie="<?= hsc($dog['categorie']) ?>"
                data-age="<?= hsc($dog['age_dog']) ?>"
                data-date="<?= hsc($dog['date_inscription']) ?>">
                <?= hsc($dog['nom_dog']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div id="dog-info" class="dog-info" style="display: none; margin-top: 20px;">
          <h3>Informations sur le chien</h3>
          <p><strong>Nom :</strong><span id="info-nom"></span></p>
          <p><strong>Catégorie :</strong><span id="info-categorie"></span></p>
          <p><strong>Râce :</strong><span id="info-race"></span></p>
          <p><strong>Age :</strong><span id="info-age"></span></p>
          <p><strong>derniere :</strong><span id="info-date"></span></p>

        </div>



        <div class="progress">
          <h3>Suivi des progrès</h3>
          <table class="progress-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Type de cours</th>
                <th>Progrès</th>
                <th>Commentaires</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1er mars 2025</td>
                <td>Éducation de base</td>
                <td>Bon progrès</td>
                <td>Toto a bien réagi aux exercices de base</td>
              </tr>
              <tr>
                <td>5 mars 2025</td>
                <td>Sociabilisation</td>
                <td>Progrès modérés</td>
                <td>Toto a montré de l'amélioration avec d'autres chiens</td>
              </tr>
              <tr>
                <td>10 mars 2025</td>
                <td>Parcours sportif</td>
                <td>Excellent progrès</td>
                <td>Toto a bien maîtrisé le parcours sportif</td>
              </tr>
            </tbody>
          </table>
        </div>

      </section>
      <section class="card-user_messagerie" id="messagerie">

        <div>
          <h2>Messagerie</h2>
          <button><a href="../messages/message_send.php" class="btn">Nouveau message</a></button>
        </div>

        <table>
          <thead>
            <tr>
              <th>De</th>
              <th>Sujet</th>
              <th>Date</th>
              <th>Actions</th>
              <th>lu</th>
            </tr>
          </thead>
          <tbody>

            <?php foreach ($recordset_messages as $msg): ?>
              <tr>
                <td><?= hsc($msg['prenom_utilisateur'] . ' ' . hsc($msg['nom_utilisateur'])) ?></td>
                <td><?= substr(hsc($msg['sujet_message']), 0, 30) ?>...</td>
                <td><?= hsc(date('d/m/Y H:i', strtotime(hsc($msg['date_envoi'])))) ?></td>
                <td>
                  <button><a class="btn" href="../messages/message_read.php?id_message=<?= hsc($msg['id_message']) ?>">Lire</a></button>
                  <button><a class="btn" href="../messages/message_delete.php?id=<?= hsc($msg['id_message']) ?>" onclick="return confirmationDeleteMessage();">Supprimer</a></button>
                </td>
                <td><?= hsc($msg['lu'] ? 'Oui' : 'Non') ?></td>
              </tr>
            <?php endforeach; ?>


          </tbody>
        </table>

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
  <!-- Modal pour choisir un chien pour la réservation d'un cours-->
  <div id="reservationModal" class="modal" style="display: none;">
    <div class="modal-content">
      <span class="close" onclick="closeCoursModal()">&times;</span>
      <h3>Choisissez un chien pour ce cours</h3>

      <form method="post" action="../reservations/process_reservation-u.php">
        <input type="hidden" name="id_cours" id="modal_id_cours">

        <input type="hidden" name="action" value="inscrire">
        <label for="id_dog">Votre chien :</label>
        <select name="id_dog" id="id_dog_reservation" required>
          <option value="">-- Sélectionner un chien --</option>

          <?php
          foreach ($dogs as $dog): ?>
            <option value="<?= hsc($dog['id_dog']) ?>"><?= hsc($dog['nom_dog']) ?></option>
          <?php endforeach; ?>
        </select>

        <button type="submit" name="action" value="inscrire" class="btn">Confirmer l'inscription</button>
        <button type="button" class="btn" onclick="closeCoursModal()">Annuler</button>
      </form>
    </div>
  </div>

  <!-- Modal pour choisir un chien pour l'inscription à un évènement-->
  <div id="inscriptionModal" class="modal" style="display: none;">
    <div class="modal-content">
      <span class="close" onclick="closeEventModal()">&times;</span>
      <h3>Choisissez un chien pour cet évènement</h3>

      <form method="post" action="../inscription_event/process_inscription_event.php">
        <input type="hidden" name="id_event" id="modal_id_event">

        <input type="hidden" name="action" value="inscrire">
        <label for="id_dog">Votre chien :</label>
        <select name="id_dog" id="id_dog_event" required>
          <option value="">-- Sélectionner un chien --</option>

          <?php
          foreach ($dogs as $dog): ?>
            <option value="<?= hsc($dog['id_dog']) ?>"><?= hsc($dog['nom_dog']) ?></option>
          <?php endforeach; ?>
        </select>

        <button type="submit" name="action" value="inscrire" class="btn">Confirmer l'inscription</button>
        <button type="button" class="btn" onclick="closeEventModal()">Annuler</button>
      </form>
    </div>
  </div>

</body>

</html>