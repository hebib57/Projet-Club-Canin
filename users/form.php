<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

$utilisateur = "";
$nom = "";
$prenom = "";
$email = "";
$password = "";
$phone = "";
$id_role = "";
// $date = date("Y-m-d");


if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $stmt = $db->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur");
    $stmt->bindValue(":id_utilisateur", $_GET["id"]);
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
        // $date = $row['date_inscription'];
    };
};

require_once __DIR__ . '/../header.php'

?>


<section>

    <div class="modification">
        <h2>Modifier Compte</h2>
        <form class="modif" action="process.php" method="post" enctype="multipart/form-data"><!--enctype sert pour le type file-->
            <label for="nom_utilisateur">Nom</label>
            <input type="text" name="nom_utilisateur" id="nom_utilisateur" value="<?= hsc($nom) ?>">
            <label for="prenom_utilisateur">Prénom</label>
            <input type="text" name="prenom_utilisateur" id="prenom_utilisateur" value="<?= hsc($prenom) ?>">
            <label for="admin_mail">Email</label>
            <input type="email" name="admin_mail" id="admin_mail" value="<?= hsc($email) ?>">
            <label for="admin_password">Mot de passe</label>
            <input type="password" name="admin_password" id="admin_password" value="<?= hsc($password) ?>">
            <label for="confirm_password">Confirmer votre mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" value="<?= hsc($confirm_password) ?>" />
            <label for="telephone_utilisateur">Téléphone</label>
            <input type="tel" name="telephone_utilisateur" id="telephone_utilisateur" value="<?= hsc($phone) ?>">
            <label for="role">Rôle</label>

            <select id="id_role" name="id_role" required>
                <option value="1" <?= $id_role == 1 ? 'selected' : '' ?>>admin</option>
                <option value="2" <?= $id_role == 2 ? 'selected' : '' ?>>coach</option>
                <option value="3" <?= $id_role == 3 ? 'selected' : '' ?>>utilisateur</option>
            </select>

            <!-- <select id="id_role" name="id_role" value="<?= hsc($id_role) ?>" required>
                    <option value="admin">admin</option>
                    <option value="coach">coach</option>
                    <option value="utilisateur">utilisateur</option>

                </select> -->
            <input type="hidden" name="id_utilisateur" value="<?= hsc($utilisateur) ?>">
            <!-- <input type="hidden" name="formCU" value="ok"> -->
            <input class="btn__modif" type="submit" value="Enregistrer">


        </form>
        <button class="btn2__modif"><a href="../admin/administratif.php#admins">Retour</a></button>
    </div>
</section>

<?php require_once __DIR__ . '/../footer.php' ?>