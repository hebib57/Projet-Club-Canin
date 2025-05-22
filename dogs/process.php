<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";


//--------------------------------------------------------------------------MODIFICATION DOG-------------------------------------------------------------------//
// Vérifier si le formulaire a été soumis
if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {

    // Assurez-vous que l'ID utilisateur est bien présent
    if (isset($_POST["id_dog"]) && !empty($_POST["id_dog"])) {
        $id_dog = $_POST["id_dog"];

        // Si l'ID utilisateur est égal à 0, nous faisons une insertion, sinon c'est une mise à jour.
        try {
            if ($id_dog == "0") {
                // Ajout d'un nouvel utilisateur dans la base de données
                $stmt = $db->prepare("INSERT INTO chien (
                    nom_dog,
                    age_dog,
                    race_dog,
                    sexe_dog,
                    id_utilisateur,
                    date_inscription
                ) VALUES (
                    :nom_dog,
                    :age_dog,
                    :race_dog,
                    :sexe_dog,
                    :id_utilisateur,
                    :date_inscription              
                )");


                // Liaison des valeurs avec la requête SQL
                $stmt->bindValue(":nom_dog", $_POST["nom_dog"]);
                $stmt->bindValue(":age_dog", $_POST["age_dog"]);
                $stmt->bindValue(":race_dog", $_POST["race_dog"]);
                $stmt->bindValue(":sexe_dog", $_POST["sexe_dog"]);
                $stmt->bindValue(":id_utilisateur", $_POST["id_utilisateur"]);
                $stmt->bindValue(":date_inscription", $_POST["date_inscription"]);

                $stmt->execute();
                $id = $db->lastInsertId(); // Récupère l'ID du nouvel utilisateur
                echo "Chien ajouté avec succès!";
            } else {
                //modification
                $stmt = $db->prepare("UPDATE chien SET
                    nom_dog = :nom_dog,
                    age_dog = :age_dog,
                    race_dog = :race_dog,
                    sexe_dog = :sexe_dog,
                    id_utilisateur = :id_utilisateur,
                    date_inscription = :date_inscription
                    WHERE id_dog = :id_dog");




                // Liaison des valeurs avec la requête SQL
                $stmt->bindValue(":nom_dog", $_POST["nom_dog"]);
                $stmt->bindValue(":age_dog", $_POST["age_dog"]);
                $stmt->bindValue(":race_dog", $_POST["race_dog"]);
                $stmt->bindValue(":sexe_dog", $_POST["sexe_dog"]);
                $stmt->bindValue(":id_utilisateur", $_POST["id_utilisateur"]);
                $stmt->bindValue(":date_inscription", $_POST["date_inscription"]);
                $stmt->bindValue(":id_dog", $id_dog); // Liaison de l'ID utilisateur

                if ($stmt->execute()) {
                    $message = ($id_dog == "0") ? "Chien ajouté avec succès" : "Chien modifié avec succès";

                    switch ($_SESSION['role_name']) {
                        case 'admin':
                            $redirectUrl = '../admin/administratif.php#dogs';
                            break;
                        case 'utilisateur':
                            $redirectUrl = '../user.php#dogs';
                            break;
                        default:
                            $redirectUrl = '../index.php';
                    }

                    echo "<script>alert('" . hsc($message) . "'); window.location.href = '$redirectUrl';</script>";
                } else {
                    $message = "Erreur lors de la " . ($id_dog == "0" ? "création" : "modification") . " du chien";
                    echo "<script>alert('" . hsc($message) . "'); window.location.href = '../index.php#dogs';</script>";
                }
            }

            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage()); //  Log erreur  serveur
            echo "<script>alert('Erreur lors de l\'enregistrement du chien'); window.location.href = '../index.php';</script>";
            exit;
        }
    }
}
