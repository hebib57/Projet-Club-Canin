<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";



//--------------------------------------------------------------------AJOUT D'UN COURS + SEANCE-----------------------------------------------------------------------------//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cours = isset($_POST['id_cours']) ? $_POST['id_cours'] : 0;
    $nom_cours = $_POST['nom_cours'];
    $type_cours = $_POST['type_cours'];
    $description_cours = $_POST['description_cours'];
    $age_min = $_POST['age_min'];
    $age_max = $_POST['age_max'];
    // $race_dog = $_POST['race_dog'];
    $sexe_dog = $_POST['sexe_dog'];
    $place_max = $_POST['place_max'];
    $date_cours = $_POST['date_cours'];

    // inserer dans table cours
    try {
        if (empty($id_cours) || $id_cours == "0") {

            $sql = "INSERT INTO cours (nom_cours, type_cours, description_cours, age_min, age_max, race_dog, sexe_dog, place_max, date_cours)
                            VALUES(:nom_cours, :type_cours, :description_cours, :age_min, :age_max, :race_dog, :sexe_dog, :place_max, :date_cours)";
            $stmt = $db->prepare($sql);

            $stmt->execute([
                ':nom_cours' => $nom_cours,
                ':type_cours' => $type_cours,
                ':description_cours' => $description_cours,
                ':age_min' => $age_min,
                ':age_max' => $age_max,
                ':race_dog' => $race_dog,
                ':sexe_dog' => $sexe_dog,
                ':place_max' => $place_max,
                ':date_cours' => $date_cours,
            ]);
            // recup id cours qu'on vient d'ajouter
            $id_cours = $db->lastInsertId();

            //seance cree automatiquement avec ce cours
            $sql_seance = "INSERT INTO seance (id_cours, date_seance, heure_seance, places_disponibles) 
                                    VALUES (:id_cours, :date_seance, '10:00:00', :places_disponibles)";
            $stmt_seance = $db->prepare($sql_seance);
            $stmt_seance->execute([
                ':id_cours' => $id_cours,
                ':date_seance' => $date_cours,
                ':places_disponibles' => $place_max
            ]);

            $message = "Cours ajouté avec succès";
        } else {
            
            $sql = "UPDATE cours SET 
                    nom_cours = :nom_cours,
                    type_cours = :type_cours,
                    description_cours = :description_cours,
                    age_min = :age_min,
                    age_max = :age_max,
                    race_dog = :race_dog,
                    sexe_dog = :sexe_dog,
                    place_max = :place_max,
                    date_cours = :date_cours
                    WHERE id_cours = :id_cours";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':nom_cours' => $nom_cours,
                ':type_cours' => $type_cours,
                ':description_cours' => $description_cours,
                ':age_min' => $age_min,
                ':age_max' => $age_max,
                ':race_dog' => $race_dog,
                ':sexe_dog' => $sexe_dog,
                ':place_max' => $place_max,
                ':date_cours' => $date_cours,
                ':id_cours' => $id_cours
            ]);

            $message = "Cours modifié avec succès";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $message = "Erreur lors de l'ajout du cours";
    }

    switch ($_SESSION['role_name']) {
        case 'admin':
            $redirectUrl = '../admin/administratif.php#cours_programmé';
            break;
        case 'coach':
            $redirectUrl = '../coach.php#cours_programmé';
            break;
        case 'utilisateur':
            $redirectUrl = '../user.php#cours_programmé';
            break;
        default:
            $redirectUrl = '../index.php';
    }

    echo "<script>alert('" . hsc($message) . "'); window.location.href = '$redirectUrl';</script>";
}
