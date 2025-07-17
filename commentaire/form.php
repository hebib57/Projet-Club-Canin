<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$id_utilisateur = $_SESSION['user_id'] ?? null;
$id_commentaire = $_GET['id'] ?? null;
$success = $error = "";
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';

if ($id_commentaire && is_numeric($id_commentaire)) {

    // Traitement du formulaire
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nom_cours = $_POST['nom_cours'] ?? '';
        $type_cours = $_POST['type_cours'] ?? '';
        $note = $_POST['note'] ?? '';
        $commentaire = $_POST['commentaire'] ?? '';
        $progres = $_POST['progres'] ?? '';

        $stmt = $db->prepare("UPDATE commentaire SET 
            nom_cours = :nom_cours,
            type_cours = :type_cours,
            note = :note,
            commentaire = :commentaire,
            progres = :progres
            WHERE id_commentaire = :id_commentaire");

        $stmt->bindValue(':nom_cours', $nom_cours);
        $stmt->bindValue(':type_cours', $type_cours);
        $stmt->bindValue(':note', $note);
        $stmt->bindValue(':commentaire', $commentaire);
        $stmt->bindValue(':progres', $progres);
        $stmt->bindValue(':id_commentaire', $id_commentaire);


        if ($stmt->execute()) {
            $success = "Commentaire mis à jour avec succès.";
        } else {
            $error = "Erreur lors de la mise à jour.";
        }
    }

    // Récupération des données existantes pour pré-remplir le formulaire
    $stmt = $db->prepare("SELECT * FROM commentaire WHERE id_commentaire = :id_commentaire");
    $stmt->bindValue(':id_commentaire', $id_commentaire);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        // Pré-remplir les champs du formulaire
        $nom_cours = $row['nom_cours'];
        $type_cours = $row['type_cours'];
        $note = $row['note'];
        $commentaire = $row['commentaire'];
        $progres = $row['progres'];
    } else {
        $error = "Commentaire introuvable.";
    }
} else {
    $error = "ID de commentaire invalide.";
}

require_once __DIR__ . '../../templates/header.php';
require_once __DIR__ . '/../templates/sidebar.php';
?>





<section class="modification">
    <h2>Modifier le commentaire</h2>
    <div class="contact-form">
        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST">
            <label>Nom du cours :</label>
            <input type="text" name="nom_cours" value="<?= hsc($nom_cours) ?>">

            <label>Type de cours :</label>
            <input type="text" name="type_cours" value="<?= hsc($type_cours) ?>">

            <label>Note (0-10) :</label>
            <input type="number" name="note" min="0" max="10" value="<?= hsc($note) ?>">

            <label>Commentaire :</label>
            <textarea name="commentaire" rows="4"><?= hsc($commentaire) ?></textarea>

            <label>Progrès constatés :</label>
            <textarea name="progres" rows="3"><?= hsc($progres) ?></textarea>

            <br><br>
            <button type="submit">Modifier le commentaire</button>
        </form>

        <br>
        <a href="../evaluations-coach.php" class="btn2__modif">Retour</a>
    </div>
</section>


<?php require_once __DIR__ . '../../templates/footer.php' ?>