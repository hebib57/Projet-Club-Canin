<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';


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
        co.nom_cours,
        co.categorie_acceptee
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


// recup les inscriptions aux evenements
$stmt = $db->prepare("
  SELECT 
  i.id_inscription,
  i.date_inscription,
  u.nom_utilisateur,
  d.nom_dog,
  e.nom_event
   FROM inscription_evenement i 
   JOIN utilisateur u ON i.id_utilisateur = u.id_utilisateur
   JOIN chien d ON i.id_dog = d.id_dog
   JOIN evenement e ON i.id_event = e.id_event
   ORDER BY i.date_inscription DESC;
   ");
$stmt->execute();
$recordset_inscription_event = $stmt->fetchAll(PDO::FETCH_ASSOC);






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
            <li><a href="#">Paramètres du Compte <img src="../interface_graphique/admin-panel.png" alt="parametres" width="40px
          "></a></li>
            <li><a href="../admin/logout.php">Déconnexion <img src="../interface_graphique/img-exit.png" alt="logout" width="40px
          "></a></li>
        </ul>
    </div>



    <section class="reservations" id="reservations">
        <h2>Cours réservés</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Catégorie</th>
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
                            <td><?= hsc($reserv['categorie_acceptee']); ?></td>
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





    <section class="inscriptions_event" id="inscriptions_event">
        <h2>Évènements réservés</h2>
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
                            <td><?= hsc($inscription['nom_utilisateur']); ?></td>
                            <td><?= hsc($inscription['nom_dog']); ?></td>
                            <td><?= hsc($inscription['nom_event']); ?></td>
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