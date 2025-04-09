<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

//--------------------------------------------------------------------------MODIFICATION USER-------------------------------------------------------------------//
// Vérifier si le formulaire a été soumis
if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {

    // Assurez-vous que l'ID utilisateur est bien présent
    if (isset($_POST["id_dog"]) && !empty($_POST["id_dog"])) {
        $id_dog = $_POST["id_dog"];

        // Si l'ID utilisateur est égal à 0, nous faisons une insertion, sinon c'est une mise à jour.
        if ($id_dog == "0") {
            // Ajout d'un nouvel utilisateur dans la base de données
            $stmt = $db->prepare("INSERT INTO chien (
                nom_dog,
                age_dog,
                race_dog,
                sexe_dog,
                proprietaire_dog,
                date_inscription
            ) VALUES (
                :nom_dog,
                :age_dog,
                :race_dog,
                :sexe_dog,
                :proprietaire_dog,
                date_inscription              
            )");


            // Liaison des valeurs avec la requête SQL
            $stmt->bindValue(":nom_dog", $_POST["nom_dog"]);
            $stmt->bindValue(":age_dog", $_POST["age_dog"]);
            $stmt->bindValue(":race_dog", $_POST["race_dog"]);
            $stmt->bindValue(":sexe_dog", $_POST["sexe_dog"]);
            $stmt->bindValue(":proprietaire_dog", $_POST["proprietaire_dog"]);
            $stmt->bindValue(":date_inscription", $_POST["date_inscription"]);

            $stmt->execute();
            $id = $db->lastInsertId(); // Récupère l'ID du nouvel utilisateur
            echo "Chien ajouté avec succès!";
        } else {

            $stmt = $db->prepare("UPDATE chien SET
                nom_dog = :nom_dog,
                age_dog = :age_dog,
                race_dog = :race_dog,
                sexe_dog = :sexe_dog,
                proprietaire_dog = :proprietaire_dog,
                date_inscription = :date_inscription
                WHERE id_dog = :id_dog");




            // Liaison des valeurs avec la requête SQL
            $stmt->bindValue(":nom_dog", $_POST["nom_dog"]);
            $stmt->bindValue(":age_dog", $_POST["age_dog"]);
            $stmt->bindValue(":race_dog", $_POST["race_dog"]);
            $stmt->bindValue(":sexe_dog", $_POST["sexe_dog"]);
            $stmt->bindValue(":proprietaire_dog", $_POST["proprietaire_dog"]);
            $stmt->bindValue(":date_inscription", $_POST["date_inscription"]);
            $stmt->bindValue(":id_dog", $id_dog); // Liaison de l'ID utilisateur

            if ($stmt->execute()) {
                echo "<script>alert('" . hsc('Chien modifié avec succès') . "'); window.location.href = '../admin/administratif.php';</script>";
            } else {
                echo "<script>alert('" . hsc('Erreur lors de la modification du chien') . "'); window.location.href = '../admin/administratif.php';</script>";
            }
        }
        exit;
    }
}
