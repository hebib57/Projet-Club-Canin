<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";


if (!isset($_SESSION['id_utilisateur']) && isset($_SESSION['user_id'])) {
    $_SESSION['id_utilisateur'] = $_SESSION['user_id'];
}
if (!isset($_SESSION['nom_utilisateur']) && isset($_SESSION['prenom_utilisateur'])) {
    $_SESSION['nom_utilisateur'] = $_SESSION['prenom_utilisateur'];
}


$role = $_SESSION['role_name'] ?? 'utilisateur';

$stmt = $db->prepare("SELECT * FROM categorie");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM race ");
$stmt->execute();
$races = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM utilisateur ");
$stmt->execute();
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);


// initialisation du formulaire (ajouter et modifier avec le même formulaire)
$id_dog = 0;
$nom_dog = "";
$age_dog = "";
$id_race = "";
$sexe_dog = "";
$id_utilisateur = "";
$nom_utilisateur = "";
$date = date("Y-m-d");
$nom_categorie = "";
$id_categorie = "";


if (isset($_GET["id"]) && is_numeric($_GET["id"])) {



    $stmt = $db->prepare("SELECT c.*, u.nom_utilisateur 
    FROM chien c
    JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur 
    WHERE id_dog = :id_dog");
    $stmt->bindValue(":id_dog", $_GET["id"]);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        $id_dog = $row['id_dog'];

        // $dog = $row['id_dog'];
        $nom_dog = $row['nom_dog'];
        $age_dog = $row['age_dog'];
        $id_race = $row['id_race'];
        $sexe_dog = $row['sexe_dog'];
        $id_utilisateur = $row['id_utilisateur'];
        $nom_utilisateur = $row['nom_utilisateur'];
        $date = $row['date_inscription'];
        $nom_categorie = $row['nom_categorie'];
        $id_categorie = $row['id_categorie'];
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
            <h2>Modifier Compte</h2>

            <form class="modif" action="process.php" method="post" enctype="multipart/form-data"><!--enctype sert pour le type file-->
                <label for="photo_dog">Photo :</label>
                <input type="file" name="photo_dog" accept="image/*">
                <label for="nom_dog">Nom</label>
                <input type="text" name="nom_dog" id="nom_dog" value="<?= hsc($nom_dog) ?>">
                <label for="sexe">sexe</label>
                <select id="sexe_dog" name="sexe_dog">
                    <option value="mâle">Mâle</option>
                    <option value="femelle">Femelle</option>
                </select>



                <label for="age_dog">Age</label>
                <input type="number" name="age_dog" id="age_dog" value="<?= hsc($age_dog) ?>">

                <label for="categorie">Catégorie</label>
                <input type="text" name="categorie" id="categorie" value="<?= hsc($nom_categorie) ?>" readonly>

                <label for="id_race">Râce</label>
                <select name="id_race" id="id_race" required>
                    <?php
                    foreach ($races as $race) {

                        echo '<option value="' . hsc($race['id_race']) .  '">' . hsc($race['nom_race']) . '</option>';
                    } ?>
                </select>


                <?php if ($id_dog == 0): ?>
                    <?php if ($role === "admin" || $role === "coach"): ?>
                        <label for="id_utilisateur">Propriétaire</label>
                        <select name="id_utilisateur" id="id_utilisateur" required>
                            <option value="">-- Sélectionner un utilisateur --</option>
                            <?php foreach ($utilisateurs as $user): ?>
                                <option value="<?= hsc($user['id_utilisateur']) ?>">
                                    <?= hsc($user['nom_utilisateur']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif ($role === "utilisateur" && isset($_SESSION['id_utilisateur'], $_SESSION['nom_utilisateur'])): ?>
                        <input type="hidden" name="id_utilisateur" value="<?= hsc($_SESSION['id_utilisateur']) ?>">
                        <p>Propriétaire : <?= hsc($_SESSION['nom_utilisateur']) ?></p>
                    <?php else: ?>
                        <label for="nom_utilisateur">Propriétaire</label>
                        <input type="text" id="nom_utilisateur" value="<?= hsc($nom_utilisateur) ?>" readonly>
                        <input type="hidden" name="id_utilisateur" value="<?= hsc($id_utilisateur) ?>">
                    <?php endif; ?>
                <?php else: ?>
                    <label for="nom_utilisateur">Propriétaire</label>
                    <input type="text" id="nom_utilisateur" value="<?= hsc($nom_utilisateur) ?>" readonly>
                    <input type="hidden" name="id_utilisateur" value="<?= hsc($id_utilisateur) ?>">
                <?php endif; ?>


                <label for="date_inscription">Date d'inscription</label>
                <input type="date" name="date_inscription" id="date_inscription" value="<?= hsc($date) ?>">
                <input type="hidden" name="id_dog" value="<?= hsc($id_dog) ?>">
                <input type="hidden" name="formCU" value="ok">
                <input class="btn__modif" type="submit" value="Enregistrer">

            </form>
            <button class="btn2__modif"><a href="../admin/administratif.php#dogs">Retour</a></button>
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