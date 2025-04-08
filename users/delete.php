<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $stmt = $db->prepare("DELETE FROM utilisateur WHERE id_Utilisateur=:id_Utilisateur"); //DELETE toujours accompagné de WHERE
    $stmt->execute([":id_Utilisateur" => $_GET['id']]);
}

header("Location:/admin/administratif.php");
