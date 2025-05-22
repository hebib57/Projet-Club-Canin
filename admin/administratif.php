<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';


// Recup les messages reçus
$sql = "SELECT m.*, u.prenom_utilisateur, u.nom_utilisateur 
FROM message m
JOIN utilisateur u ON m.id_expediteur = u.id_utilisateur
WHERE m.id_destinataire = :id_utilisateur
AND m.contenu IS NOT NULL 
ORDER BY m.date_envoi DESC";

$stmt = $db->prepare($sql);
$stmt->execute([':id_utilisateur' => $_SESSION['user_id']]);
$recordset_messages = $stmt->fetchAll();

//recup le compte total des messages reçus
$stmt = $db->prepare("SELECT COUNT(*) FROM message WHERE id_destinataire = ? ");
$stmt->execute([$_SESSION['user_id']]);
$total_messages = $stmt->fetchColumn();

$query = "
    SELECT 
       c.id_dog,
       c.nom_dog,
       c.race_dog,
       c.age_dog,
       c.sexe_dog,
       u.nom_utilisateur,
       c.date_inscription

    FROM 
        chien c
        
        JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
       
    ORDER BY c.date_inscription DESC;
";


$recordset_dog = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);



$stmt = $db->prepare("SELECT * FROM cours");
$stmt->execute();
$recordset_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// recup le nombre de reservations
$stmt = $db->prepare("SELECT COUNT(*) FROM reservation");
$stmt->execute();
$total_reservations = $stmt->fetchColumn();

// recup le nombre de cours
$stmt = $db->prepare("SELECT COUNT(*) FROM cours");
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

// recup tous les évènements
$stmt = $db->prepare("SELECT * FROM evenement");
$stmt->execute();
$recordset_event = $stmt->fetchAll(PDO::FETCH_ASSOC);

// recup le nombre total des évènements
$stmt = $db->prepare("SELECT COUNT(*) FROM evenement");
$stmt->execute();
$total_event = $stmt->fetchColumn();

// recup tous les utilisateurs avec leur rôle
$sql = "SELECT * 
        FROM utilisateur u
        JOIN utilisateur_role ur ON u.id_utilisateur = ur.id_utilisateur
        JOIN role r ON ur.id_role = r.id_role";


try {
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $recordset_role = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
  $recordset_role = []; // Pour éviter d'autres erreurs en cas d'échec
}


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
    ORDER BY r.date_reservation DESC
    ";


$stmt = $db->query($query);
$recordset_reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>



