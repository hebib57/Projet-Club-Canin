<?php
// require_once "../admin/include/connect.php";
// session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$id_utilisateur = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_dog = $_POST['id_dog'];
    $id_reservation = $_POST['id_reservation'];
    $nom_cours = $_POST['nom_cours'];
    $type_cours = $_POST['type_cours'];
    $note = $_POST['note'];
    $commentaire = $_POST['commentaire'];
    $progres = $_POST['progres'];
    // $date_commentaire = $_POST['date_commentaire'];

    if (!empty($commentaire) && !empty($id_dog)) {
        try {
            $stmt = $db->prepare("INSERT INTO commentaire (commentaire, note, nom_cours, type_cours, progres, id_dog, id_utilisateur, id_reservation) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                $commentaire,
                // $date_commentaire,
                $note,
                $nom_cours,
                $type_cours,
                $progres,
                $id_dog,
                $id_utilisateur,
                $id_reservation
            ]);

            $success = "Commentaire ajouté avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir les champs.";
    }
}


$stmt = $db->prepare("SELECT * FROM chien");
$stmt->execute();
$dogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT id_reservation, date_reservation FROM reservation ");
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Club CANIN - Commentaires</title>
    <link rel="stylesheet" href="../custom.css" />

</head>

<body>
    <header class="header2">
        <div class="logo">
            <img src="../interface_graphique/logo-dog-removebg-preview.png" alt="logo" />
        </div>
        <nav class="navbar">
            <ul class="navbar__burger-menu--closed">
                <li><a href="index.php">Accueil</a></li>
            </ul>
        </nav>
        <button class="navbar__burger-menu-toggle" id="burgerMenu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </header>






    <section class="modification">
        <h2>Envoyer un nouveau commentaire</h2>

        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <div class="contact-form">

            <form method="POST">
                <label for="id_dog">Chien :</label><br>
                <select name="id_dog" required>

                    <?php foreach ($dogs as $dog): ?>
                        <option value="<?= hsc($dog['id_dog']) ?>">
                            <?= hsc($dog['nom_dog']) ?>

                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="id_reservation">Réservation :</label><br>
                <select name="id_reservation" required>

                    <?php foreach ($reservations as $reservation): ?>
                        <option value="<?= hsc($reservation['id_reservation']) ?>">
                            <?= hsc($reservation['date_reservation']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Nom du cours :</label>
                <input type="text" name="nom_cours">

                <label>Type de cours :</label>
                <input type="text" name="type_cours">

                <!-- <label>Date du cours :</label>
                <input type="date" name="date_cours"> -->

                <label>Note (0-10) :</label>
                <input type="number" name="note" min="0" max="10">

                <label>Commentaire :</label>
                <textarea name="commentaire" rows="4"></textarea>

                <label>Progrès constatés :</label>
                <textarea name="progres" rows="3"></textarea>

                <br><br>
                <button type="submit">Ajouter le commentaire</button>
            </form>

            <br>
            <a href="../coach.php#commentaires" class="btn2__modif">Retour</a>

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
    <script src="../index.js"></script>


</body>

</html>