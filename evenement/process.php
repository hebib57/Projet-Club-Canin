<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

//--------------------------------------------------------------------------MODIFICATION COURS-------------------------------------------------------------------//
// Verifie soumission formulaire 
if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {

    // verifie id est bien present
    if (isset($_POST["id_event"])) {
        $id_event = $_POST["id_event"];

        
        try {
            if ($id_event == "0") {
                // Ajout dans BDD
                $stmt = $db->prepare("INSERT INTO evenement (
                    nom_event,
                    date_event,
                    heure_event,                   
                    place_max
                ) VALUES (
                    :nom_event,
                    :date_event,
                    :heure_event,
                    :place_max              
                )");


                // Liaison des valeurs avec la requête SQL
                $stmt->bindValue(":nom_event", $_POST["nom_event"]);
                $stmt->bindValue(":date_event", $_POST["date_event"]);
                $stmt->bindValue(":heure_event", $_POST["heure_event"]);
                $stmt->bindValue(":place_max", $_POST["place_max"]);

                $stmt->execute();
                $id = $db->lastInsertId(); // recup id du nouvel évènement
                echo "Evènement ajouté avec succès!";
            } else {

                $stmt = $db->prepare("UPDATE evenement SET
                    nom_event = :nom_event,
                    date_event = :date_event,
                    heure_event = :heure_event,
                    place_max = :place_max
                    WHERE id_event = :id_event");

                // Liaison des valeurs avec la requête 
                $stmt->bindValue(":nom_event", $_POST["nom_event"]);
                $stmt->bindValue(":date_event", $_POST["date_event"]);
                $stmt->bindValue(":heure_event", $_POST["heure_event"]);
                $stmt->bindValue(":place_max", $_POST["place_max"]);
                $stmt->bindValue(":id_event", $id_event); 

                if ($stmt->execute()) {
                    $message = ($id_event == "0") ? "Evenement ajouté avec succès" : "Evenement modifié avec succès";

                    switch ($_SESSION['role_name']) {
                        case 'admin':
                            $redirectUrl = '../admin/administratif.php#events';
                            break;
                        case 'coach':
                            $redirectUrl = '../coach.php#events';
                            break;
                        default:
                            $redirectUrl = '../index.php';
                    }
                    echo "<script>alert('" . hsc($message) . "'); window.location.href = '$redirectUrl';</script>";
                } else {
                    $message = "Erreur lors de la " . ($id_event == "0" ? "création" : "modification") . " de l'évènement";
                    echo "<script>alert('" . hsc($message) . "'); window.location.href = '../index.php#dogs';</script>";
                }
            }
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage()); //  Log erreur  serveur
            echo "<script>alert('Erreur lors de l\'enregistrement de l'évènement'); window.location.href = '../index.php';</script>";
            exit;
        }
    }
}
