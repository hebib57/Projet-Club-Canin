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
    <!-- <main class="container_bord">
        <section class="dashbord"> -->
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
                                <button><a class="btn" href="../messages/message_read.php?id_message=<?= hsc($msg['id_message']) ?>">Lire</a></button>
                                <button><a class="btn" href="../messages/message_delete.php?id=<?= hsc($msg['id_message']) ?>" onclick="return confirmationDeleteMessage();">Supprimer</a></button>
                            </td>
                            <td><?= hsc($msg['lu'] ? 'Oui' : 'Non') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </section>
    </section>








    <!-- </section>
    </main> -->
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