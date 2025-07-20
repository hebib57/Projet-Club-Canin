<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';

// recup tous les évènements
// $stmt = $db->prepare("SELECT * FROM evenement");
// $stmt->execute();
// $recordset_event = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Page actuelle (par défaut 1)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Compter le total des enregistrements
$stmtCount = $db->prepare("SELECT COUNT(*) as total FROM evenement ");
$stmtCount->execute();
$totalEvents = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

// Nombre d'éléments par page
$nbPerPage = isset($_GET['nbPerPage']) ? (int) $_GET['nbPerPage'] : 10;

// Évite la division par zéro
if ($nbPerPage <= 0) {
    $nbPerPage = 10;
}
// Calcul du nombre de pages
$nbPage = ceil($totalEvents / $nbPerPage);


$offset = ($currentPage - 1) * $nbPerPage;

$stmt = $db->prepare("SELECT * FROM evenement  LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_event = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once __DIR__ . '../../templates/header.php';
require_once __DIR__ . '/../templates/sidebar.php';
?>







<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé des activités du Club Canin.</h2>
</div>


<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "events_programmes-admin.php", "page", $nbPerPage); ?>
</div>


<section class="events" id="events">
    <h2>Gestion des Événements</h2>
    <button class="btn">
        <a href="../evenement/form.php">Ajouter un Événement</a></button>
    <?php require_once __DIR__ . '/../templates/form_nb-per-page.php'; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom de l'Événement</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Places disponibles</th>
                <th>Actions</th>
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
                        <button class="btn"><a href="../evenement/form.php?id=<?= hsc($row['id_event']) ?>">Modifier</a></button>
                        <button class="btn"><a href="../evenement/delete.php?id=<?= hsc($row['id_event']) ?>" onclick="return confirmationDeleteEvent();">Supprimer</a></button>
                    </td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>

</section>


<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "events_programmes-admin.php", "page", $nbPerPage); ?>
</div>


<?php require_once __DIR__ . '../../templates/footer.php' ?>