<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";


//--------------------------------------------------------SUPPRESSION D'UN COMMENTAIRE---------------------------------------------------------------------------//
if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    try {
        $stmt = $db->prepare("DELETE FROM commentaire WHERE id_commentaire=:id_commentaire"); //DELETE toujours accompagné de WHERE
        $stmt->execute([":id_commentaire" => $_GET['id']]);

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
