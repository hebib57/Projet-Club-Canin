<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_coach.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour afficher
$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';

// recup tous les évènements
$stmt = $db->prepare("SELECT * FROM evenement");
$stmt->execute();
$recordset_event = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                            <td><?= hsc($row['places_disponibles']); ?></td>

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