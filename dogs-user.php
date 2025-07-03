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
    <span id="date">
    </span>

    <main class="container_bord">

        <section class="dashbord">


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

            <section class="content">
                <div class="contenair" id="dogs">
                    <h2>Mes chiens</h2>
                    <div class="card">
                        <div class="card-header">
                            <h3>Mes chiens</h3>
                            <a href="../dogs/form.php" class="btn">+ Ajouter un chien</a>
                        </div>
                        <div class="card-body">
                            <ul class="dog-list">
                                <?php foreach ($dogs as $dog): ?>
                                    <li class="dog-item">
                                        <div class="dog-avatar">
                                            <img src=" <?= "../upload/xs_" . hsc($dog['photo_dog']) ?>" alt="photo chien">
                                        </div>
                                        <div class="dog-info">
                                            <h4><?= hsc($dog['nom_dog']) ?></h4>
                                            <p>Catégorie : <?= hsc($dog['categorie']) ?></p>
                                            <p>Râce : <?= hsc($dog['nom_race']) ?></p>
                                            <p>Age : <?= hsc($dog['age_dog']) ?> mois</p>
                                            <p>Sexe : <?= hsc($dog['sexe_dog']) ?></p>
                                        </div>
                                        <div class="dog-actions">
                                            <button class="btn btn-details"
                                                data-categorie="<?= hsc($dog['categorie']) ?>"
                                                data-nom="<?= hsc($dog['nom_dog']) ?>"
                                                data-race="<?= hsc($dog['nom_race']) ?>"
                                                data-age="<?= hsc($dog['age_dog']) ?>"
                                                data-sexe="<?= hsc($dog['sexe_dog']) ?>"
                                                data-photo="<?= hsc($dog['photo_dog']) ?>"
                                                data-date-inscription="<?= hsc($dog['date_inscription']) ?>"
                                                data-date-naissance="<?= hsc($dog['date_naissance']) ?>">
                                                Détails
                                            </button>
                                            <button class="btn"><a href="../dogs/form.php?id=<?= hsc($dog['id_dog']) ?>">Modifier</a></button>
                                            <button class="btn"><a href="../dogs/delete.php?id=<?= hsc($dog['id_dog']) ?>" onclick="return confirmationDeleteDog();">Supprimer</a></button>
                                        </div>
                                    </li>

                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="dogModal" class="modal_dog" style="display: none;">
                    <div class="modal_detail">
                        <span class="close">X</span>
                        <img id="modal_photo-dog" alt="chien" style="max-width: 100%;">
                        <h4 id="modal-nom"></h4>
                        <p><strong>Race :</strong> <span id="modal-race"></span></p>
                        <p><strong>Catégorie :</strong> <span id="modal-categorie"></span></p>
                        <p><strong>Âge :</strong> <span id="modal-age"></span> mois</p>
                        <p><strong>Sexe :</strong> <span id="modal-sexe"></span></p>
                        <p><strong>Date d'inscription :</strong> <span id="modal-date-inscription"></span></p>
                        <p><strong>Date de naissance :</strong> <span id="modal-date-naissance"></span></p>
                    </div>
                </div>
            </section>



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
</body>

</html>