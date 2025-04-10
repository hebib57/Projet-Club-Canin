<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

//--------------------------------------------------------------------------MODIFICATION COURS-------------------------------------------------------------------//
// Vérifier si le formulaire a été soumis
if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {

    // Assurez-vous que l'ID utilisateur est bien présent
    if (isset($_POST["id_cours"]) && !empty($_POST["id_cours"])) {
        $id_cours = $_POST["id_cours"];

        // Si l'ID utilisateur est égal à 0, nous faisons une insertion, sinon c'est une mise à jour.
        if ($id_cours == "0") {
            // Ajout d'un nouvel utilisateur dans la base de données
            $stmt = $db->prepare("INSERT INTO cours (
                nom_cours,
                type_cours,
                age_min,
                age_max,
                race_dog,
                sexe_dog,
                description_cours,
                date_cours,
                place_max
            ) VALUES (
                :nom_cours,
                :type_cours,
                :age_min,
                :age_max,
                :race_dog,
                :sexe_dog,
                :description_cours,
                :date_cours,
                :place_max              
            )");


            // Liaison des valeurs avec la requête SQL
            $stmt->bindValue(":nom_cours", $_POST["nom_cours"]);
            $stmt->bindValue(":type_cours", $_POST["type_cours"]);
            $stmt->bindValue(":age_min", $_POST["age_min"]);
            $stmt->bindValue(":age_max", $_POST["age_max"]);
            $stmt->bindValue(":race_dog", $_POST["race_dog"]);
            $stmt->bindValue(":sexe_dog", $_POST["sexe_dog"]);
            $stmt->bindValue(":description_cours", $_POST["description_cours"]);
            $stmt->bindValue(":date_cours", $_POST["date_cours"]);
            $stmt->bindValue(":place_max", $_POST["place_cours"]);

            $stmt->execute();
            $id = $db->lastInsertId(); // Récupère l'ID du nouvel utilisateur
            echo "Cours ajouté avec succès!";
        } else {

            $stmt = $db->prepare("UPDATE cours SET
                nom_cours = :nom_cours,
                type_cours = :type_cours,
                age_min = :age_min,
                age_max = :age_max,
                race_dog = :race_dog,
                sexe_dog = :sexe_dog,
                description_cours = :description_cours,
                date_cours = :date_cours,
                place_max = :place_max
                WHERE id_cours = :id_cours");




            // Liaison des valeurs avec la requête SQL
            $stmt->bindValue(":nom_cours", $_POST["nom_cours"]);
            $stmt->bindValue(":type_cours", $_POST["type_cours"]);
            $stmt->bindValue(":age_min", $_POST["age_min"]);
            $stmt->bindValue(":age_max", $_POST["age_max"]);
            $stmt->bindValue(":race_dog", $_POST["race_dog"]);
            $stmt->bindValue(":sexe_dog", $_POST["sexe_dog"]);
            $stmt->bindValue(":description_cours", $_POST["description_cours"]);
            $stmt->bindValue(":date_cours", $_POST["date_cours"]);
            $stmt->bindValue(":place_max", $_POST["place_max"]);
            $stmt->bindValue(":id_cours", $id_cours); // Liaison de l'ID utilisateur

            if ($stmt->execute()) {
                echo "<script>alert('" . hsc('Cours modifié avec succès') . "'); window.location.href = '../admin/administratif.php';</script>";
            } else {
                echo "<script>alert('" . hsc('Erreur lors de la modification du cours') . "'); window.location.href = '../admin/administratif.php';</script>";
            }
        }
        exit;
    }
}
