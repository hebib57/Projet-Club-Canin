<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

// initialisation du formulaire (ajouter et modifier avec le même formulaire)
$cours = "";
$nom_cours = "";
$type_cours = "";
// $age_min = "";
// $age_max = "";
$race_dog = "";
$sexe_dog = "";
$description_cours = "";
$date_cours = date("Y-m-d");
$heure_cours = "";
$places_disponibles = "";
$categorie_acceptee = "";
$id_coach = "";

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $stmt = $db->prepare("SELECT * FROM cours WHERE id_cours = :id_cours");
    $stmt->bindValue(":id_cours", $_GET["id"]);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        $cours = $row['id_cours'];
        $nom_cours = $row['nom_cours'];
        $type_cours = $row['type_cours'];
        // $age_min = $row['age_min'];
        // $age_max = $row['age_max'];
        $race_dog = $row['race_dog'];
        $sexe_dog = $row['sexe_dog'];
        $description_cours = $row['description_cours'];
        $date_cours = $row['date_cours'];
        $heure_cours = $row['heure_cours'];
        $places_disponibles = $row['places_disponibles'];
        $categorie_acceptee = $row['categorie_acceptee'];
        $id_coach = $row['id_coach'];
    };
};

$stmt = $db->prepare("SELECT * FROM type_cours");
$stmt->execute();
$type_cours_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM race");
$stmt->execute();
$races = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM categorie");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT id_utilisateur, nom_utilisateur, prenom_utilisateur FROM utilisateur WHERE id_role = 2");
$stmt->execute();
$coachs = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            <h2>Modifier Cours</h2>
            <form class="modif" action="../cours/process.php" method="post" enctype="multipart/form-data"><!--enctype sert pour le type file-->
                <label for="nom_cours">Nom du cours</label>
                <input type="text" name="nom_cours" id="nom_cours" value="<?= hsc($nom_cours) ?>">
                <label for="type_cours">Type de cours</label>
                <select id="type_cours" name="type_cours" required>
                    <?php
                    foreach ($type_cours_list as $type) {
                        $selected = ($type['nom'] == $type_cours) ? 'selected' : '';
                        echo '<option value="' . hsc($type['nom']) . '" ' . $selected . '>' . hsc($type['nom']) . '</option>';
                    }

                    ?>
                </select>
                <label for="id_coach">Coach prévu</label>
                <select id="id_coach" name="id_coach" required>
                    <option value="">-- Sélectionnez un coach --</option>
                    <?php
                    foreach ($coachs as $coach) {
                        $selected = ($coach['id_utilisateur'] == $id_coach) ? 'selected' : '';
                        echo '<option value="' . hsc($coach['id_utilisateur']) . '" ' . $selected . '>' . hsc($coach['nom_utilisateur']) . '</option>';
                    }

                    ?>
                </select>
                <!-- <label for="age_min">Age minimum</label>
                <input type="number" name="age_min" id="age_min" value="<?= hsc($age_min) ?>">
                <label for="age_max">Age maximum</label>
                <input type="number" name="age_max" id="age_max" value="<?= hsc($age_max) ?>"> -->

                <label for="categorie_acceptee">Catégorie acceptée</label>
                <select name="categorie_acceptee" id="categorie_acceptee" required>
                    <?php
                    foreach ($categories as $cat) {
                        $selected = ($cat['nom_categorie'] == $categorie_acceptee) ? 'selected' : '';
                        echo '<option value="' . hsc($cat['nom_categorie']) . '" ' . $selected . '>' . hsc($cat['nom_categorie']) . '</option>';
                    } ?>
                </select>

                <label for="race">Râce</label>
                <select name="race_dog" id="race_dog" required>
                    <?php
                    foreach ($races as $race) {
                        $selected = ($race['nom_race'] == $race_dog) ? 'selected' : '';
                        echo '<option value="' . hsc($race['nom_race']) . '" ' . $selected . '>' . hsc($race['nom_race']) . '</option>';
                    } ?>
                </select>
                <label for="sexe_dog">Sexe</label>
                <select name="sexe_dog" id="sexe_dog" value="<?= hsc($sexe_dog) ?>">
                    <option value="male">Mâle</option>
                    <option value="femelle">Femelle</option>
                </select>
                <label for="description_cours">Description du cours</label>
                <input type="text" name="description_cours" id="description_cours" value="<?= hsc($description_cours) ?>">
                <label for="date_cours">Date du cours</label>
                <input type="date" name="date_cours" id="date_cours" value="<?= hsc($date_cours) ?>">
                <label for="heure_cours">Heure du cours</label>
                <input type="time" name="heure_cours" id="heure_cours" value="<?= hsc($heure_cours) ?>">
                <label for="places_disponibles">Places disponibles</label>
                <input type="number" name="places_disponibles" id="places_disponibles" value="<?= hsc($places_disponibles) ?>">
                <input type="hidden" name="id_cours" value="<?= hsc($cours) ?>">
                <input type="hidden" name="formCU" value="ok">
                <input class="btn__modif" type="submit" value="Enregistrer">
            </form>
            <?php
            switch ($_SESSION['role_name']) {
                case 'admin':
                    $redirectUrl = '../admin/administratif.php#cours_programmé';
                    break;
                case 'coach':
                    $redirectUrl = '../cours_programmes-coach.php';
                    break;
                // case 'utilisateur':
                //     $redirectUrl = '../user.php#cours_programmé';
                //     break;
                default:
                    $redirectUrl = '../index.php';
            }
            ?>
            <button class="btn2__modif">
                <a href="<?= $redirectUrl ?>">Retour</a>
            </button>



            <!-- <button class="btn2__modif"><a href="../admin/administratif.php#cours">Retour</a></button> -->
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