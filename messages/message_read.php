<?php
// require_once "../admin/include/connect.php";
// session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";


if (!isset($_GET['id_message']) || !isset($_SESSION['user_id'])) {
    die("Message introuvable ou utilisateur non connecté.");
}


$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$id_message = $_GET['id_message'];
$id_utilisateur = $_SESSION['user_id'];

// recup le message
$sql = "SELECT m.*, u.prenom_utilisateur, u.nom_utilisateur 
        FROM message m
        JOIN utilisateur u ON m.id_expediteur = u.id_utilisateur
        WHERE m.id_message = :id_message AND m.id_destinataire = :id_utilisateur";
$stmt = $db->prepare($sql);
$stmt->execute([':id_message' => $id_message, ':id_utilisateur' => $id_utilisateur]);
$message = $stmt->fetch();

if (!$message) {
    die("Message introuvable.");
}

// message comme lu
$sql = "UPDATE message SET lu = 1 WHERE id_message = :id_message";
$stmt = $db->prepare($sql);
$stmt->execute([':id_message' => $id_message]);


// formulaire de reponse
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['contenu'], $_POST['destinataires'], $_POST['sujet_message'])) {
    $contenu = trim($_POST['contenu']);
    $id_destinataire = (int) $_POST['destinataires'];
    $sujet = trim($_POST['sujet_message']);

    if (!empty($contenu) && !empty($id_destinataire) && !empty($sujet)) {
        $sql_insert = "INSERT INTO message (id_expediteur, id_destinataire, sujet_message, contenu, date_envoi, lu)
                       VALUES (:id_expediteur, :id_destinataire, :sujet_message, :contenu, NOW(), 0)";
        $stmt_insert = $db->prepare($sql_insert);
        $stmt_insert->execute([
            ':id_expediteur' => $id_utilisateur,
            ':id_destinataire' => $id_destinataire,
            ':sujet_message' => $sujet,
            ':contenu' => $contenu
        ]);

        $success = "Message envoyé avec succès.";


        switch ($_SESSION['role_name']) {
            case 'admin':
                $redirectUrl = '../admin/administratif.php#messagerie';
                break;
            case 'coach':
                $redirectUrl = '../coach.php#messagerie';
                break;
            case 'utilisateur':
                $redirectUrl = '../user.php#messagerie';
                break;
            default:
                $redirectUrl = '../index.php';
        }

        echo "<script>alert('" . hsc($success) . "'); window.location.href = '$redirectUrl';</script>";
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}



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
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="../admin/logout.php">Déconnexion</a></li>

            </ul>
        </nav>
        <button class="navbar__burger-menu-toggle" id="burgerMenu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </header>




    <section class="modification">




        <h3>Message de <?= hsc($message['prenom_utilisateur'] . ' ' . hsc($message['nom_utilisateur'])) ?></h3>
        <p><strong>Date :</strong> <?= hsc(date('d/m/Y H:i', strtotime(hsc($message['date_envoi'])))) ?></p>
        <p><strong>Sujet :</strong> <?= hsc($message['sujet_message']) ?></p>
        <p><strong>Message :</strong><?= hsc($message['contenu']) ?></p>


        <!-- <p><strong>Contenu :</strong><br><?= nl2br(hsc($message['contenu'])) ?></p> -->


        <form method="POST" style="margin-top: 20px;">
            <input type="hidden" name="destinataires" value="<?= hsc($message['id_expediteur']) ?>">
            <input type="hidden" name="sujet_message" value="RE: <?= hsc($message['sujet_message']) ?>">
            <textarea name="contenu" rows="5" cols="50" placeholder="Votre réponse..."></textarea><br>
            <button type="submit">Répondre</button>
        </form>


        <?php
        if (!isset($_SESSION['is_logged'])) {
            echo '<a href="./admin/inscription.php" class="button">S\'inscrire maintenant</a>';
        } else {
            switch ($_SESSION['role_name']) {
                case 'admin':
                    $redirectUrl = '../admin/administratif.php#messagerie';
                    break;
                case 'coach':
                    $redirectUrl = '../coach.php#messagerie';
                    break;
                case 'utilisateur':
                    $redirectUrl = '../user.php#messagerie';
                    break;
                default:
                    $redirectUrl = '../index.php';
            }
            echo '<a href="' . $redirectUrl . '" class="button">Retour à la messagerie</a>';
        } ?>

        <!-- <button><a href="../admin/administratif.php#messagerie">Retour à la messagerie</a></button> -->
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
</body>

</html>