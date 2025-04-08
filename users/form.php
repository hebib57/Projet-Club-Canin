<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

// Initialisation du formulaire (nécessaire pour que l'on puisse ajouter un produit et le modifier avec le même formulaire)
$utilisateur = "";
$nom = "";
$prenom = "";
$email = "";
$password = "";
$phone = "";
$role = "";
$date = date("Y-m-d");


if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $stmt = $db->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur");
    $stmt->bindValue(":id_utilisateur", $_GET["id"]);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        $utilisateur = $row['id_utilisateur'];
        $nom = $row['nom_utilisateur'];
        $prenom = $row['prenom_utilisateur'];
        $email = $row['admin_mail'];
        $password = $row['admin_password'];
        $phone = $row['telephone_utilisateur'];
        $role = $row['role'];
        $date = $row['date_inscription'];
    };
};
// header("Location:../admin/administratif.php");
// exit;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification utilisateur</title>

</head>

<body>
    <!-- L'élement dans le for du label doit correspondre à l'id de l'input -->
    <form action="process.php" method="post" enctype="multipart/form-data"><!--enctype sert pour le type file-->
        <label for="nom_utilisateur">Nom</label>
        <input type="text" name="nom_utilisateur" id="nom_utilisateur" value="<?= hsc($nom) ?>">
        <label for="prenom_utilisateur">Prénom</label>
        <input type="text" name="prenom_utilisateur" id="prenom_utilisateur" value="<?= hsc($prenom) ?>">
        <label for="admin_mail">Email</label>
        <input type="email" name="admin_mail" id="admin_mail" value="<?= hsc($email) ?>">
        <label for="admin_password">Mot de passe</label>
        <input type="password" name="admin_password" id="admin_password" value="<?= hsc($password) ?>">
        <label for="telephone_utilisateur">Téléphone</label>
        <input type="tel" name="telephone_utilisateur" id="telephone_utilisateur" value="<?= hsc($phone) ?>">
        <label for="telephone utilisasteur">Rôle</label>
        <input type="text" name="role" id="role" value="<?= hsc($role) ?>">
        <label for="date_inscription">Date d'inscription</label>
        <input type="date" name="date_inscription" id="date_inscription" value="<?= hsc($date) ?>">
        <input type="hidden" name="id_utilisateur" value="<?= hsc($utilisateur) ?>">
        <input type="hidden" name="formCU" value="ok">
        <input type="submit" value="Enregistrer">

        <a href="../admin/administratif.php">Retour</a>
    </form>

</body>