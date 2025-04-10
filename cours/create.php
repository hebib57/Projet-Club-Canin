<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";


//--------------------------------------------------------------------AJOUT D'UN COURS-----------------------------------------------------------------------------//
//vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //récupérer les valeurs du formulaire
    $nom_cours = $_POST['nom_cours'];
    $type_cours = $_POST['type_cours'];
    $description_cours = $_POST['description_cours'];
    $age_min = $_POST['age_min'];
    $age_max = $_POST['age_max'];
    $race_dog = $_POST['race_dog'];
    $sexe_dog = $_POST['sexe_dog'];
    $place_max = $_POST['place_max'];
    $date_cours = $_POST['date_cours'];

    // requête pour insérer un user dans la BDD
    $sql = "INSERT INTO cours (nom_cours, type_cours, description_cours, age_min, age_max, race_dog, sexe_dog, place_max, date_cours)
                VALUES(:nom_cours, :type_cours, :description_cours, :age_min, :age_max, :race_dog, :sexe_dog, :place_max, :date_cours)";
    $stmt = $db->prepare($sql);

    //execution de la requête
    if ($stmt->execute([
        ':nom_cours' => $nom_cours,
        ':type_cours' => $type_cours,
        ':description_cours' => $description_cours,
        ':age_min' => $age_min,
        ':age_max' => $age_max,
        ':race_dog' => $race_dog,
        ':sexe_dog' => $sexe_dog,
        ':place_max' => $place_max,
        ':date_cours' => $date_cours,
    ])) {

        echo "<script>alert('" . hsc('Cours ajouté avec succès') . "'); window.location.href = '../admin/administratif.php';</script>";
    } else {
        echo "<script>alert('" . hsc('Erreur lors de l\'ajout du cours') . "'); window.location.href = '../admin/administratif.php';</script>";
    }
}
exit();

//----------------------------------------------------------------------------------------------------------------------------------------------------------------//