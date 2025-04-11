<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";


$stmt = $db->prepare("SELECT * FROM cours");
$stmt->execute();
$recordset3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
      <img src="./interface_graphique/logo-dog-removebg-preview.png" alt="logo" />
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
    <h2>Bienvenue Coach, voici le résumé de vos activités au Club.</h2>
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
            <h3>Francky</h3>
            <p>Coach</p>
          </div>
        </div>

        <ul class="menu-list">
          <li><a href="#dashbord">Tableau de bord</a></li>
          <li><a href="#cours_programmé">Gestion des Cours</a></li>
          <li><a href="#reservations">Suivi des réservations</a></li>
          <li><a href="#eval">Evaluation</a></li>
          <li><a href="#messagerie">Messagerie</a></li>
          <li><a href="#">Paramètres du compte</a></li>
          <li><a href="#">Déconnexion</a></li>
        </ul>
      </div>

      <section class="container-coach" id="dashbord">
        <div class="card-coach">
          <div>
            <h2>Tableau de bord</h2>
            <div>
              <span id="current-date">Mardi 18 mars 2025</span>
            </div>
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

          <section id="cours_programmé">
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
                <?php foreach ($recordset3 as $row) { ?>
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
                      <button class="btn"><a href="../cours/delete.php?id=<?= $row['id_cours'] ?>">Supprimer</a></button>
                    </td>
                  </tr>
                <?php }; ?>
              </tbody>
            </table>
            <button class="btn">
              <a href="../ajouter_cours.php">Ajouter un Cours</a></button>
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