<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
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
        <li><a href="../user.php">utilisateur</a></li>
      </ul>
    </nav>
    <button class="navbar__burger-menu-toggle" id="burgerMenu">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>
  </header>
  <div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé des activités du Club Canin.</h2>
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
          <div class="user-avatar">AD</div>
          <div class="user-info">
            <h3><?= hsc(ucfirst($prenom_utilisateur)) ?></h3>

          </div>
        </div>

        <ul class="menu-list">
          <li><a href="#dashbord">Tableau de bord</a></li>
          <li><a href="#reservations">Suivi des Réservations</a></li>
          <li><a href="#cours_programmé">Gestion des Cours</a></li>
          <li><a href="#users">Gestion des Utilisateurs</a></li>
          <li><a href="#coachs">Gestion des Coachs</a></li>
          <li><a href="#dogs">Gestion des Chiens</a></li>
          <li><a href="#events">Gestion des Evènements</a></li>
          <li><a href="#messagerie">Messagerie</a></li>
          <li><a href="#">Paramètres du Compte</a></li>
          <li><a href="../admin/logout.php">Déconnexion</a></li>
        </ul>
      </div>
      <section class="admin_container">
        <div id="date"> </div>
        <div class="dashbord" id="dashbord">
          <h2>Tableau de Bord</h2>
          <div class="tab_bord-card">
            <div class="card">
              <h3>Cours à venir</h3>
              <p><?= $total_cours ?> </p>
              <button class="btn">Voir les cours</button>
            </div>

            <div class="card">
              <h3>Réservations en cours</h3>
              <p><?= $total_reservations ?> </p>
              <button class="btn">Voir les réservations</button>
            </div>

            <div class="card">
              <h3>Utilisateurs Inscrits</h3>
              <p><?= $total_utilisateurs ?> </p>
              <button class="btn">Voir les utilisateurs</button>
            </div>
          </div>
          <div class="tab_bord-card">
            <div class="card">
              <h3>Chiens Inscrits</h3>
              <p><?= $total_dogs ?> </p>
              <button class="btn">Voir les chiens</button>
            </div>

            <div class="card">
              <h3>Messages reçus</h3>
              <p><?= $total_messages ?> </p>
              <button class="btn">Voir les messages</button>
            </div>

            <div class="card">
              <h3>Evenements prévus</h3>
              <p><?= $total_event ?></p>
              <button class="btn">Voir les évènements</button>
            </div>
          </div>
        </div>

        <section class="reservations" id="reservations">
          <h2>Suivi des Réservations</h2>
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
                      <!-- Option de suppression ou gestion -->
                      <form method="post" action="../reservations/delete_reservation.php" style="display: inline;">
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

        <section class="cours_programmé" id="cours_programmé">
          <h2>Gestion des Cours</h2>
          <div class="table-container">
            <?php if (isset($_GET['success'])): ?>
              <div class="alert success">
                ✅ Le cours et sa séance ont bien été ajoutés !
              </div>
            <?php elseif (isset($_GET['error'])): ?>
              <div class="alert error">
                ❌ Une erreur est survenue lors de l'ajout du cours. Veuillez réessayer.
              </div>
            <?php endif; ?>
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <!-- <th>Nom du Cours</th> -->
                  <th>Type de cours</th>
                  <th>Description du cours</th>
                  <th>Âge min</th>
                  <th>Âge max</th>
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
                      <button class="btn"><a href="../cours/form.php?id=<?= $row['id_cours'] ?>">Modifier</a></button>
                      <button class="btn"><a href="../cours/delete.php?id=<?= $row['id_cours'] ?>" onclick="return confirmationDeleteCours();">Supprimer</a></button>
                    </td>
                  </tr>
                <?php }; ?>
              </tbody>
            </table>
          </div>
          <button class="btn">
            <a href="../cours/form.php">Ajouter un Cours</a>
          </button>

        </section>

        <section class="admins" id="admins">
          <h2>Gestion des Admins</h2>
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nom</th>
                  <th>Prénom</th>
                  <th>Email</th>
                  <th>Téléphone</th>
                  <!-- <th>Rôle</th> -->
                  <th>Date d'inscription</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recordset_role as $row) { ?>

                  <?php if ($row['id_role'] == 1) { ?>
                    <tr>

                      <td><?= hsc($row['id_utilisateur']); ?></td>
                      <td><?= hsc($row['nom_utilisateur']); ?></td>
                      <td><?= hsc($row['prenom_utilisateur']); ?></td>
                      <td><?= hsc($row['admin_mail']); ?></td>
                      <td><?= hsc($row['telephone_utilisateur']); ?></td>
                      <!-- <td><?= hsc($row['nom_role']); ?></td> -->
                      <!-- <td><?= hsc($row['date_inscription']); ?></td> -->
                      <td> <?php $date = new DateTime($row['date_inscription']);
                            echo hsc($date->format('d/m/Y')); ?></td>
                      <td>
                      <td>
                        <button class="btn"><a href="../users/form.php?id=<?= $row['id_utilisateur'] ?>">Modifier</a></button>
                        <button class="btn"><a href="../users/delete.php?id=<?= $row['id_utilisateur'] ?>" onclick="return confirmationDeleteAdmin();">Supprimer</a></button>
                      </td>
                    </tr>
                  <?php }; ?>
                <?php }; ?>
              </tbody>
            </table>
          </div>
          <button class="btn">
            <a href="../users/ajouter_user.php">Ajouter un Utilisateur</a>
          </button>
        </section>

        <section class="users" id="users">
          <h2>Gestion des Utilisateurs</h2>
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nom</th>
                  <th>Prénom</th>
                  <th>Email</th>
                  <th>Téléphone</th>
                  <!-- <th>Rôle</th> -->
                  <th>Date d'inscription</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recordset_role as $row) { ?>
                  <?php if ($row['id_role'] == 3) { ?>
                    <tr>

                      <td><?= hsc($row['id_utilisateur']); ?></td>
                      <td><?= hsc($row['nom_utilisateur']); ?></td>
                      <td><?= hsc($row['prenom_utilisateur']); ?></td>
                      <td><?= hsc($row['admin_mail']); ?></td>
                      <td><?= hsc($row['telephone_utilisateur']); ?></td>
                      <!-- <td><?= hsc($row['nom_role']); ?></td> -->
                      <!-- <td><?= hsc($row['date_inscription']); ?></td> -->
                      <td> <?php $date = new DateTime($row['date_inscription']);
                            echo hsc($date->format('d/m/Y')); ?></td>
                      <td>
                        <button class="btn"><a href="../users/form.php?id=<?= $row['id_utilisateur'] ?>">Modifier</a></button>
                        <button class="btn"><a href="../users/delete.php?id=<?= $row['id_utilisateur'] ?>" onclick="return confirmationDeleteUser();">Supprimer</a></button>
                      </td>
                    </tr>
                  <?php }; ?>
                <?php }; ?>
              </tbody>
            </table>
          </div>
          <button class="btn">
            <a href="../users/ajouter_user.php">Ajouter un Utilisateur</a>
          </button>
        </section>
        <section class="coachs" id="coachs">
          <h2>Gestion des Coachs</h2>
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nom</th>
                  <th>Prénom</th>
                  <th>Email</th>
                  <th>Téléphone</th>
                  <!-- <th>Rôle</th> -->
                  <th>Date d'inscription</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recordset_role as $row) { ?>
                  <?php if ($row['id_role'] == 2) { ?>
                    <tr>

                      <td><?= hsc($row['id_utilisateur']); ?></td>
                      <td><?= hsc($row['nom_utilisateur']); ?></td>
                      <td><?= hsc($row['prenom_utilisateur']); ?></td>
                      <td><?= hsc($row['admin_mail']); ?></td>
                      <td><?= hsc($row['telephone_utilisateur']); ?></td>
                      <!-- <td><?= hsc($row['nom_role']); ?></td> -->
                      <!-- <td><?= hsc($row['date_inscription']); ?></td> -->
                      <td> <?php $date = new DateTime($row['date_inscription']);
                            echo hsc($date->format('d/m/Y')); ?></td>
                      <td>
                      <td>
                        <button class="btn"><a href="../users/form.php?id=<?= $row['id_utilisateur'] ?>">Modifier</a></button>
                        <button class="btn"><a href="../users/delete.php?id=<?= $row['id_utilisateur'] ?>" onclick="return confirmationDeleteCoach();">Supprimer</a></button>
                      </td>
                    </tr>
                  <?php }; ?>
                <?php }; ?>
              </tbody>
            </table>
          </div>
          <button class="btn">
            <a href="../users/ajouter_user.php">Ajouter un Coach</a>
          </button>
        </section>

        <section class="dogs" id="dogs">
          <h2>Gestion des Chiens</h2>
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nom du Chien</th>
                  <th>Race</th>
                  <th>Âge</th>
                  <th>Sexe</th>
                  <th>Propriétaire</th>
                  <th>Date d'inscription</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recordset_dog as $row) { ?>
                  <tr>
                    <td><?= hsc($row['id_dog']); ?></td>
                    <td><?= hsc($row['nom_dog']); ?></td>
                    <td><?= hsc($row['race_dog']); ?></td>
                    <td><?= hsc($row['age_dog']); ?></td>
                    <td><?= hsc($row['sexe_dog']); ?></td>
                    <td><?= hsc($row['nom_utilisateur']); ?></td>
                    <!-- <td><?= hsc($row['date_inscription']); ?></td> -->
                    <td> <?php $date = new DateTime($row['date_inscription']);
                          echo hsc($date->format('d/m/Y')); ?></td>
                    <td>
                    <td>
                      <button class="btn"><a href="../dogs/form.php?id=<?= $row['id_dog'] ?>">Modifier</a></button>
                      <button class="btn"><a href="../dogs/delete.php?id=<?= $row['id_dog'] ?>" onclick="return confirmationDeleteDog();">Supprimer</a></button>
                    </td>
                  </tr>
                <?php }; ?>

              </tbody>
            </table>
          </div>
          <button class="btn">
            <a href="../dogs/ajouter_dog.php">Ajouter un Chien</a>
          </button>
        </section>

        <section class="events" id="events">
          <h2>Gestion des Événements</h2>
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
                    <button class="btn"><a href="../evenement/form.php?id=<?= $row['id_event'] ?>">Modifier</a></button>
                    <button class="btn"><a href="../evenement/delete.php?id=<?= $row['id_event'] ?>" onclick="return confirmationDeleteEvent();">Supprimer</a></button>
                  </td>
                </tr>
              <?php }; ?>
            </tbody>
          </table>
          <button class="btn">
            <a href="../evenement/form.php">Ajouter un Événement</a></button>
        </section>

        <section class="card-admin_messagerie" id="messagerie">
          <h2>Messagerie</h2>
          <div class="card-header">
            <button class="btn"><a href="../messages/message_send.php">Nouveau message</a></button>
          </div>
          <div class="table-container">
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
                    <td><?= hsc($msg['prenom_utilisateur'] . ' ' . $msg['nom_utilisateur']) ?></td>
                    <td><?= substr(hsc($msg['sujet_message']), 0, 30) ?>...</td>
                    <td><?= hsc(date('d/m/Y H:i', strtotime($msg['date_envoi']))) ?></td>
                    <td>
                      <button><a class="btn" href="../messages/message_read.php?id_message=<?= $msg['id_message'] ?>">Lire</a></button>
                      <button><a class="btn" href="../messages/message_delete.php?id=<?= $msg['id_message'] ?>" onclick="return confirmationDeleteMessage();">Supprimer</a></button>
                    </td>
                    <td><?= hsc($msg['lu'] ? 'Oui' : 'Non') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>

            </table>
          </div>
        </section>
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
  <script src="./administratif.js"></script>
</body>

</html>