<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;


$utilisateur = "";
$nom = "";
$prenom = "";
$email = "";
$password = "";
$phone = "";
// $id_role = "";
$date = date("Y-m-d");


if ($id_utilisateur) {
    $stmt = $db->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur");
    $stmt->bindValue(":id_utilisateur", $id_utilisateur);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        $utilisateur = $row['id_utilisateur'];
        $nom = $row['nom_utilisateur'];
        $prenom = $row['prenom_utilisateur'];
        $email = $row['admin_mail'];
        $password = $row['admin_password'];
        $confirm_password = $password;
        $phone = $row['telephone_utilisateur'];
        $id_role = $row['id_role'];
        $date = $row['date_inscription'];
    };
};

require_once __DIR__ . '../../templates/header.php';
require_once __DIR__ . '/../templates/sidebar.php';

?>


<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé des activités du Club Canin.</h2>
</div>




<section class="form-container creation">
    <h2>Paramètre du compte</h2>
    <form action="../users/process.php" method="POST">

        <label for="nom_utilisateur">Nom</label>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur" value="<?= hsc($nom) ?>" />

        <label for="prenom_utilisateur">Prénom</label>
        <input type="text" id="prenom_utilisateur" name="prenom_utilisateur" value="<?= hsc($prenom) ?>" required />

        <label for="admin_mail">Email</label>
        <input type="email" id="admin_mail" name="admin_mail" value="<?= hsc($email) ?>" required>

        <label for="admin_password">Mot de passe</label>
        <input type="password" id="admin_password" name="admin_password" value="<?= hsc($password) ?>" required />

        <label for="confirm_password">Confirmer votre mot de passe</label>
        <input type="password" id="confirm_password" name="confirm_password" value="<?= hsc($confirm_password) ?>" required />

        <label for="telephone_utilisateur">Téléphone</label>
        <input type="tel" id="telephone_utilisateur" name="telephone_utilisateur" value="<?= hsc($phone) ?>" required />


        <label for="date_inscription">Date d'inscription</label>
        <input type="date" name="date_inscription" id="date_inscription" value="<?= hsc($date) ?>">

        <input type="hidden" name="id_utilisateur" value="<?= hsc($utilisateur) ?>">
        <button type="submit">Modifier mon compte utilisateur</button>

    </form>
</section>


<?php require_once __DIR__ . '../../templates/footer.php' ?>