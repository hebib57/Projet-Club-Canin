<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

// $total = 0; //déclarer la variable à 0, si je ne rentre pas dans le if
$stmt = $db->prepare("SELECT * FROM utilisateur");

$stmt->execute();
$recordset = $stmt->fetchAll(PDO::FETCH_ASSOC); //tableau indéxé qui contient des tableaux associatifs*/

$stmt = $db->prepare("SELECT * FROM chien");
$stmt->execute();
$recordset2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
      <img src="../images/logo-dog-removebg-preview.png" alt="logo" />
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
    <h2>Bienvenue Admin, voici le résumé des activités du Club Canin.</h2>
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
            <h3>Admin</h3>
            <p>Administrateur</p>
          </div>
        </div>

        <ul class="menu-list">
          <li><a href="#dashbord">Tableau de bord</a></li>
          <li><a href="#reservations">Suivi des Réservations</a></li>
          <li><a href="#cours_programmés">Gestion des Cours</a></li>
          <li><a href="#users">Gestion des Utilisateurs</a></li>
          <li><a href="#coachs">Gestion des Coachs</a></li>
          <li><a href="#dogs">Gestion des Chiens</a></li>
          <li><a href="#events">Gestion des Evènements</a></li>
          <li><a href="#messagerie">Messagerie</a></li>
          <li><a href="#">Paramètres du Compte</a></li>
          <li><a href="./logout.php">Déconnexion</a></li>
        </ul>
      </div>
      <section class="admin_container">
        <div class="dashbord" id="dashbord">
          <h2>Tableau de Bord</h2>
          <div class="tab_bord-card">
            <div class="card">
              <h3>Cours à venir</h3>
              <p>5 cours programmés cette semaine</p>
              <button class="btn">Voir les détails</button>
            </div>

            <div class="card">
              <h3>Réservations en cours</h3>
              <p>20 réservations effectuées</p>
              <button class="btn">Voir les réservations</button>
            </div>

            <div class="card">
              <h3>Participants Inscrits</h3>
              <p>150 participants inscrits</p>
              <button class="btn">Voir les participants</button>
            </div>
          </div>
          <div class="tab_bord-card">
            <div class="card">
              <h3>Cours à venir</h3>
              <p>5 cours programmés cette semaine</p>
              <button class="btn">Voir les détails</button>
            </div>

            <div class="card">
              <h3>Réservations en cours</h3>
              <p>20 réservations effectuées</p>
              <button class="btn">Voir les réservations</button>
            </div>

            <div class="card">
              <h3>Participants Inscrits</h3>
              <p>150 participants inscrits</p>
              <button class="btn">Voir les participants</button>
            </div>
          </div>
        </div>

        <section class="reservations" id="reservations">
          <h2>Suivi des Réservations</h2>
          <table>
            <thead>
              <tr>
                <th>ID Réservation</th>
                <th>Utilisateur</th>
                <th>Cours</th>
                <th>Chien</th>
                <th>Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>toto</td>
                <td>École du Chiot</td>
                <td>luna</td>
                <td>10/04/2025</td>
                <td>Réservé</td>
              </tr>
              <tr>
                <td>2</td>
                <td>tete</td>
                <td>Éducation</td>
                <td>luna</td>
                <td>12/04/2025</td>
                <td>En attente</td>
              </tr>
            </tbody>
          </table>
        </section>

        <section class="cours_programmé" id="cours_programmés">
          <h2>Gestion des Cours</h2>
          <table class="table">
            <thead>
              <tr>
                <th>Nom du Cours</th>
                <th>Date</th>
                <th>Nombre de places disponibles</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Éducation de base</td>
                <td>1er mars 2025</td>
                <td>10</td>
                <td>
                  <button class="btn">Modifier</button>
                  <button class="btn">Supprimer</button>
                </td>
              </tr>
              <tr>
                <td>Sociabilisation</td>
                <td>2 mars 2025</td>
                <td>12</td>
                <td>
                  <button class="btn">Modifier</button>
                  <button class="btn">Supprimer</button>
                </td>
              </tr>
              <tr>
                <td>Parcours sportif</td>
                <td>3 mars 2025</td>
                <td>8</td>
                <td>
                  <button class="btn">Modifier</button>
                  <button class="btn">Supprimer</button>
                </td>
              </tr>
            </tbody>
          </table>
          <button class="btn">
            <a href="../ajouter_cours.php">Ajouter un Cours</a>
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
                  <th>Rôle</th>
                  <th>Date d'inscription</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recordset as $row) { ?>
                  <tr>
                    <td><?= hsc($row['id_utilisateur']); ?></td>
                    <td><?= hsc($row['nom_utilisateur']); ?></td>
                    <td><?= hsc($row['prenom_utilisateur']); ?></td>
                    <td><?= hsc($row['admin_mail']); ?></td>
                    <td><?= hsc($row['telephone_utilisateur']); ?></td>
                    <td><?= hsc($row['role']); ?></td>
                    <td><?= hsc($row['date_inscription']); ?></td>
                    <td>
                      <button class="btn"><a href="../users/form.php?id=<?= $row['id_utilisateur'] ?>">Modifier</a></button>
                      <button class="btn"><a href="../users/delete.php?id=<?= $row['id_utilisateur'] ?>">Supprimer</a></button>
                    </td>
                  </tr>
                <?php }; ?>

              </tbody>
            </table>
          </div>
          <button class="btn">
            <a href="../ajouter_user.php">Ajouter un Utilisateur</a>
          </button>
        </section>
        <section class="coachs" id="coachs">
          <h2>Gestion des Coachs</h2>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Nom du Coach</th>
                <th>Email</th>
                <th>Spécialité</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>tutu</td>
                <td>tutu@email.com</td>
                <td>Dressage, Agilité</td>
                <td>
                  <button class="btn">Modifier</button>
                  <button class="btn">Supprimer</button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>tete</td>
                <td>tete@email.com</td>
                <td>Éducation, Sociabilisation</td>
                <td>
                  <button class="btn">Modifier</button>
                  <button class="btn">Supprimer</button>
                </td>
              </tr>
            </tbody>
          </table>
          <button class="btn">
            <a href="../ajouter_coach.php">Ajouter un Coach</a>
          </button>
        </section>

        <section class="dogs" id="dogs">
          <h2>Gestion des Chiens</h2>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Nom du Chien</th>
                <th>Race</th>
                <th>Âge</th>
                <th>Sexe</th>
                <th>Propriétaire</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recordset2 as $row) { ?>
                <tr>
                  <td><?= hsc($row['id_dog']); ?></td>
                  <td><?= hsc($row['nom_dog']); ?></td>
                  <td><?= hsc($row['race_dog']); ?></td>
                  <td><?= hsc($row['age_dog']); ?></td>
                  <td><?= hsc($row['sexe_dog']); ?></td>
                  <td><?= hsc($row['proprietaire_dog']); ?></td>
                  <td>
                    <button class="btn"><a href="../users/form.php?id=<?= $row['id_dog'] ?>">Modifier</a></button>
                    <button class="btn"><a href="../dogs/delete.php?id=<?= $row['id_dog'] ?>">Supprimer</a></button>
                  </td>
                </tr>
              <?php }; ?>

            </tbody>
          </table>
          <button class="btn">
            <a href="../ajouter_dog.php">Ajouter un Chien</a>
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
                <th>Places disponibles</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Concours Agility</td>
                <td>25/03/2025</td>
                <td>30</td>
                <td>
                  <button class="btn">Modifier</button>
                  <button class="btn">Supprimer</button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Formation Dressage</td>
                <td>28/03/2025</td>
                <td>15</td>
                <td>
                  <button class="btn">Modifier</button>
                  <button class="btn">Supprimer</button>
                </td>
              </tr>
            </tbody>
          </table>
          <button class="btn">Ajouter un Événement</button>
        </section>

        <section class="card-admin_messagerie" id="messagerie">
          <h2>Messagerie</h2>
          <div class="card-header">
            <button class="btn">Nouveau message</button>
          </div>

          <table>
            <thead>
              <tr>
                <th>De</th>
                <th>Sujet</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Abdel</td>
                <td>Planification des cours pour avril</td>
                <td>17/03/2025</td>
                <td>
                  <button class="btn">Lire</button>
                </td>
              </tr>
              <tr>
                <td>Toto Titi</td>
                <td>Question sur le progrès de Luna</td>
                <td>16/03/2025</td>
                <td>
                  <button class="btn">Lire</button>
                </td>
              </tr>
            </tbody>
          </table>
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
          <img src="./images/logo-dog-removebg-preview.png" alt="Educa dog" />
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