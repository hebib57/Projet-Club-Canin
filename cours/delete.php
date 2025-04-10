<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";


//--------------------------------------------------------SUPPRESSION D'UN CHIEN---------------------------------------------------------------------------//
if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $stmt = $db->prepare("DELETE FROM cours WHERE id_cours=:id_cours"); //DELETE toujours accompagné de WHERE
    $stmt->execute([":id_cours" => $_GET['id']]);


    echo "<script>alert('" . hsc('Cours supprimé avec succès') . "'); window.location.href = '../admin/administratif.php';</script>";
} else {
    echo "<script>alert('" . hsc('Erreur lors de la suppression du cours') . "'); window.location.href = '../admin/administratif.php';</script>";
}

exit();
