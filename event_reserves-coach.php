<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_coach.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour afficher
$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';

// recup les inscriptions aux evenements
// $stmt = $db->prepare("SELECT ie.*, e.nom_event, c.nom_dog   
//                       FROM inscription_evenement ie
//                       JOIN evenement e ON ie.id_event = e.id_event
//                       JOIN chien c ON ie.id_dog = c.id_dog");
// $stmt->execute();
// $recordset_inscription_event = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
//recup les inscriptions aux events
$stmt = $db->prepare("SELECT ie.*, e.nom_event, c.nom_dog   
                      FROM inscription_evenement ie
                      JOIN evenement e ON ie.id_event = e.id_event
                      JOIN chien c ON ie.id_dog = c.id_dog
                      LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_inscription_event = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';
?>



<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
</div>

<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "event_reserves-coach.php", "page", $nbPerPage); ?>
</div>

<section class="inscriptions_event" id="inscriptions_event">
    <h2>Suivi des Inscriptions Évènements</h2>
    <?php require_once __DIR__ . '/templates/form_nb-per-page.php'; ?>
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
                        <td><?= hsc($inscription['id_utilisateur']); ?></td>
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
    <?php displayPagination($nbPage, $currentPage, "event_reserves-coach.php", "page", $nbPerPage); ?>
</div>


<?php require_once __DIR__ . '/templates/footer.php' ?>