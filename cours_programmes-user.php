<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_user.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

// Vérifier si l'utilisateur est connecté
$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$utilisateur = [];

//recup des seances
$stmt = $db->prepare("SELECT s.id_seance, s.id_cours, c.nom_cours, u.nom_utilisateur, u.prenom_utilisateur, s.date_seance, s.heure_seance, s.places_disponibles, c.id_cours, c.categorie_acceptee
                      FROM seance s 
                      LEFT JOIN cours c ON s.id_cours = c.id_cours
                      LEFT JOIN utilisateur u ON u.id_utilisateur = s.id_utilisateur
                    ");
$stmt->execute();
$recordset_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

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



        <section class="cours_programmé" id="cours_programmé">
            <h2>Cours programmés</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Catégorie</th>
                        <th>Nom du Cours</th>
                        <th>Nom Coach</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Places disponibles</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recordset_cours as $row) { ?>
                        <tr>

                            <td><?= hsc($row['id_seance']); ?></td>
                            <td><?= hsc($row['categorie_acceptee']); ?></td>
                            <td><?= hsc($row['nom_cours']); ?></td>
                            <td><?= hsc($row['prenom_utilisateur'] . ' ' . $row['nom_utilisateur']); ?></td>
                            <td><?= hsc($row['date_seance']); ?></td>
                            <td><?= hsc($row['heure_seance']); ?></td>
                            <td><?= hsc($row['places_disponibles']); ?></td>

                            <td>
                                <form method="post" action="reservations/process_reservation-u.php" style="display: inline;">
                                    <input type="hidden" name="id_dog" value="<?= hsc($row["id_seance"]); ?>">
                                    <input type="hidden" name="id_cours" value="<?= hsc($row["id_cours"]); ?>">
                                    <?php if (!in_array($row["id_cours"], $utilisateur)): ?>
                                        <button type="button" class="btn" onclick="openCoursModal(<?= hsc($row['id_cours']) ?>)">S'inscrire</button>
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

    <!-- Modal pour choisir un chien pour la réservation d'un cours-->
    <div id="reservationModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeCoursModal()">&times;</span>
            <h3>Choisissez un chien pour ce cours</h3>

            <form method="post" action="../reservations/process_reservation-u.php">
                <input type="hidden" name="id_cours" id="modal_id_cours">

                <input type="hidden" name="action" value="inscrire">
                <label for="id_dog">Votre chien :</label>
                <select name="id_dog" id="id_dog_reservation" required>
                    <option value="">-- Sélectionner un chien --</option>

                    <?php
                    foreach ($dogs as $dog): ?>
                        <option value="<?= hsc($dog['id_dog']) ?>"><?= hsc($dog['nom_dog']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" name="action" value="inscrire" class="btn">Confirmer l'inscription</button>
                <button type="button" class="btn" onclick="closeCoursModal()">Annuler</button>
            </form>
        </div>
    </div>

</body>

</html>