<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_user.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$utilisateur = [];

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
    WHERE r.id_utilisateur = ?
    ORDER BY r.date_reservation DESC
    
    ";

// Exécution de la requête
$stmt = $db->prepare($query);
$stmt->execute([$id_utilisateur]);
$recordset_reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);

//--------------------------------------------------------------------//
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
                <li><a href="./admin/logout.php">Déconnexion</a></li>
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
        <!-- <span id="date">
    </span> -->



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
                <li><a href="user.php#dashbord">Tableau de bord <img src="../interface_graphique/online-reservation.png" alt="dashboard" width="40px
          "></a></li>
                <li><a href="cours_programmes-user.php">Cours programmés <img src="../interface_graphique/training-program.png" alt="cours" width="40px
          "></a></li>
                <li><a href="event_programmes-user.php">Évènements programmés <img src="../interface_graphique/banner.png" alt="events" width="40px
          "></a></li>
                <li><a href="dogs-user.php">Mes chiens <img src="../interface_graphique/corgi.png" alt="dogs" width="40px
          "></a></li>
                <li><a href="reservations-user.php">Mes réservations <img src="../interface_graphique/reservation.png" alt="reservations" width="40px
          "></a></li>
                <li><a href="progression.php">Progression <img src="../interface_graphique/img-progress.png" alt="progression" width="40px
          "></a></li>
                <li><a href="messagerie-user.php">Messagerie <img src="../interface_graphique/mail.png" alt="messagerie" width="40px
          "></a></li>
                <li><a href="#">Paramètres du compte <img src="../interface_graphique/admin-panel.png" alt="parametres" width="40px
          "></a></li>
                <li><a href="./admin/logout.php">Déconnexion <img src="../interface_graphique/img-exit.png" alt="logout" width="40px
          "></a></li>
            </ul>
        </div>



        <section class="reservations" id="reservations">
            <h2>Cours Réservés</h2>
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





        <section class="inscriptions_event" id="events">
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
                    <div class="footer-info"><a href="../index.php">Accueil</a></div>
                    <div class="footer-info">
                        <a href="#nos_activite">Nos Activités</a>
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
                        <a href="#nous_contacter">Nous contacter</a>
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
</body>

</html>