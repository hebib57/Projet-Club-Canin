<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_coach.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour afficher

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';


// ercup les messages reçus
$sql = "SELECT m.*, u.prenom_utilisateur, u.nom_utilisateur 
FROM message m
JOIN utilisateur u ON m.id_expediteur = u.id_utilisateur
WHERE m.id_destinataire = :id_utilisateur
AND m.contenu IS NOT NULL 
ORDER BY m.date_envoi DESC";

$stmt = $db->prepare($sql);
$stmt->execute([':id_utilisateur' => $_SESSION['user_id']]);
$recordset_messages = $stmt->fetchAll();


$stmt = $db->prepare("SELECT * FROM cours");
$stmt->execute();
$recordset_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);


$query = "
SELECT 
    r.id_reservation,
    r.date_reservation,

    u.id_utilisateur,
    u.nom_utilisateur, 

    d.id_dog,
    d.nom_dog,             

    s.id_seance,
    s.date_seance,
    s.heure_seance,
    s.places_disponibles,
    s.duree_seance,
    s.statut_seance,

    c.type_cours,
    c.nom_cours,

    coach.nom_utilisateur AS nom_coach

FROM reservation r
INNER JOIN seance s ON r.id_seance = s.id_seance
INNER JOIN cours c ON s.id_cours = c.id_cours
INNER JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur
INNER JOIN chien d ON r.id_dog = d.id_dog

-- Coach assigné à la séance (optionnel si tu as un coach pour chaque séance)
LEFT JOIN utilisateur coach ON coach.id_utilisateur = s.id_utilisateur
LEFT JOIN utilisateur_role ur ON coach.id_utilisateur = ur.id_utilisateur
LEFT JOIN role role_coach ON ur.id_role = role_coach.id_role AND role_coach.nom_role = 'coach'

ORDER BY r.date_reservation DESC;
";

$stmt = $db->query($query);
$recordset_reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);

// recup tous les évènements
$stmt = $db->prepare("SELECT * FROM evenement");
$stmt->execute();
$recordset_event = $stmt->fetchAll(PDO::FETCH_ASSOC);

