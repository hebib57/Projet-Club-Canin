<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";


//--------------------------------------------------------------------AJOUT D'UN COACH-----------------------------------------------------------------------------//
//vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //récupérer les valeurs du formulaire
    $nom_coach = $_POST['nom_coach'];
    $prenom_coach = $_POST['prenom_coach'];
    $date_inscription = $_POST['date_inscription'];
    $email_coach = $_POST['email_coach'];


    // requête pour insérer un user dans la BDD
    $sql = "INSERT INTO coach (nom_coach, prenom_coach, date_inscription, email_coach)
                VALUES(:nom_coach, :prenom_coach, :date_inscription, :email_coach)";
    $stmt = $db->prepare($sql);

    //execution de la requête
    if ($stmt->execute([
        ':nom_coach' => $nom_coach,
        ':prenom_coach' => $prenom_coach,
        ':date_inscription' => $date_inscription,
        ':email_coach' => $email_coach,

    ])) {

        echo "<script>alert('" . hsc('Coach ajouté avec succès') . "'); window.location.href = '../admin/administratif.php';</script>";
    } else {
        echo "<script>alert('" . hsc('Erreur lors de l\'ajout du coach') . "'); window.location.href = '../admin/administratif.php';</script>";
    }
}
exit();

//----------------------------------------------------------------------------------------------------------------------------------------------------------------//