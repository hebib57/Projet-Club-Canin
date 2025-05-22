<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

// $id_utilisateur = $_SESSION['user_id'] ?? null;
// $nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
// $prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

//--------------------------------------------------------SUPPRESSION D'UN MESSAGE---------------------------------------------------------------------------//
if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    try {
        $stmt = $db->prepare("DELETE FROM message WHERE id_message=:id_message"); //DELETE toujours accompagné de WHERE
        $stmt->execute([":id_message" => $_GET['id']]);

        if ($stmt->rowCount() > 0) {
            $message = 'Message supprimé avec succès';
        } else {
            $message = 'Aucun message trouvé avec cet identifiant';
        }
    } catch (PDOException $e) {
        $message = 'Erreur lors de la suppression: ' . $e->getMessage();
    }

    $redirectUrl = '';
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
    echo  "<script>alert('" . hsc($message) . "'); window.location.href = '$redirectUrl';</script>";
}
exit();
