<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";


//--------------------------------------------------------SUPPRESSION D'UNE INSCRIPTION à UN EVENEMENT---------------------------------------------------------------------------//


if (isset($_POST['id_inscription']) && is_numeric($_POST['id_inscription'])) {
    $id = (int)$_POST['id_inscription'];

    try {

        $stmt = $db->prepare("DELETE FROM inscription_evenement WHERE id_inscription=:id_inscription");

        $stmt->execute([":id_inscription" => $id]);


        if ($stmt->rowCount() > 0) {
            $message = 'Inscription supprimée avec succès';
        } else {

            $message = 'Aucune rinscription trouvée avec cet identifiant';
        }
    } catch (PDOException $e) {
        // En cas d'erreur SQL 
        $message = "Erreur lors de la suppression : " . $e->getMessage();
    }


    $redirectUrl = '';
    switch ($_SESSION['role_name']) {
        case 'admin':
            $redirectUrl = '../admin/administratif.php#reservations';
            break;
        case 'coach':
            $redirectUrl = '../coach.php#reservations';
            break;
        case 'utilisateur':
            $redirectUrl = '../user.php#reservations';
            break;

        default:
            $redirectUrl = '../index.php';
    }


    echo "<script>alert('" . hsc($message) . "'); window.location.href = '$redirectUrl';</script>";
}
exit();
