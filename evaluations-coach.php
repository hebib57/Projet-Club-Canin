<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_coach.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour afficher
$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';

// $stmt = $db->prepare("
//         SELECT c.*, u.prenom_utilisateur, u.nom_utilisateur, d.nom_dog
//         FROM commentaire c
//         JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
//         JOIN chien d ON c.id_dog = d.id_dog

//         ORDER BY c.date_commentaire DESC
//       ");
// $stmt->execute();
// $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM evenement ");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

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

$stmt = $db->prepare("SELECT c.*, u.prenom_utilisateur, u.nom_utilisateur, d.nom_dog
        FROM commentaire c
        JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
        JOIN chien d ON c.id_dog = d.id_dog
        
        ORDER BY c.date_commentaire DESC 
        LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';

?>



<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
</div>



<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "evaluations-coach.php", "page", $nbPerPage); ?>
</div>


<section id="commentaires" class="commentaires">
    <h2>Commentaires de progression</h2>
    <button class="btn"><a href="../commentaire/commentaire_send.php">Nouvelle évaluation</a></button>
    <?php require_once __DIR__ . '/templates/form_nb-per-page.php'; ?>
    <table>

        <thead>
            <tr>
                <th>Chien</th>
                <th>Utilisateur</th>
                <th>Cours</th>
                <th>Note</th>
                <th>Date</th>
                <th>Commentaire</th>
                <th>Progrès</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($commentaires as $commentaire) { ?>
                <tr>
                    <td><?= hsc($commentaire['nom_dog']); ?></td>
                    <td><?= hsc($commentaire['prenom_utilisateur']); ?></td>
                    <td><?= hsc($commentaire['nom_cours']); ?></td>
                    <td><?= hsc($commentaire['note']); ?></td>
                    <td><?= hsc(date('d/m/Y', strtotime($commentaire['date_commentaire']))); ?></td>
                    <td><?= hsc($commentaire['commentaire']); ?></td>
                    <td><?= hsc($commentaire['progres']); ?></td>

                    <td>
                        <button class="btn"><a href="../commentaire/form.php?id=<?= hsc($commentaire['id_commentaire']) ?>">Modifier</a></button>
                        <button class="btn"><a href="../commentaire/commentaire_delete.php?id=<?= hsc($commentaire['id_commentaire']) ?>" onclick="return confirmationDeleteCommentaire();">Supprimer</a></button>
                    </td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
</section>

<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "evaluations-coach.php", "page", $nbPerPage); ?>
</div>



<?php require_once __DIR__ . '/templates/footer.php' ?>