<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";


$dog = "";
$nom_dog = "";
$age_dog = "";
$race_dog = "";
$sexe_dog = "";
$proprietaire_dog = "";
$date = date("Y-m-d");


if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $stmt = $db->prepare("SELECT * FROM chien WHERE id_dog = :id_dog");
    $stmt->bindValue(":id_dog", $_GET["id"]);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        $dog = $row['id_dog'];
        $nom_dog = $row['nom_dog'];
        $age_dog = $row['age_dog'];
        $race_dog = $row['race_dog'];
        $sexe_dog = $row['sexe_dog'];
        $proprietaire_dog = $row['proprietaire_dog'];
        $date = $row['date_inscription'];
    };
};

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

    <section>

        <div class="modification">
            <h2>S'inscrire à ce cours</h2>
            <form class="modif" action="process.php" method="post" enctype="multipart/form-data"><!--enctype sert pour le type file-->
                <label for="nom_dog">Nom</label>
                <input type="text" name="nom_dog" id="nom_dog" value="<?= hsc($nom_dog) ?>">
                <label for="age_dog">Age</label>
                <input type="number" name="age_dog" id="age_dog" value="<?= hsc($age_dog) ?>">
                <label for="race_dog">Râce</label>
                <input type="text" name="race_dog" id="race_dog" value="<?= hsc($race_dog) ?>">
                <label for="sexe_dog">Sexe</label>
                <input type="text" name="sexe_dog" id="sexe_dog" value="<?= hsc($sexe_dog) ?>">
                <label for="proprietaire_dog">Propriétaire</label>
                <input type="text" name="proprietaire_dog" id="proprietaire_dog" value="<?= hsc($proprietaire_dog) ?>">
                <label for="date_inscription">Date d'inscription</label>
                <input type="date" name="date_inscription" id="date_inscription" value="<?= hsc($date) ?>">
                <input type="hidden" name="id_dog" value="<?= hsc($dog) ?>">
                <input type="hidden" name="formCU" value="ok">
                <input class="btn__modif" type="submit" value="Enregistrer">


            </form>
            <button class="btn2__modif"><a href="../admin/administratif.php">Retour</a></button>
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