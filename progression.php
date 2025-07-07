<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_user.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$utilisateur = [];

//recup chiens utilisateur
$stmt = $db->prepare("SELECT c.id_dog, c.nom_dog, c.date_naissance, r.nom_race, c.age_dog, c.photo_dog, c.sexe_dog, c.date_inscription, c.categorie
                       FROM chien AS c 
                       INNER JOIN race AS r                       
                       ON c.id_race = r.id_race  

                       WHERE c.id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$dogs = $stmt->fetchAll(PDO::FETCH_ASSOC);


//recup commentaires
$stmt = $db->prepare("
        SELECT c.*, u.prenom_utilisateur, u.nom_utilisateur, d.nom_dog 
        FROM commentaire c
        JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
        JOIN chien d ON c.id_dog = d.id_dog
        
        ORDER BY c.date_commentaire DESC
      ");
$stmt->execute();
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);


$commentaire_dog = [];

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


    <section class="suivi" id="suivi">
        <h2>Suivi et Progression</h2>
        <div class="selection">
            <label for="dog-select">Sélectionner un chien :</label>
            <select name="id_dog" id="id_dog_user" required>
                <option value="">-- Sélectionner un chien --</option>
                <?php foreach ($dogs as $dog): ?>
                    <option
                        value="<?= hsc($dog['id_dog']) ?>"
                        data-nom="<?= hsc($dog['nom_dog']) ?>"
                        data-race="<?= hsc($dog['nom_race']) ?>"
                        data-categorie="<?= hsc($dog['categorie']) ?>"
                        data-age="<?= hsc($dog['age_dog']) ?>"
                        data-date-naissance="<?= hsc($dog['date_naissance']) ?>"
                        data-sexe="<?= hsc($dog['sexe_dog']) ?>"
                        data-date-inscription="<?= hsc($dog['date_inscription']) ?>">
                        <?= hsc($dog['nom_dog']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="dog-info" class="dog-info" style="display: none; margin-top: 20px;">
            <h3>Informations sur le chien</h3>
            <p><strong>Nom :&nbsp;</strong><span id="info-nom"></span></p>
            <p><strong>Catégorie :&nbsp; </strong><span id="info-categorie"></span></p>
            <p><strong>Râce :&nbsp; </strong><span id="info-race"></span></p>
            <p><strong>Age : &nbsp;</strong><span id="info-age"></span>&nbsp; mois</p>
            <p><strong>Date de naissance :&nbsp;</strong><span id="info-date-naissance"></span></p>
            <p><strong>Sexe :&nbsp;</strong><span id="info-sexe"></span></p>
            <p><strong>Date d'inscription :&nbsp;</strong><span id="info-date-inscription"></span></p>
        </div>

        <div class="progress">
            <h3>Suivi des progrès</h3>
            <table class="progress-table ">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Coach</th>
                        <th>Nom du cours</th>
                        <th>Note</th>
                        <th>Commentaires</th>
                        <th>Progrès</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($commentaires as $commentaire) {

                        $id_dog = $commentaire['id_dog'];
                        if (!isset($commentaire_dog[$id_dog])) {
                            $commentaire_dog[$id_dog] = [];
                        }

                        $commentaire_dog[$id_dog][] = [
                            'date' => date('d/m/Y', strtotime($commentaire['date_commentaire'])),
                            'coach' => $commentaire['prenom_utilisateur'] . ' ' . $commentaire['nom_utilisateur'],
                            'nom_cours' => $commentaire['nom_cours'],
                            'note' => $commentaire['note'],
                            'progres' => $commentaire['progres'],
                            'commentaire' => $commentaire['commentaire']
                        ];
                    } ?>
                </tbody>
            </table>
        </div>
    </section>


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
    <!-- CONSTANTE COMMENTAIRE -->
    <script>
        const commentaireDog = <?= json_encode($commentaire_dog) ?>
    </script>
    <script src="user.js"></script>


</body>

</html>