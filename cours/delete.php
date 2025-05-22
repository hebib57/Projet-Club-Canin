<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";


//--------------------------------------------------------SUPPRESSION D'UN COURS---------------------------------------------------------------------------//
if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    try {

        $stmt = $db->prepare("DELETE s, c
                                FROM seance s
                                JOIN cours c ON s.id_cours = c.id_cours
                                WHERE c.id_cours = :id_cours;");
        // $stmt = $db->prepare("DELETE FROM cours WHERE id_cours=:id_cours"); //DELETE toujours suivi de WHERE
        $stmt->execute([":id_cours" => $_GET['id']]);

        if ($stmt->rowCount() > 0) {
            $message = 'Cours supprimé avec succès';
        } else {
            $message = 'Aucun cours trouvé avec cet identifiant';
        }
    } catch (PDOException $e) {
        $message = 'Erreur lors de la suppression: ' . $e->getMessage();
    }

    $redirectUrl = '';
    switch ($_SESSION['role_name']) {
        case 'admin':
            $redirectUrl = '../admin/administratif.php#cours_programmé';
            break;
        case 'coach':
            $redirectUrl = '../coach.php#cours_programmé';
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
