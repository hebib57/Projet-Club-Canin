<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $stmt = $db->prepare("DELETE FROM utilisateur WHERE id_utilisateur=:id_utilisateur"); //DELETE toujours accompagné de WHERE
    $stmt->execute([":id_utilisateur" => $_GET['id']]);


    echo "<script>alert('" . hsc('Utilisateur supprimé avec succès') . "'); window.location.href = '../admin/administratif.php';</script>";
} else {
    echo "<script>alert('" . hsc('Erreur lors de la suppression de l\'utilisateur') . "'); window.location.href = '../admin/administratif.php';</script>";
}

exit();
