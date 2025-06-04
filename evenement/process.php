<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";



//--------------------------------------------------------------------AJOUT D'UN EVENEMENT-----------------------------------------------------------------------------//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_event = isset($_POST['id_event']) ? $_POST['id_event'] : 0;
    $nom_event = $_POST['nom_event'];
    $place_max = $_POST['place_max'];
    $date_event = $_POST['date_event'];
    $heure_event = $_POST['heure_event'];


    try {
        if (empty($id_event) || $id_event == "0") {

            $sql = "INSERT INTO evenement (nom_event, place_max, date_event, heure_event)
                            VALUES(:nom_event, :place_max, :date_event, :heure_event)";
            $stmt = $db->prepare($sql);

            $stmt->execute([
                ':nom_event' => $nom_event,
                ':place_max' => $place_max,
                ':date_event' => $date_event,
                ':heure_event' => $heure_event,
            ]);
            //recup id evenement qu'on vient d'ajouter
            $id_event = $db->lastInsertId();



            $message = "Evenement ajouté avec succès";
        } else {
            // modifie un évènement 
            $sql = "UPDATE evenement SET 
                    nom_event = :nom_event,
                    place_max = :place_max,
                    date_event = :date_event,
                    heure_event = :heure_event
                    WHERE id_event = :id_event";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':nom_event' => $nom_event,
                ':place_max' => $place_max,
                ':date_event' => $date_event,
                ':heure_event' => $heure_event,
                ':id_event' => $id_event
            ]);

            $message = "Evenement modifié avec succès";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $message = "Erreur lors de l'ajout de l'évènement";
    }

    switch ($_SESSION['role_name']) {
        case 'admin':
            $redirectUrl = '../admin/administratif.php#events';
            break;
        case 'coach':
            $redirectUrl = '../coach.php#events';
            break;
        case 'utilisateur':
            $redirectUrl = '../user.php#events';
            break;
        default:
            $redirectUrl = '../index.php';
    }

    echo "<script>alert('" . hsc($message) . "'); window.location.href = '$redirectUrl';</script>";
}
