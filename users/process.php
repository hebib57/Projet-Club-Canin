<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

//--------------------------------------------------------------------------MODIFICATION USER-------------------------------------------------------------------//
// Vérifier si le formulaire a été soumis
if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {

    // Assurez-vous que l'ID utilisateur est bien présent
    if (isset($_POST["id_utilisateur"]) && !empty($_POST["id_utilisateur"])) {
        $id_utilisateur = $_POST["id_utilisateur"];

        // Si l'ID utilisateur est égal à 0, nous faisons une insertion, sinon c'est une mise à jour.
        if ($id_utilisateur == "0") {
            // Ajout d'un nouvel utilisateur dans la base de données
            $stmt = $db->prepare("INSERT INTO utilisateur (
                nom_utilisateur,
                prenom_utilisateur,
                admin_mail,
                admin_password,
                telephone_utilisateur,
                role,
                date_inscription
            ) VALUES (
                :nom_utilisateur,
                :prenom_utilisateur,
                :admin_mail,
                :admin_password,
                :telephone_utilisateur,
                :role,
                :date_inscription
            )");


            $password_hashed = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);

            // Liaison des valeurs avec la requête SQL
            $stmt->bindValue(":nom_utilisateur", $_POST["nom_utilisateur"]);
            $stmt->bindValue(":prenom_utilisateur", $_POST["prenom_utilisateur"]);
            $stmt->bindValue(":admin_mail", $_POST["admin_mail"]);
            $stmt->bindValue(":admin_password", $password_hashed);
            $stmt->bindValue(":telephone_utilisateur", $_POST["telephone_utilisateur"]);
            $stmt->bindValue(":role", $_POST["role"]);
            $stmt->bindValue(":date_inscription", $_POST["date_inscription"]);

            $stmt->execute();
            $id = $db->lastInsertId(); // Récupère l'ID du nouvel utilisateur
            echo "Utilisateur ajouté avec succès!";
        } else {

            $stmt = $db->prepare("UPDATE utilisateur SET
                nom_utilisateur = :nom_utilisateur,
                prenom_utilisateur = :prenom_utilisateur,
                admin_mail = :admin_mail,
                admin_password = :admin_password,
                telephone_utilisateur = :telephone_utilisateur,
                role = :role,
                date_inscription = :date_inscription
                WHERE id_utilisateur = :id_utilisateur");


            $password_hashed = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);

            // Liaison des valeurs avec la requête SQL
            $stmt->bindValue(":nom_utilisateur", $_POST["nom_utilisateur"]);
            $stmt->bindValue(":prenom_utilisateur", $_POST["prenom_utilisateur"]);
            $stmt->bindValue(":admin_mail", $_POST["admin_mail"]);
            $stmt->bindValue(":admin_password", $password_hashed);
            $stmt->bindValue(":telephone_utilisateur", $_POST["telephone_utilisateur"]);
            $stmt->bindValue(":role", $_POST["role"]);
            $stmt->bindValue(":date_inscription", $_POST["date_inscription"]);
            $stmt->bindValue(":id_utilisateur", $id_utilisateur); // Liaison de l'ID utilisateur

            if ($stmt->execute()) {
                echo "<script>alert('" . hsc('Utilisateur modifié avec succès') . "'); window.location.href = '../admin/administratif.php';</script>";
            } else {
                echo "<script>alert('" . hsc('Erreur lors de la modification de l\'utilisateur') . "'); window.location.href = '../admin/administratif.php';</script>";
            }
        }
        exit;
    }
}
