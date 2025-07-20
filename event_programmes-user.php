<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_user.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$utilisateur = [];

// recup tous les évènements
// $stmt = $db->prepare("SELECT * FROM evenement");
// $stmt->execute();
// $recordset_event = $stmt->fetchAll(PDO::FETCH_ASSOC);

//recup chiens utilisateur
$stmt = $db->prepare("SELECT c.id_dog, c.nom_dog, c.date_naissance, r.nom_race, c.age_dog, c.photo_dog, c.sexe_dog, c.date_inscription, c.categorie
                       FROM chien AS c 
                       INNER JOIN race AS r                       
                       ON c.id_race = r.id_race  
                      
                       WHERE c.id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$dogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Page actuelle (par défaut 1)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Compter le total des enregistrements
$stmtCount = $db->prepare("SELECT COUNT(*) as total FROM evenement ");
$stmtCount->execute();
$totalItems = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

// Nombre d'éléments par page
$nbPerPage = isset($_GET['nbPerPage']) ? (int) $_GET['nbPerPage'] : 10;

// Évite la division par zéro
if ($nbPerPage <= 0) {
    $nbPerPage = 10;
}
// Calcul du nombre de pages
$nbPage = ceil($totalItems / $nbPerPage);


$offset = ($currentPage - 1) * $nbPerPage;

$stmt = $db->prepare("SELECT * FROM evenement  LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_event = $stmt->fetchAll(PDO::FETCH_ASSOC);



require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';
?>



<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
</div>







<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "event_programmes-user.php", "page", $nbPerPage); ?>
</div>

<section class="events" id="events">
    <h2>Événements programmés</h2>
    <?php require_once __DIR__ . '/templates/form_nb-per-page.php'; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom de l'Événement</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Places disponibles</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recordset_event as $row) { ?>
                <tr>
                    <td><?= hsc($row['id_event']); ?></td>
                    <td><?= hsc($row['nom_event']); ?></td>
                    <td><?= hsc($row['date_event']); ?></td>
                    <td><?= hsc($row['heure_event']); ?></td>
                    <td><?= hsc($row['places_disponibles']); ?></td>
                    <td>
                        <form method="post" action="./inscription_event/process_inscription_event.php" style="display:inline;">
                            <input type="hidden" name="id_event" value="<?= hsc($row['id_event']); ?>">
                            <?php if (!in_array($row["id_event"], $utilisateur)): ?>
                                <button type="button" class="btn" onclick="openEventModal(<?= hsc($row['id_event']) ?>)">S'inscrire</button>
                            <?php else: ?>
                                <button type="submit" name="action" value="desinscrire" class="btn">Se désinscrire</button>
                            <?php endif;
                            ?>
                        </form>
                    </td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
</section>
<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "event_programmes-user.php", "page", $nbPerPage); ?>
</div>
<!-- Modal pour choisir un chien pour l'inscription à un évènement-->
<div id="inscriptionModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeEventModal()">&times;</span>
        <h3>Choisissez un chien pour cet évènement</h3>

        <form method="post" action="../inscription_event/process_inscription_event.php">
            <input type="hidden" name="id_event" id="modal_id_event">

            <input type="hidden" name="action" value="inscrire">
            <label for="id_dog">Votre chien :</label>
            <select name="id_dog" id="id_dog_event" required>
                <option value="">-- Sélectionner un chien --</option>

                <?php
                foreach ($dogs as $dog): ?>
                    <option value="<?= hsc($dog['id_dog']) ?>"><?= hsc($dog['nom_dog']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="action" value="inscrire" class="btn">Confirmer l'inscription</button>
            <button type="button" class="btn" onclick="closeEventModal()">Annuler</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php' ?>