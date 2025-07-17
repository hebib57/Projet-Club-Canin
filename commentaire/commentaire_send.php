<?php
// require_once "../admin/include/connect.php";
// session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$id_utilisateur = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_dog = $_POST['id_dog'];
    $id_reservation = $_POST['id_reservation'];
    $nom_cours = $_POST['nom_cours'];
    $type_cours = $_POST['type_cours'];
    $note = $_POST['note'];
    $commentaire = $_POST['commentaire'];
    $progres = $_POST['progres'];
    // $date_commentaire = $_POST['date_commentaire'];

    if (!empty($commentaire) && !empty($id_dog)) {
        try {
            $stmt = $db->prepare("INSERT INTO commentaire (commentaire, note, nom_cours, type_cours, progres, id_dog, id_utilisateur, id_reservation) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                $commentaire,
                // $date_commentaire,
                $note,
                $nom_cours,
                $type_cours,
                $progres,
                $id_dog,
                $id_utilisateur,
                $id_reservation
            ]);

            $success = "Commentaire ajouté avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir les champs.";
    }
}


$stmt = $db->prepare("SELECT * FROM chien");
$stmt->execute();
$dogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT id_reservation, date_reservation FROM reservation ");
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once __DIR__ . '../../templates/header.php'
?>







<section class="modification">
    <h2>Envoyer un nouveau commentaire</h2>

    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <div class="contact-form">

        <form method="POST">
            <label for="id_dog">Chien :</label><br>
            <select name="id_dog" required>

                <?php foreach ($dogs as $dog): ?>
                    <option value="<?= hsc($dog['id_dog']) ?>">
                        <?= hsc($dog['nom_dog']) ?>

                    </option>
                <?php endforeach; ?>
            </select>

            <label for="id_reservation">Réservation :</label><br>
            <select name="id_reservation" required>

                <?php foreach ($reservations as $reservation): ?>
                    <option value="<?= hsc($reservation['id_reservation']) ?>">
                        <?= hsc($reservation['date_reservation']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Nom du cours :</label>
            <input type="text" name="nom_cours">

            <label>Type de cours :</label>
            <input type="text" name="type_cours">

            <!-- <label>Date du cours :</label>
                <input type="date" name="date_cours"> -->

            <label>Note (0-10) :</label>
            <input type="text" name="note" min="0" max="10">

            <label>Commentaire :</label>
            <textarea name="commentaire" rows="4"></textarea>

            <label>Progrès constatés :</label>
            <textarea name="progres" rows="3"></textarea>

            <br><br>
            <button type="submit">Ajouter le commentaire</button>
        </form>

        <br>
        <a href="../evaluations-coach.php" class="btn2__modif">Retour</a>

    </div>
</section>

<?php require_once __DIR__ . '../../templates/footer.php' ?>