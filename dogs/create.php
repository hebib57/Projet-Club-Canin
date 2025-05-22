<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";




//--------------------------------------------------------------------AJOUT D'UN CHIEN-----------------------------------------------------------------------------//
//verifie soumission formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //recup valeurs du formulaire
    $nom_dog = $_POST['nom_dog'];
    $age_dog = $_POST['age_dog'];
    $race_dog = $_POST['race_dog'];
    $sexe_dog = $_POST['sexe_dog'];
    $id_utilisateur = $_POST['id_utilisateur'];

    try {
        // inserer dog dans BDD
        $sql = "INSERT INTO chien (nom_dog, age_dog, race_dog, sexe_dog, id_utilisateur)
                VALUES(:nom_dog, :age_dog, :race_dog, :sexe_dog, :id_utilisateur)";
        $stmt = $db->prepare($sql);

        if ($stmt->execute([
            ':nom_dog' => $nom_dog,
            ':age_dog' => $age_dog,
            ':race_dog' => $race_dog,
            ':sexe_dog' => $sexe_dog,
            ':id_utilisateur' => $id_utilisateur,
        ])) {

            $message = "Chien ajouté avec succès";
        } else {
            $message = "Erreur lors de l'ajout du chien";
        }
    } catch (PDOException $e) {
        // Annule la transaction en cas d'erreur
        $db->rollBack();
        error_log($e->getMessage()); // Log pour debug

        $message = "Erreur lors de l'ajout du chien";
    }

    switch ($_SESSION['role_name']) {
        case 'admin':
            $redirectUrl = '../admin/administratif.php#dogs';
            break;
        case 'coach':
            $redirectUrl = '../coach.php';
            break;
        case 'utilisateur':
            $redirectUrl = '../user.php#dogs';
            break;
        default:
            $redirectUrl = '../index.php';
    }

    echo "<script>alert('" . hsc($message) . "'); window.location.href = '$redirectUrl';</script>";
}
exit();

//----------------------------------------------------------------------------------------------------------------------------------------------------------------//