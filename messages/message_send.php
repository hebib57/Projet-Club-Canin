<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";


$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';


$replyToId = $_POST['reply_to'] ?? $_GET['reply_to'] ?? null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expediteur = $_SESSION['user_id'];
    $sujet_message = $_POST['sujet_message'];

    $destinataires = $_POST['destinataires'] ?? [];
    $contenu = trim($_POST['contenu']);

    if (!empty($contenu) && !empty($destinataires)) {
        $stmt = $db->prepare("INSERT INTO message (contenu, sujet_message, date_envoi, lu, id_expediteur, id_destinataire) VALUES (?, ?, NOW(), 0, ?, ?)");

        foreach ($destinataires as $dest) {
            $stmt->execute([$contenu, $sujet_message, $expediteur, $dest]);
        }

        $success = "Message envoyé avec succès.";

        switch ($_SESSION['role_name']) {
            case 'admin':
                $redirectUrl = '../admin/administratif.php#messagerie';
                break;
            case 'coach':
                $redirectUrl = '../messagerie-coach.php';
                break;
            case 'utilisateur':
                $redirectUrl = '../messagerie-user.php';
                break;
            default:
                $redirectUrl = '../index.php';
        }

        echo "<script>alert('" . hsc($success) . "'); window.location.href = '$redirectUrl';</script>";
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}

// Recup la liste des utilisateurs 
$stmt = $db->prepare("SELECT id_utilisateur, prenom_utilisateur, nom_utilisateur FROM utilisateur WHERE id_utilisateur != ?");
$stmt->execute([$_SESSION['user_id']]);
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Club CANIN - Accueil</title>
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





        <section class="modification">
            <h2>Envoyer un nouveau message</h2>

            <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <div class="contact-form">
                <form method="POST">
                    <label for="destinataires">Destinataire(s) :</label><br>
                    <select name="destinataires[]" id="destinataires" multiple required style="width: 100%;">

                        <?php foreach ($utilisateurs as $user): ?>
                            <option value="<?= $user['id_utilisateur'] ?>" <?= ($replyToId == $user['id_utilisateur']) ? 'selected' : '' ?>>
                                <?= hsc($user['prenom_utilisateur']) . ' ' . hsc($user['nom_utilisateur']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="">Sujet</label>
                    <input type="text" name="sujet_message" id="sujet_message">
                    <label>Message :</label><br>
                    <textarea name="contenu" rows="5" cols="50" required></textarea><br><br>

                    <button type="submit">Envoyer</button>
                </form>
                <?php
                switch ($_SESSION['role_name']) {
                    case 'admin':
                        $redirectUrl = '../admin/administratif.php#messagerie';
                        break;
                    case 'coach':
                        $redirectUrl = '../messagerie-coach.php';
                        break;
                    case 'utilisateur':
                        $redirectUrl = '../messagerie-user.php';
                        break;
                    default:
                        $redirectUrl = '../index.php';
                }
                ?>
                <button class="btn2__modif">
                    <a href="<?= $redirectUrl ?>">Retour</a>
                </button>
            </div>
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
    <script src="../index.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect('#destinataires', {
            plugins: ['remove_button'],
            placeholder: "Rechercher un ou plusieurs destinataires...",
            maxOptions: 1000,
            searchField: ['text'] // pour recherche par noms
        });
    </script>

</body>

</html>