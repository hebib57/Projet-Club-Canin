<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

//--------------------------------------------------------------------------MODIFICATION USER-------------------------------------------------------------------//

if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {


    if (isset($_POST["id_utilisateur"]) && !empty($_POST["id_utilisateur"])) {
        $id_utilisateur = $_POST["id_utilisateur"];

        if ($id_utilisateur == "0") {

            $stmt = $db->prepare("INSERT INTO utilisateur (
                nom_utilisateur,
                prenom_utilisateur,
                admin_mail,
                admin_password,
                telephone_utilisateur,
                id_role
                -- nom_role,
                -- date_inscription
            ) VALUES (
                :nom_utilisateur,
                :prenom_utilisateur,
                :admin_mail,
                :admin_password,
                :telephone_utilisateur,
                :id_role
                -- :nom_role,
                -- :date_inscription
            )");


            $password_hashed = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);

            // Liaison des valeurs
            $stmt->bindValue(":nom_utilisateur", $_POST["nom_utilisateur"]);
            $stmt->bindValue(":prenom_utilisateur", $_POST["prenom_utilisateur"]);
            $stmt->bindValue(":admin_mail", $_POST["admin_mail"]);
            $stmt->bindValue(":admin_password", $password_hashed);
            $stmt->bindValue(":telephone_utilisateur", $_POST["telephone_utilisateur"]);
            $stmt->bindValue(":id_role", $_POST["id_role"]);
            // $stmt->bindValue(":nom_role", $_POST["nom_role"]);
            // $stmt->bindValue(":date_inscription", $_POST["date_inscription"]);

            $stmt->execute();
            $id = $db->lastInsertId();
            echo "Utilisateur ajouté avec succès!";
        } else {

            $stmt = $db->prepare("UPDATE utilisateur SET
                nom_utilisateur = :nom_utilisateur,
                prenom_utilisateur = :prenom_utilisateur,
                admin_mail = :admin_mail,
                admin_password = :admin_password,
                telephone_utilisateur = :telephone_utilisateur
                id_role = :id_role,
                -- date_inscription = :date_inscription
                WHERE id_utilisateur = :id_utilisateur");


            // $password_hashed = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
            //hashage du nouveau mdp sinon on garde l'ancien
            if (!empty($_POST['admin_password'])) {
                $password_hashed = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
            } else {
                $password_hashed = $uer['admin_password'];
            }


            $stmt->bindValue(":nom_utilisateur", $_POST["nom_utilisateur"]);
            $stmt->bindValue(":prenom_utilisateur", $_POST["prenom_utilisateur"]);
            $stmt->bindValue(":admin_mail", $_POST["admin_mail"]);
            $stmt->bindValue(":admin_password", $password_hashed);
            $stmt->bindValue(":telephone_utilisateur", $_POST["telephone_utilisateur"]);
            $stmt->bindValue(":id_role", $_POST["id_role"]);
            // $stmt->bindValue(":date_inscription", $_POST["date_inscription"]);
            $stmt->bindValue(":id_utilisateur", $id_utilisateur);

            if ($stmt->execute()) {
                echo "<script>alert('" . hsc('Utilisateur modifié avec succès') . "'); window.location.href = '../admin/administratif.php';</script>";
            } else {
                echo "<script>alert('" . hsc('Erreur lors de la modification de l\'utilisateur') . "'); window.location.href = '../admin/administratif.php';</script>";
            }
        }
        exit;
    }
}
