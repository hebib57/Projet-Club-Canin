<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$id_utilisateur = $_SESSION['user_id'] ?? null;
$id_commentaire = $_GET['id'] ?? null;
$success = $error = "";
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';

if ($id_commentaire && is_numeric($id_commentaire)) {

    // Traitement du formulaire
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nom_cours = $_POST['nom_cours'] ?? '';
        $type_cours = $_POST['type_cours'] ?? '';
        $note = $_POST['note'] ?? '';
        $commentaire = $_POST['commentaire'] ?? '';
        $progres = $_POST['progres'] ?? '';

        $stmt = $db->prepare("UPDATE commentaire SET 
            nom_cours = :nom_cours,
            type_cours = :type_cours,
            note = :note,
            commentaire = :commentaire,
            progres = :progres
            WHERE id_commentaire = :id_commentaire");

        $stmt->bindValue(':nom_cours', $nom_cours);
        $stmt->bindValue(':type_cours', $type_cours);
        $stmt->bindValue(':note', $note);
        $stmt->bindValue(':commentaire', $commentaire);
        $stmt->bindValue(':progres', $progres);
        $stmt->bindValue(':id_commentaire', $id_commentaire);


        if ($stmt->execute()) {
            $success = "Commentaire mis à jour avec succès.";
        } else {
            $error = "Erreur lors de la mise à jour.";
        }
    }

    // Récupération des données existantes pour pré-remplir le formulaire
    $stmt = $db->prepare("SELECT * FROM commentaire WHERE id_commentaire = :id_commentaire");
    $stmt->bindValue(':id_commentaire', $id_commentaire);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        // Pré-remplir les champs du formulaire
        $nom_cours = $row['nom_cours'];
        $type_cours = $row['type_cours'];
        $note = $row['note'];
        $commentaire = $row['commentaire'];
        $progres = $row['progres'];
    } else {
        $error = "Commentaire introuvable.";
    }
} else {
    $error = "ID de commentaire invalide.";
}
?>




<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification utilisateur</title>
    <link rel="stylesheet" href="../custom.css">
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
    <main>
        <div class="title">
            <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
        </div>

        <main>


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

            <section class="modification">
                <h2>Modifier le commentaire</h2>
                <div class="contact-form">
                    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
                    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

                    <form method="POST">
                        <label>Nom du cours :</label>
                        <input type="text" name="nom_cours" value="<?= hsc($nom_cours) ?>">

                        <label>Type de cours :</label>
                        <input type="text" name="type_cours" value="<?= hsc($type_cours) ?>">

                        <label>Note (0-10) :</label>
                        <input type="number" name="note" min="0" max="10" value="<?= hsc($note) ?>">

                        <label>Commentaire :</label>
                        <textarea name="commentaire" rows="4"><?= hsc($commentaire) ?></textarea>

                        <label>Progrès constatés :</label>
                        <textarea name="progres" rows="3"><?= hsc($progres) ?></textarea>

                        <br><br>
                        <button type="submit">Modifier le commentaire</button>
                    </form>

                    <br>
                    <a href="../evaluations-coach.php" class="btn2__modif">Retour</a>
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