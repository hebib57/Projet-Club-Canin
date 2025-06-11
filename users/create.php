<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
// require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

//--------------------------------------------------------------------AJOUT D'UN UTILISATEUR-----------------------------------------------------------------------------//

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sécurisation des données
    $nom = $_POST['nom_utilisateur'] ?? '';
    $prenom = $_POST['prenom_utilisateur'] ?? '';
    $email = filter_var($_POST['admin_mail'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['admin_password'] ?? '', PASSWORD_DEFAULT);
    $phone = $_POST['telephone_utilisateur'] ?? '';
    $id_role = $_POST['id_role'] ?? null;
    // $date = $_POST['date_inscription'] ?? date('Y-m-d');

    try {

        $db->beginTransaction();

        $sqlUser = "INSERT INTO utilisateur (
                        nom_utilisateur, prenom_utilisateur, admin_mail, admin_password, telephone_utilisateur 
                    ) VALUES (
                        :nom_utilisateur, :prenom_utilisateur, :admin_mail, :admin_password, :telephone_utilisateur 
                    )";
        $stmtUser = $db->prepare($sqlUser);


        $stmtUser->execute([
            ':nom_utilisateur' => $nom,
            ':prenom_utilisateur' => $prenom,
            ':admin_mail' => $email,
            ':admin_password' => $password,
            ':telephone_utilisateur' => $phone
            // ':date_inscription' => $date
        ]);

        // recup id inséré
        $id_utilisateur = $db->lastInsertId();



        $sqlRole = "INSERT INTO utilisateur_role (id_utilisateur, id_role)
                    VALUES (:id_utilisateur, :id_role)";
        $stmtRole = $db->prepare($sqlRole);
        $stmtRole->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_role' => $id_role
        ]);



        $db->commit();
        $message = "Utilisateur ajouté avec succès";
    } catch (PDOException $e) {
        // Annule la transaction en cas d'erreur
        $db->rollBack();
        error_log($e->getMessage());

        $message = "Erreur lors de l'ajout de l'utilisateur";
    }

    switch ($_SESSION['role_name']) {
        case 'admin':
            $redirectUrl = '../admin/administratif.php';
            break;
        case 'coach':
            $redirectUrl = '../coach.php';
            break;
        case 'utilisateur':
            $redirectUrl = '../user.php';
            break;
        default:
            $redirectUrl = '../index.php';
    }

    echo "<script>alert('" . hsc($message) . "'); window.location.href = '$redirectUrl';</script>";
}

exit();

//----------------------------------------------------------------------------------------------------------------------------------------------------------------//