<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
// require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
session_start();
//--------------------------------------------------------------------AJOUT D'UN UTILISATEUR-----------------------------------------------------------------------------//

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sécurisation des données
    $nom = $_POST['nom_utilisateur'] ?? '';
    $prenom = $_POST['prenom_utilisateur'] ?? '';
    $email = filter_var($_POST['admin_mail'] ?? '', FILTER_VALIDATE_EMAIL);
    $passwordR = $_POST['admin_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = $_POST['telephone_utilisateur'] ?? '';
    $id_role = $_POST['id_role'] ?? null;
    $password = null; //par defaut pas de changement de password


    if (!empty($passwordR) || !empty($confirm_password)) {
        if ($passwordR !== $confirm_password) {
            echo "<script>alert('Les mots de passe ne correspondent pas.'); window.history.back();</script>";
            exit;
        }

        $password = password_hash($passwordR, PASSWORD_DEFAULT);
    }
    try {
        $db->beginTransaction();

        $id_utilisateur = $_POST['id_utilisateur'] ?? null;

        if ($id_utilisateur) {
            // MODIFICATION

            // Si le mot de passe est vide, pas mis à jour
            if ($password) {

                $sqlUser = "UPDATE utilisateur SET 
                        nom_utilisateur = :nom_utilisateur,
                        prenom_utilisateur = :prenom_utilisateur,
                        admin_mail = :admin_mail,
                        admin_password = :admin_password,
                        telephone_utilisateur = :telephone_utilisateur,
                        id_role = :id_role
                       
                    WHERE id_utilisateur = :id_utilisateur";
                $params = [
                    ':nom_utilisateur' => $nom,
                    ':prenom_utilisateur' => $prenom,
                    ':admin_mail' => $email,
                    ':admin_password' => $password,
                    ':telephone_utilisateur' => $phone,
                    ':id_role' => $id_role,
                    ':id_utilisateur' => $id_utilisateur,
                    // ':date_inscription' => $date_inscription
                ];
            } else {
                $sqlUser = "UPDATE utilisateur SET 
                        nom_utilisateur = :nom_utilisateur,
                        prenom_utilisateur = :prenom_utilisateur,
                        admin_mail = :admin_mail,
                        telephone_utilisateur = :telephone_utilisateur,
                        id_role = :id_role
                    WHERE id_utilisateur = :id_utilisateur";
                $params = [
                    ':nom_utilisateur' => $nom,
                    ':prenom_utilisateur' => $prenom,
                    ':admin_mail' => $email,
                    ':telephone_utilisateur' => $phone,
                    ':id_role' => $id_role,
                    ':id_utilisateur' => $id_utilisateur
                ];
            }

            $stmtUser = $db->prepare($sqlUser);
            $stmtUser->execute($params);

            // Mise à jour de la table utilisateur_role
            $sqlRole = "UPDATE utilisateur_role SET id_role = :id_role WHERE id_utilisateur = :id_utilisateur";
            $stmtRole = $db->prepare($sqlRole);
            $stmtRole->execute([
                ':id_role' => $id_role,
                ':id_utilisateur' => $id_utilisateur
            ]);

            $message = "Utilisateur modifié avec succès";
        } else {
            // AJOUT

            $sqlUser = "INSERT INTO utilisateur (
                    nom_utilisateur, prenom_utilisateur, admin_mail, admin_password, telephone_utilisateur, id_role, date_inscription  
                ) VALUES (
                    :nom_utilisateur, :prenom_utilisateur, :admin_mail, :admin_password, :telephone_utilisateur, :id_role, NOW() 
                )";
            $stmtUser = $db->prepare($sqlUser);

            $stmtUser->execute([
                ':nom_utilisateur' => $nom,
                ':prenom_utilisateur' => $prenom,
                ':admin_mail' => $email,
                ':admin_password' => $password,
                ':telephone_utilisateur' => $phone,
                ':id_role' => $id_role
            ]);

            $id_utilisateur = $db->lastInsertId();

            $sqlRole = "INSERT INTO utilisateur_role (id_utilisateur, id_role) VALUES (:id_utilisateur, :id_role)";
            $stmtRole = $db->prepare($sqlRole);
            $stmtRole->execute([
                ':id_utilisateur' => $id_utilisateur,
                ':id_role' => $id_role
            ]);

            $message = "Utilisateur ajouté avec succès";
        }

        $db->commit();
    } catch (PDOException $e) {
        // Annule la transaction en cas d'erreur
        $db->rollBack();
        error_log($e->getMessage());

        // echo "<script>alert('Erreur : " . hsc($e->getMessage()) . "'); window.history.back();</script>";
        $message = "Erreur lors de l\'ajout de l\'utilisateur";
    }

    $role_name = $_SESSION['role_name'] ?? null;

    switch ($role_name) {
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