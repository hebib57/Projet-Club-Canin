<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";


if (!isset($_SESSION['id_utilisateur']) && isset($_SESSION['user_id'])) {
    $_SESSION['id_utilisateur'] = $_SESSION['user_id'];
}
if (!isset($_SESSION['nom_utilisateur']) && isset($_SESSION['prenom_utilisateur'])) {
    $_SESSION['nom_utilisateur'] = $_SESSION['prenom_utilisateur'];
}


$role = $_SESSION['role_name'] ?? 'utilisateur';

$stmt = $db->prepare("SELECT * FROM categorie");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM race ");
$stmt->execute();
$races = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM utilisateur ");
$stmt->execute();
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);


// initialisation du formulaire (ajouter et modifier avec le même formulaire)
$id_dog = 0;
$nom_dog = "";
$date_naissance = "";
$age_dog = "";
$id_race = "";
$sexe_dog = "";
$id_utilisateur = "";
$nom_utilisateur = "";
$date = date("Y-m-d");
$nom_categorie = "";
$id_categorie = "";


if (isset($_GET["id"]) && is_numeric($_GET["id"])) {



    $stmt = $db->prepare("SELECT c.*, u.nom_utilisateur, cat.nom_categorie, cat.id_categorie
    FROM chien c
    JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur 
    JOIN categorie cat ON c.id_categorie = cat.id_categorie
    WHERE id_dog = :id_dog");
    $stmt->bindValue(":id_dog", $_GET["id"]);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        $id_dog = $row['id_dog'];

        // $dog = $row['id_dog'];
        $nom_dog = $row['nom_dog'];
        $date_naissance = $row['date_naissance'];
        $age_dog = $row['age_dog'];
        $id_race = $row['id_race'];
        $sexe_dog = $row['sexe_dog'];
        $id_utilisateur = $row['id_utilisateur'];
        $nom_utilisateur = $row['nom_utilisateur'];
        $date = $row['date_inscription'];
        $nom_categorie = $row['nom_categorie'];
        $id_categorie = $row['id_categorie'];
    };
};


require_once __DIR__ . '/../header.php'
?>




<section>

    <div class="modification">
        <h2>Modifier Compte</h2>

        <form class="modif" action="process.php" method="post" enctype="multipart/form-data"><!--enctype sert pour le type file-->
            <label for="photo_dog">Photo :</label>
            <input type="file" name="photo_dog" accept="image/*">
            <label for="nom_dog">Nom</label>
            <input type="text" name="nom_dog" id="nom_dog" value="<?= hsc($nom_dog) ?>">
            <label for="sexe">sexe</label>
            <select id="sexe_dog" name="sexe_dog">
                <option value="mâle">Mâle</option>
                <option value="femelle">Femelle</option>
            </select>

            <label for="date_naissance">Date de naissance</label>
            <input type="date" name="date_naissance" id="date_naissance" value="<?= hsc($date_naissance) ?>">

            <!-- <label for="age_dog">Age</label>
                <input type="number" name="age_dog" id="age_dog" value="<?= hsc($age_dog) ?>"> -->

            <!-- <label for="categorie">Catégorie</label> -->
            <input type="hidden" name="categorie" id="categorie" value="<?= hsc($nom_categorie) ?>" readonly>

            <label for="id_race">Râce</label>
            <select name="id_race" id="id_race" required>
                <?php
                foreach ($races as $race) {

                    echo '<option value="' . hsc($race['id_race']) .  '">' . hsc($race['nom_race']) . '</option>';
                } ?>
            </select>


            <?php if ($id_dog == 0): ?>
                <?php if ($role === "admin" || $role === "coach"): ?>
                    <label for="id_utilisateur">Propriétaire</label>
                    <select name="id_utilisateur" id="id_utilisateur" required>
                        <option value="">-- Sélectionner un utilisateur --</option>
                        <?php foreach ($utilisateurs as $user): ?>
                            <option value="<?= hsc($user['id_utilisateur']) ?>">
                                <?= hsc($user['nom_utilisateur']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php elseif ($role === "utilisateur" && isset($_SESSION['id_utilisateur'], $_SESSION['nom_utilisateur'])): ?>
                    <input type="hidden" name="id_utilisateur" value="<?= hsc($_SESSION['id_utilisateur']) ?>">
                    <p>Propriétaire : <?= hsc($_SESSION['nom_utilisateur']) ?></p>
                <?php else: ?>
                    <label for="nom_utilisateur">Propriétaire</label>
                    <input type="text" id="nom_utilisateur" value="<?= hsc($nom_utilisateur) ?>" readonly>
                    <input type="hidden" name="id_utilisateur" value="<?= hsc($id_utilisateur) ?>">
                <?php endif; ?>
            <?php else: ?>
                <label for="nom_utilisateur">Propriétaire</label>
                <input type="text" id="nom_utilisateur" value="<?= hsc($nom_utilisateur) ?>" readonly>
                <input type="hidden" name="id_utilisateur" value="<?= hsc($id_utilisateur) ?>">
            <?php endif; ?>


            <label for="date_inscription">Date d'inscription</label>
            <input type="date" name="date_inscription" id="date_inscription" value="<?= hsc($date) ?>">
            <input type="hidden" name="id_dog" value="<?= hsc($id_dog) ?>">
            <input type="hidden" name="formCU" value="ok">
            <input class="btn__modif" type="submit" value="Enregistrer">

        </form>
        <?php
        switch ($_SESSION['role_name']) {
            case 'admin':
                $redirectUrl = '../admin/dogs-admin.php';
                break;
            case 'coach':
                $redirectUrl = '../coach.php#dogs';
                break;
            case 'utilisateur':
                $redirectUrl = '../dogs-user.php';
                break;
            default:
                $redirectUrl = '../index.php';
        }
        ?>
        <button class="btn2__modif">
            <a href="<?= $redirectUrl ?>">Retour</a>
        </button>
        <!-- <button class="btn2__modif"><a href="../admin/administratif.php#dogs">Retour</a></button> -->
    </div>
</section>

<?php require_once __DIR__ . '/../footer.php' ?>