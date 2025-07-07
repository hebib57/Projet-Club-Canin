<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";


//--------------------------------------------------------SUPPRESSION D'UN EVENEMENT---------------------------------------------------------------------------//
if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    try {

        $stmt = $db->prepare("DELETE FROM evenement WHERE id_event = :id_event;");

        $stmt->execute([":id_event" => $_GET['id']]);

        if ($stmt->rowCount() > 0) {
            $message = 'Evènement supprimé avec succès';
        } else {
            $message = 'Aucun évènement trouvé avec cet identifiant';
        }
    } catch (PDOException $e) {
        $message = 'Erreur lors de la suppression: ' . $e->getMessage();
    }

    $redirectUrl = '';
    switch ($_SESSION['role_name']) {
        case 'admin':
            $redirectUrl = '../admin/event_programmes-admin.php';
            break;
        case 'coach':
            $redirectUrl = '../event_programmes-coach.php';
            break;
        case 'utilisateur':
            $redirectUrl = '../user.php#cours_programmé';
            break;
        default:
            $redirectUrl = '../index.php';
    }
    echo  "<script>alert('" . hsc($message) . "'); window.location.href = '$redirectUrl';</script>";
}
exit();
