<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";


//--------------------------------------------------------SUPPRESSION D'UN CHIEN---------------------------------------------------------------------------//
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id']; // Cast en entier pour éviter les injections ou erreurs de type

    try {
        $stmt = $db->prepare("DELETE FROM chien WHERE id_dog=:id_dog"); //DELETE toujours suivi de WHERE
        $stmt->execute([":id_dog" => $_GET['id']]);

        if ($stmt->rowCount() > 0) {
            $message = 'Chien supprimé avec succès';
        } else {
            $message = 'Aucun chien trouvé avec cet identifiant';
        }
    } catch (PDOException $e) {
        $message = 'Erreur lors de la suppression: ' . $e->getMessage();
    }

    $redirectUrl = '';
    switch ($_SESSION['role_name']) {
        case 'admin':
            $redirectUrl = '../admin/dogs-admin.php';
            break;
        case 'utilisateur':
            $redirectUrl = '../dogs-user.php';
            break;
        default:
            $redirectUrl = '../index.php';
    }
    echo  "<script>alert('" . hsc($message) . "'); window.location.href = '$redirectUrl';</script>";
}
exit();
