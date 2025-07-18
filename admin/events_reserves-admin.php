<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';

// Page actuelle (par défaut 1)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Compter le total des enregistrements
$stmtCount = $db->prepare("SELECT COUNT(*) as total FROM evenement ");
$stmtCount->execute();
$totalInscription = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

// Nombre d'éléments par page
$nbPerPage = isset($_GET['nbPerPage']) ? (int) $_GET['nbPerPage'] : 10;

// Évite la division par zéro
if ($nbPerPage <= 0) {
    $nbPerPage = 10;
}
// Calcul du nombre de pages
$nbPage = ceil($totalInscription / $nbPerPage);


$offset = ($currentPage - 1) * $nbPerPage;

$stmt = $db->prepare("SELECT 
     i.id_inscription,
     i.date_inscription,
     u.nom_utilisateur,
     d.nom_dog,
     e.nom_event
     FROM inscription_evenement i 
     JOIN utilisateur u ON i.id_utilisateur = u.id_utilisateur
     JOIN chien d ON i.id_dog = d.id_dog
     JOIN evenement e ON i.id_event = e.id_event
     ORDER BY i.date_inscription DESC
        LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_inscription_event = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '../../templates/header.php';
require_once __DIR__ . '/../templates/sidebar.php';

?>

<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé des activités du Club Canin.</h2>
</div>

<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "events_reserves-admin.php", "page", $nbPerPage); ?>
</div>

<section class="inscriptions_event" id="inscriptions_event">
    <h2>Évènements réservés</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Nom du chien</th>
                    <th>Nom Évènement</th>
                    <!-- <th>Date Séance</th>
                  <th>Heure Séance</th> -->
                    <th>Date Inscription</th>
                    <th>Action</th>
                </tr>
            </thead><?php foreach ($recordset_inscription_event as $inscription): ?>

                <tbody>
                    <tr>
                        <td><?= hsc($inscription['id_inscription']); ?></td>
                        <td><?= hsc($inscription['nom_utilisateur']); ?></td>
                        <td><?= hsc($inscription['nom_dog']); ?></td>
                        <td><?= hsc($inscription['nom_event']); ?></td>
                        <td><?= hsc($inscription['date_inscription']); ?></td>
                        <td>
                            <!-- Option de suppression ou gestion -->
                            <form method="post" action="../inscription_event/delete_inscription_event.php" style="display: inline;">
                                <input type="hidden" name="id_inscription" value="<?= hsc($inscription['id_inscription']); ?>">
                                <button type="submit" class="btn" onclick=" return confirmationDeleteInscription();">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
        </table>
    </div>
</section>

<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "events_reserves-admin.php", "page", $nbPerPage); ?>
</div>

<?php require_once __DIR__ . '../../templates/footer.php' ?>