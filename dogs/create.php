<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";


//--------------------------------------------------------------------AJOUT D'UN CHIEN-----------------------------------------------------------------------------//
//vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //récupérer les valeurs du formulaire
    $nom_dog = $_POST['nom_dog'];
    $age_dog = $_POST['age_dog'];
    $race_dog = $_POST['race_dog'];
    $sexe_dog = $_POST['sexe_dog'];
    $proprietaire_dog = $_POST['proprietaire_dog'];

    // requête pour insérer un user dans la BDD
    $sql = "INSERT INTO chien (nom_dog, age_dog, race_dog, sexe_dog, proprietaire_dog)
                VALUES(:nom_dog, :age_dog, :race_dog, :sexe_dog, :proprietaire_dog)";
    $stmt = $db->prepare($sql);

    //execution de la requête
    if ($stmt->execute([
        ':nom_dog' => $nom_dog,
        ':age_dog' => $age_dog,
        ':race_dog' => $race_dog,
        ':sexe_dog' => $sexe_dog,
        ':proprietaire_dog' => $proprietaire_dog,
    ])) {

        echo "<script>alert('" . hsc('Chien ajouté avec succès') . "'); window.location.href = '../admin/administratif.php';</script>";
    } else {
        echo "<script>alert('" . hsc('Erreur lors de l\'ajout du chien') . "'); window.location.href = '../admin/administratif.php';</script>";
    }
}
exit();

//----------------------------------------------------------------------------------------------------------------------------------------------------------------//