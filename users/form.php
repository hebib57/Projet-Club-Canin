<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

$utilisateur = "";
$nom = "";
$prenom = "";
$email = "";
$password = "";
$phone = "";
$id_role = "";
// $date = date("Y-m-d");


if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $stmt = $db->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur");
    $stmt->bindValue(":id_utilisateur", $_GET["id"]);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        $utilisateur = $row['id_utilisateur'];
        $nom = $row['nom_utilisateur'];
        $prenom = $row['prenom_utilisateur'];
        $email = $row['admin_mail'];
        $password = $row['admin_password'];
        $confirm_password = $password;
        $phone = $row['telephone_utilisateur'];
        $id_role = $row['id_role'];
        // $date = $row['date_inscription'];
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

            </ul>
        </nav>
        <button class="navbar__burger-menu-toggle" id="burgerMenu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>


    </header>

    <main>
        <section>

            <div class="modification">
                <h2>Modifier Compte</h2>
                <form class="modif" action="process.php" method="post" enctype="multipart/form-data"><!--enctype sert pour le type file-->
                    <label for="nom_utilisateur">Nom</label>
                    <input type="text" name="nom_utilisateur" id="nom_utilisateur" value="<?= hsc($nom) ?>">
                    <label for="prenom_utilisateur">Prénom</label>
                    <input type="text" name="prenom_utilisateur" id="prenom_utilisateur" value="<?= hsc($prenom) ?>">
                    <label for="admin_mail">Email</label>
                    <input type="email" name="admin_mail" id="admin_mail" value="<?= hsc($email) ?>">
                    <label for="admin_password">Mot de passe</label>
                    <input type="password" name="admin_password" id="admin_password" value="<?= hsc($password) ?>">
                    <label for="confirm_password">Confirmer votre mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" value="<?= hsc($confirm_password) ?>" />
                    <label for="telephone_utilisateur">Téléphone</label>
                    <input type="tel" name="telephone_utilisateur" id="telephone_utilisateur" value="<?= hsc($phone) ?>">
                    <label for="role">Rôle</label>

                    <select id="id_role" name="id_role" required>
                        <option value="1" <?= $id_role == 1 ? 'selected' : '' ?>>admin</option>
                        <option value="2" <?= $id_role == 2 ? 'selected' : '' ?>>coach</option>
                        <option value="3" <?= $id_role == 3 ? 'selected' : '' ?>>utilisateur</option>
                    </select>

                    <!-- <select id="id_role" name="id_role" value="<?= hsc($id_role) ?>" required>
                    <option value="admin">admin</option>
                    <option value="coach">coach</option>
                    <option value="utilisateur">utilisateur</option>

                </select> -->
                    <input type="hidden" name="id_utilisateur" value="<?= hsc($utilisateur) ?>">
                    <!-- <input type="hidden" name="formCU" value="ok"> -->
                    <input class="btn__modif" type="submit" value="Enregistrer">


                </form>
                <button class="btn2__modif"><a href="../admin/administratif.php#admins">Retour</a></button>
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