// recup les inscriptions aux evenements
$stmt = $db->prepare("SELECT * FROM inscription_evenement");
$stmt->execute();
$recordset_inscription_event = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        <li><a href="user.php">utilisateur</a></li>
        <li><a href="./admin/administratif.php">administratif</a></li>
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

  <main class="container_bord">

    <section class="dashbord">
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
          <li><a href="#dashbord">Tableau de bord <img src="../interface_graphique/online-reservation.png" alt="dashboard" width="40px
          "></a></li>
          <li><a href="#cours_programmé">Gestion des Cours <img src="../interface_graphique/training-program.png" alt="cours" width="40px
          "></a></li>
          <li><a href="#events">Gestion des Évènements <img src="../interface_graphique/banner.png" alt="events" width="40px
          "></a></li>
          <li><a href="#reservations">Suivi des réservations <img src="../interface_graphique/reservation.png" alt="reservations" width="40px
          "></a></li>
          <li><a href="#eval">Evaluation <img src="../interface_graphique/img-eval.png" alt="evaluations" width="40px
          "></a></li>
          <li><a href="#messagerie">Messagerie <img src="../interface_graphique/mail.png" alt="messagerie" width="40px
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
      <section class="container-coach" id="dashbord">

        <div class="card-coach">
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

          <section class="reservations" id="reservations">
            <h2>Suivi des Réservations</h2>
            <div class="table-container">
              <table>
                <thead>
                  <tr>
                    <th>ID Réservation</th>
                    <th>Nom du coach</th>
                    <th>Utilisateur</th>
                    <th>Type de cours</th>
                    <th>Nom Cours</th>
                    <th>Nom du chien</th>
                    <th>Date Séance</th>
                    <th>Heure Séance</th>
                    <th>Date Réservation</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>

                  <?php foreach ($recordset_reservation as $reserv): ?>

                    <td><?= hsc($reserv['id_reservation']); ?></td>
                    <td><?= hsc($reserv['nom_coach']); ?></td>
                    <td><?= hsc($reserv['nom_utilisateur']); ?></td>
                    <td><?= hsc($reserv['type_cours']); ?></td>
                    <td><?= hsc($reserv['nom_cours']); ?></td>
                    <td><?= hsc($reserv['nom_dog']); ?></td>
                    <td><?= hsc($reserv['date_seance']); ?></td>
                    <td><?= hsc($reserv['heure_seance']); ?></td>
                    <td><?= hsc($reserv['date_reservation']); ?></td>
                    <td>

                      <form method="post" action="./reservations/delete_reservation.php" style="display: inline;">
                        <input type="hidden" name="id_reservation" value="<?= hsc($reserv['id_reservation']); ?>">
                        <button type="submit" class="btn" onclick=" return confirmationDeleteReservation();">Supprimer</button>
                      </form>
                    </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </section>

          <section id="cours_programmé" class="cours_programmé">
            <h2>Gestion des Cours</h2>
            <table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <!-- <th>Nom du Cours</th> -->
                  <th>Type de cours</th>
                  <th>Description du cours</th>
                  <th>Âge mini</th>
                  <th>Âge maxi</th>
                  <th>Race</th>
                  <th>Sexe</th>
                  <th>Places disponibles</th>
                  <th>Date prévue</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recordset_cours as $row) { ?>
                  <tr>
                    <td><?= hsc($row['id_cours']); ?></td>
                    <!-- <td><?= hsc($row['nom_cours']); ?></td> -->
                    <td><?= hsc($row['type_cours']); ?></td>
                    <td><?= hsc($row['description_cours']); ?></td>
                    <td><?= hsc($row['age_min']); ?></td>
                    <td><?= hsc($row['age_max']); ?></td>
                    <td><?= hsc($row['race_dog']); ?></td>
                    <td><?= hsc($row['sexe_dog']); ?></td>
                    <td><?= hsc($row['place_max']); ?></td>
                    <td><?= hsc($row['date_cours']); ?></td>
                    <td>
                      <button class="btn"><a href="../cours/form.php?id=<?= hsc($row['id_cours']) ?>">Modifier</a></button>
                      <button class="btn"><a href="../cours/delete.php?id=<?= hsc($row['id_cours']) ?>">Supprimer</a></button>
                    </td>
                  </tr>
                <?php }; ?>
              </tbody>
            </table>
            <button class="btn">
              <a href="../ajouter_cours.php">Ajouter un Cours</a></button>
          </section>


          <section class="inscriptions_event" id="inscriptions_event">
            <h2>Suivi des Inscriptions Évènements</h2>
            <div class="table-container">
              <table>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Nom du chien</th>
                    <th>Nom Évènement</th>
                    <!-- <th>Date Séance</th>
                  <th>Heure Séance</th> -->
                    <th>Date Inscription</th>
                    <th>Action</th>
                  </tr>
                </thead><?php foreach ($recordset_inscription_event as $inscription): ?>

                  <tbody>
                    <tr>
                      <td><?= hsc($inscription['id_inscription']); ?></td>
                      <td><?= hsc($inscription['id_utilisateur']); ?></td>
                      <td><?= hsc($inscription['id_dog']); ?></td>
                      <td><?= hsc($inscription['id_event']); ?></td>

                      <td><?= hsc($inscription['date_inscription']); ?></td>
                      <td>
                        <!-- Option de suppression ou gestion -->
                        <form method="post" action="../inscription_event/delete_inscription_event.php" style="display: inline;">
                          <input type="hidden" name="id_inscription" value="<?= hsc($inscription['id_inscription']); ?>">
                          <button type="submit" class="btn" onclick=" return confirmationDeleteInscription();">Supprimer</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  </tbody>
              </table>
            </div>
          </section>

          <section class="events" id="events">
            <h2>Gestion des Évènements</h2>
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
                    <td><?= hsc($row['place_max']); ?></td>

                    <td>
                      <button class="btn"><a href="../evenement/form.php?id=<?= hsc($row['id_event']) ?>">Modifier</a></button>
                      <button class="btn"><a href="../evenement/delete.php?id=<?= hsc($row['id_event']) ?>" onclick="return confirmationDeleteEvent();">Supprimer</a></button>
                    </td>
                  </tr>
                <?php }; ?>
              </tbody>
            </table>
            <button class="btn">
              <a href="../evenement/form.php">Ajouter un Événement</a></button>
          </section>



          <section class="card-coach2" id="eval">
            <div>
              <h2>Évaluations en attente</h2>
              <button class="btn">Nouvelle évaluation</button>
            </div>

            <table>
              <thead>
                <tr>
                  <th>Chien</th>
                  <th>Propriétaire</th>
                  <th>Cours</th>
                  <th>Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="dog-profile">
                      <div class="dog-avatar"></div>
                      <div>Luna (Staffie)</div>
                    </div>
                  </td>
                  <td>Abdel</td>
                  <td>Éducation niveau 1</td>
                  <td>15/03/2025</td>
                  <td>
                    <button class="btn">Évaluer</button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="dog-profile">
                      <div class="dog-avatar"></div>
                      <div>Luna (Staffie)</div>
                    </div>
                  </td>
                  <td>Yannick</td>
                  <td>École du chiot</td>
                  <td>17/03/2025</td>
                  <td>
                    <button class="btn">Évaluer</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </section>

          <section class="card-coach_messagerie" id="messagerie">
            <div>
              <h2>Messagerie</h2>
              <div class="card-header">
                <button class="btn"><a href="../messages/message_send.php">Nouveau message</a></button>
                <button><a class="btn" href="../messages/inbox.php">boite de réception</a></button>

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
        </div>
      </section>
    </section>
  </main>

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
        <div class="footer-info">Adresse : 86 rue aux arenes, 57000 Metz</div>
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
          <img src="./interface_graphique/logo-dog-removebg-preview.png" alt="Educa dog" />
        </div>
      </div>
    </div>
    <p>
      Copyright &copy; - 2025 Club CANIN "Educa Dog"- Tous droits réservés.
    </p>
  </section>
  <script src="./coach.js"></script>
</body>

</html>