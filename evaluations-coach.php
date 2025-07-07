<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_coach.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour afficher
$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';

$stmt = $db->prepare("
        SELECT c.*, u.prenom_utilisateur, u.nom_utilisateur, d.nom_dog
        FROM commentaire c
        JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
        JOIN chien d ON c.id_dog = d.id_dog
        
        ORDER BY c.date_commentaire DESC
      ");
$stmt->execute();
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM evenement ");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

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



    <section id="commentaires" class="commentaires">
        <h2>Commentaires de progression</h2>
        <table>
            <button class="btn"><a href="../commentaire/commentaire_send.php">Nouvelle évaluation</a></button>
            <thead>
                <tr>
                    <th>Chien</th>
                    <th>Utilisateur</th>
                    <th>Cours</th>
                    <th>Note</th>
                    <th>Date</th>
                    <th>Commentaire</th>
                    <th>Progrès</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($commentaires as $commentaire) { ?>
                    <tr>
                        <td><?= hsc($commentaire['nom_dog']); ?></td>
                        <td><?= hsc($commentaire['prenom_utilisateur']); ?></td>
                        <td><?= hsc($commentaire['nom_cours']); ?></td>
                        <td><?= hsc($commentaire['note']); ?></td>
                        <td><?= hsc(date('d/m/Y', strtotime($commentaire['date_commentaire']))); ?></td>
                        <td><?= hsc($commentaire['commentaire']); ?></td>
                        <td><?= hsc($commentaire['progres']); ?></td>

                        <td>
                            <button class="btn"><a href="../commentaire/form.php?id=<?= hsc($commentaire['id_commentaire']) ?>">Modifier</a></button>
                            <button class="btn"><a href="../commentaire/commentaire_delete.php?id=<?= hsc($commentaire['id_commentaire']) ?>" onclick="return confirmationDeleteCommentaire();">Supprimer</a></button>
                        </td>
                    </tr>
                <?php }; ?>
            </tbody>
        </table>
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
    <script src="./coach.js"></script>
</body>

</html>