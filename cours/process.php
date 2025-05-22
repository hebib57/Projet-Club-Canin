<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

//--------------------------------------------------------------------------MODIFICATION COURS-------------------------------------------------------------------//
// Verifie soumission formulaire 
if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {

    // verifie id est bien present
    if (isset($_POST["id_cours"])) {
        $id_cours = $_POST["id_cours"];


        // id = 0 => insertion, sinon c'est une mise à jour
        try {
            if ($id_cours == "0") {
                // Ajout d'un nouveau cours dans BDD
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
                $stmt->bindValue(":place_max", $_POST["place_max"]);

                $stmt->execute();
                $id = $db->lastInsertId(); // recup id du nouveau cours
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




                // Liaison des valeurs avec la requête 
                $stmt->bindValue(":nom_cours", $_POST["nom_cours"]);
                $stmt->bindValue(":type_cours", $_POST["type_cours"]);
                $stmt->bindValue(":age_min", $_POST["age_min"]);
                $stmt->bindValue(":age_max", $_POST["age_max"]);
                $stmt->bindValue(":race_dog", $_POST["race_dog"]);
                $stmt->bindValue(":sexe_dog", $_POST["sexe_dog"]);
                $stmt->bindValue(":description_cours", $_POST["description_cours"]);
                $stmt->bindValue(":date_cours", $_POST["date_cours"]);
                $stmt->bindValue(":place_max", $_POST["place_max"]);
                $stmt->bindValue(":id_cours", $id_cours); // Liaison de l'id

                if ($stmt->execute()) {
                    $message = ($id_cours == "0") ? "Cours ajouté avec succès" : "Cours modifié avec succès";

                    switch ($_SESSION['role_name']) {
                        case 'admin':
                            $redirectUrl = '../admin/administratif.php#cours_programmé';
                            break;
                        case 'coach':
                            $redirectUrl = '../coach.php#cours_programmé';
                            break;
                        default:
                            $redirectUrl = '../index.php';                         
                    }
                    echo "<script>alert('" . hsc($message) . "'); window.location.href = '$redirectUrl';</script>";
                } else {
                    $message = "Erreur lors de la " . ($id_cours == "0" ? "création" : "modification") . " du cours";
                    echo "<script>alert('" . hsc($message) . "'); window.location.href = '../index.php#dogs';</script>";
                }
            }
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage()); //  Log erreur  serveur
            echo "<script>alert('Erreur lors de l\'enregistrement du cours'); window.location.href = '../index.php';</script>";
            exit;
        }
    }
}
