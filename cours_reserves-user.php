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

// Exécution de la requête
// $stmt = $db->prepare($query);
// $stmt->execute([$id_utilisateur]);
// $recordset_reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Page actuelle (par défaut 1)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Récupération du nombre total de réservations pour cet utilisateur
$stmtCount = $db->prepare("SELECT COUNT(*) as total FROM reservation WHERE id_utilisateur = ?");
$stmtCount->execute([$id_utilisateur]);
$totalReservation = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

// Compter le total des enregistrements
// $stmtCount = $db->prepare("SELECT COUNT(*) as total 
//                             FROM reservation 
//                             JOIN seance ON cours.id_cours = seance.id_cours");
// $stmtCount->execute();
// $totalReservation = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

// Nombre d'éléments par page
$nbPerPage = isset($_GET['nbPerPage']) ? (int) $_GET['nbPerPage'] : 10;

// Évite la division par zéro
if ($nbPerPage <= 0) {
    $nbPerPage = 10;
}
// Calcul du nombre de pages
$nbPage = ceil($totalReservation / $nbPerPage);


$offset = ($currentPage - 1) * $nbPerPage;

$sql = "SELECT 
        r.id_reservation,
        r.date_reservation,
        u.nom_utilisateur,
        r.id_dog,
        d.nom_dog,
        s.id_seance,
        s.date_seance,
        s.heure_seance,
        s.places_disponibles,
        s.duree_seance,
        s.statut_seance,
        co.nom_cours,
        co.categorie_acceptee
    FROM 
        reservation r
        JOIN seance s ON r.id_seance = s.id_seance
        JOIN cours co ON s.id_cours = co.id_cours
        JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur
        JOIN chien d ON r.id_dog = d.id_dog
    WHERE r.id_utilisateur = :id_utilisateur
    ORDER BY r.date_reservation DESC
    LIMIT :limit OFFSET :offset";

$stmt = $db->prepare($sql);
$stmt->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';


?>




<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
</div>





<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "cours_reserves-user.php", "page", $nbPerPage); ?>
</div>

<section class="reservations" id="reservations">
    <h2>Cours Réservés</h2>
    <?php require_once __DIR__ . '/templates/form_nb-per-page.php'; ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Catégorie</th>
                    <th>Utilisateur</th>
                    <th>Nom du chien</th>
                    <th>Nom Cours</th>
                    <th>Date Séance</th>
                    <th>Heure Séance</th>
                    <th>Date Réservation</th>
                    <th>Action</th>
                </tr>
            </thead><?php foreach ($recordset_reservation as $reserv): ?>

                <tbody>
                    <tr>
                        <td><?= hsc($reserv['id_reservation']); ?></td>
                        <td><?= hsc($reserv['categorie_acceptee']); ?></td>
                        <td><?= hsc($reserv['nom_utilisateur']); ?></td>
                        <td><?= hsc($reserv['nom_dog']); ?></td>
                        <td><?= hsc($reserv['nom_cours']); ?></td>
                        <td><?= hsc($reserv['date_seance']); ?></td>
                        <td><?= hsc($reserv['heure_seance']); ?></td>
                        <td><?= hsc($reserv['date_reservation']); ?></td>
                        <td>

                            <form method="post" action="../reservations/delete_reservation.php" style="display: inline;">
                                <input type="hidden" name="id_reservation" value="<?= hsc($reserv['id_reservation']); ?>">
                                <button type="submit" class="btn" onclick=" return confirmationDelete();">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
        </table>
    </div>
</section>

<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "cours_reserves-user.php", "page", $nbPerPage); ?>
</div>

<?php require_once __DIR__ . '/templates/footer.php' ?>