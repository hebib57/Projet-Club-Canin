<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_user.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$utilisateur = [];

// $query = "
// SELECT 
//         r.id_reservation,
//         r.date_reservation,
//         u.nom_utilisateur,
//         r.id_dog,
//         d.nom_dog,
//         s.id_seance,
//         s.date_seance,
//         s.heure_seance,
//         s.places_disponibles,
//         s.duree_seance,
//         s.statut_seance,
//         co.nom_cours,
//         co.categorie_acceptee
//     FROM 
//         reservation r
//         JOIN seance s ON r.id_seance = s.id_seance
//         JOIN cours co ON s.id_cours = co.id_cours
//         JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur
//         JOIN chien d ON r.id_dog = d.id_dog
//     WHERE r.id_utilisateur = ?
//     ORDER BY r.date_reservation DESC

//     ";

// // Exécution de la requête
// $stmt = $db->prepare($query);
// $stmt->execute([$id_utilisateur]);
// $recordset_reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);

//--------------------------------------------------------------------//



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
// $stmt = $db->prepare("SELECT 
//     e.id_event,
//     e.nom_event, 
//     e.date_event, 
//     e.heure_event, 
//     e.places_disponibles,
//     c.nom_dog,
//     c.id_dog
// FROM 
//     inscription_evenement ie
// JOIN 
//     evenement e ON ie.id_event = e.id_event
// JOIN 
//     chien c ON ie.id_dog = c.id_dog
// WHERE 
//     c.id_utilisateur = ?
// ORDER BY 
//     e.date_event DESC
//                       LIMIT :limit OFFSET :offset");
// $stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
// $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
// $stmt->execute([$id_utilisateur]);
// $event_user_dog = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "
SELECT 
    e.id_event,
    e.nom_event, 
    e.date_event, 
    e.heure_event, 
    e.places_disponibles,
    c.nom_dog,
    c.id_dog
FROM 
    inscription_evenement ie
JOIN 
    evenement e ON ie.id_event = e.id_event
JOIN 
    chien c ON ie.id_dog = c.id_dog
WHERE 
    c.id_utilisateur = ?
ORDER BY 
    e.date_event DESC
";
$stmt = $db->prepare($sql);
$stmt->execute([$id_utilisateur]);
$event_user_dog = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';


?>




<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
</div>



<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "event_reserves-user.php", "page", $nbPerPage); ?>
</div>









<section class="inscriptions_event" id="events">
    <h2>Événements réservés</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom de l'Événement</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Places disponibles</th>
                <th>Nom du chien</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($event_user_dog as $row) { ?>
                <tr>
                    <td><?= hsc($row['id_event']); ?></td>
                    <td><?= hsc($row['nom_event']); ?></td>
                    <td><?= hsc($row['date_event']); ?></td>
                    <td><?= hsc($row['heure_event']); ?></td>
                    <td><?= hsc($row['places_disponibles']); ?></td>
                    <td><?= hsc($row['nom_dog']); ?></td>
                    <td>
                        <form method="post" action="./inscription_event/process_inscription_event.php" onsubmit="return confirmDesinscriptionEvent();" style="display:inline;">
                            <input type="hidden" name="id_event" value="<?= hsc($row['id_event']); ?>">
                            <input type="hidden" name="id_dog" value="<?= hsc($row['id_dog']); ?>">
                            <input type="hidden" name="action" value="desinscrire">
                            <button type="submit" class="btn">Se désinscrire</button>
                        </form>
                    </td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
</section>

<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "event_reserves-user.php", "page", $nbPerPage); ?>
</div>


<?php require_once __DIR__ . '/templates/footer.php' ?>