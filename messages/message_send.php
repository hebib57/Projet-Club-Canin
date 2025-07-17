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

require_once __DIR__ . '../../templates/header.php'
?>










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

<?php require_once __DIR__ . '../../templates/footer.php' ?>