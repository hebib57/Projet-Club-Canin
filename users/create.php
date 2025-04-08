<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";


//--------------------------------------------------------------------AJOUT D'UN UTILISATEUR-----------------------------------------------------------------------------//
//vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //récupérer les valeurs du formulaire
    $nom = $_POST['nom_utilisateur'];
    $prenom = $_POST['prenom_utilisateur'];
    $email = $_POST['admin_mail'];
    $password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
    $phone = $_POST['telephone_utilisateur'];
    $role = $_POST['role_utilisateur'];
    $date = $_POST['date_inscription'];

    // requête pour insérer un user dans la BDD
    $sql = "INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, admin_mail, admin_password, telephone_utilisateur, role, date_inscription)
                VALUES(:nom_utilisateur, :prenom_utilisateur, :admin_mail, :admin_password, :telephone_utilisateur, :role_utilisateur, :date_inscription)";
    $stmt = $db->prepare($sql);

    //execution de la requête
    if ($stmt->execute([
        ':nom_utilisateur' => $nom,
        ':prenom_utilisateur' => $prenom,
        ':admin_mail' => $email,
        ':admin_password' => $password,
        ':telephone_utilisateur' => $phone,
        ':role_utilisateur' => $role,
        ':date_inscription' => $date
    ])) {

        echo "<script>alert('Utilisateur ajouté avec succès'); window.location.href = '../admin/administratif.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'ajout de l\'utilisateur'); window.location.href = '../admin/administratif.php';</script>";
    }
}

// header('Location: ../admin/administratif.php');
exit();
