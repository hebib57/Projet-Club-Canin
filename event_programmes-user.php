<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_user.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$utilisateur = [];

// recup tous les évènements
$stmt = $db->prepare("SELECT * FROM evenement");
$stmt->execute();
$recordset_event = $stmt->fetchAll(PDO::FETCH_ASSOC);

//recup chiens utilisateur
$stmt = $db->prepare("SELECT c.id_dog, c.nom_dog, c.date_naissance, r.nom_race, c.age_dog, c.photo_dog, c.sexe_dog, c.date_inscription, c.categorie
                       FROM chien AS c 
                       INNER JOIN race AS r                       
                       ON c.id_race = r.id_race  
                      
                       WHERE c.id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$dogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                <li><a href="user.php">Tableau de bord <img src="../interface_graphique/online-reservation.png" alt="dashboard" width="40px
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

        <section class="events" id="events">
            <h2>Événements programmés</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom de l'Événement</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Places disponibles</th>
                        <th>Action</th>
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
                                <form method="post" action="./inscription_event/process_inscription_event.php" style="display:inline;">
                                    <input type="hidden" name="id_event" value="<?= hsc($row['id_event']); ?>">
                                    <?php if (!in_array($row["id_event"], $utilisateur)): ?>
                                        <button type="button" class="btn" onclick="openEventModal(<?= hsc($row['id_event']) ?>)">S'inscrire</button>
                                    <?php else: ?>
                                        <button type="submit" name="action" value="desinscrire" class="btn">Se désinscrire</button>
                                    <?php endif;
                                    ?>
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

    <!-- Modal pour choisir un chien pour l'inscription à un évènement-->
    <div id="inscriptionModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeEventModal()">&times;</span>
            <h3>Choisissez un chien pour cet évènement</h3>

            <form method="post" action="../inscription_event/process_inscription_event.php">
                <input type="hidden" name="id_event" id="modal_id_event">

                <input type="hidden" name="action" value="inscrire">
                <label for="id_dog">Votre chien :</label>
                <select name="id_dog" id="id_dog_event" required>
                    <option value="">-- Sélectionner un chien --</option>

                    <?php
                    foreach ($dogs as $dog): ?>
                        <option value="<?= hsc($dog['id_dog']) ?>"><?= hsc($dog['nom_dog']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" name="action" value="inscrire" class="btn">Confirmer l'inscription</button>
                <button type="button" class="btn" onclick="closeEventModal()">Annuler</button>
            </form>
        </div>
    </div>



</body>

</html>