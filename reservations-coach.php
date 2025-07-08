<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_coach.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour afficher
$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';

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
    c.categorie_acceptee,
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

// recup les inscriptions aux evenements
$stmt = $db->prepare("SELECT ie.*, e.nom_event, c.nom_dog   
                      FROM inscription_evenement ie
                      JOIN evenement e ON ie.id_event = e.id_event
                      JOIN chien c ON ie.id_dog = c.id_dog");
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
            </ul>
        </nav>
        <button class="navbar__burger-menu-toggle" id="burgerMenu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </header>
    <main>
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
        <!-- <div>
                <span id="date">
                </span>
            </div> -->


        <section class="reservations" id="reservations">
            <h2> Cours Réservés</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID </th>
                            <th>Catégorie</th>
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
                            <td><?= hsc($reserv['categorie_acceptee']); ?></td>
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