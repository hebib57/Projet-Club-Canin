<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

// initialisation du formulaire 
$id_event = "";
$nom_event = "";
$date_event = "";
$heure_event = "";
$places_disponibles = "";



if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $stmt = $db->prepare("SELECT * FROM evenement WHERE id_event = :id_event");
    $stmt->bindValue(":id_event", $_GET["id"]);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        $id_event = $row['id_event'];
        $nom_event = $row['nom_event'];
        $date_event = $row['date_event'];
        $heure_event = $row['heure_event'];
        $places_disponibles = $row['places_disponibles'];
    };
};



$stmt = $db->prepare("SELECT * FROM evenement ");
$stmt->execute();
$races = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification évènement</title>
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
    <section>

        <div class="modification">
            <h2>Modifier Evènement</h2>
            <form class="modif" action="../evenement/process.php" method="post" enctype="multipart/form-data"><!--enctype sert pour le type file-->
                <label for="nom_event">Nom de l'évènement</label>
                <input type="text" name="nom_event" id="nom_event" value="<?= hsc($nom_event) ?>">
                <label for="date_event">Date de l'évènement</label>
                <input type="date" name="date_event" id="date_event" value="<?= hsc($date_event) ?>">
                <label for="heure_event">Heure de l'évènement</label>
                <input type="time" name="heure_event" id="heure_event" value="<?= hsc($heure_event) ?>">
                <label for="places_disponibles">Places disponibles</label>
                <input type="number" name="places_disponibles" id="places_disponibles" value="<?= hsc($places_disponibles) ?>">
                <input type="hidden" name="id_event" id="id_event" value="<?= hsc($id_event) ?>">
                <input type="hidden" name="formCU" value="ok">
                <input class="btn__modif" type="submit" value="Enregistrer">
            </form>
            <button class="btn2__modif"><a href="../admin/administratif.php#cours">Retour</a></button>
        </div>
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
                    <img src="./images/logo-dog-removebg-preview.png" alt="Educa dog" />
                </div>
            </div>
        </div>
        <p>
            Copyright &copy; - 2025 Club CANIN "Educa Dog"- Tous droits réservés.
        </p>
    </section>
    <script src="../index.js"></script>
</body>

</html>