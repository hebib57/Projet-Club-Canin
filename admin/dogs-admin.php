<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';


// $query = "
//     SELECT 
//        c.id_dog,
//        c.nom_dog,
//        c.id_race,
//        c.age_dog,
//        c.sexe_dog,
//        u.nom_utilisateur,
//        c.date_inscription,
//        c.photo_dog,
//        r.nom_race,
//        c.categorie

//     FROM 
//         chien c

//         JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
//         JOIN race r ON c.id_race = r.id_race


// ";
// $recordset_dog = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Page actuelle (par défaut 1)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Compter le total des enregistrements
$stmtCount = $db->prepare("SELECT COUNT(*) as total FROM chien ");
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

$stmt = $db->prepare("SELECT 
       c.id_dog,
       c.nom_dog,
       c.id_race,
       c.age_dog,
       c.sexe_dog,
       u.nom_utilisateur,
       c.date_inscription,
       c.photo_dog,
       r.nom_race,
       c.categorie 
       FROM chien c
       JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
       JOIN race r ON c.id_race = r.id_race
       ORDER BY c.date_inscription DESC
       LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_dog = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once __DIR__ . '../../templates/header.php';
require_once __DIR__ . '/../templates/sidebar.php';
?>







<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé des activités du Club Canin.</h2>
</div>




<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "dogs-admin.php", "page", $nbPerPage); ?>
</div>


<section class="dogs" id="dogs">
    <h2>Gestion des Chiens</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du Chien</th>
                    <th>Catégorie</th>
                    <th>Race</th>
                    <th>Âge</th>
                    <th>Sexe</th>
                    <th>Propriétaire</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recordset_dog as $row) { ?>
                    <tr>
                        <td><?= hsc($row['id_dog']); ?></td>
                        <td><?= hsc($row['nom_dog']); ?></td>
                        <td><?= hsc($row['categorie']); ?></td>
                        <td><?= hsc($row['nom_race']); ?></td>
                        <td><?= hsc($row['age_dog']); ?></td>
                        <td><?= hsc($row['sexe_dog']); ?></td>
                        <td><?= hsc($row['nom_utilisateur']); ?></td>
                        <td>
                            <?php $date = new DateTime($row['date_inscription']);
                            echo hsc($date->format('d/m/Y')); ?></td>
                        <td>

                        <td>
                            <button class="btn"><a href="../dogs/form.php?id=<?= hsc($row['id_dog']) ?>">Modifier</a></button>
                            <button class="btn"><a href="../dogs/delete.php?id=<?= hsc($row['id_dog']) ?>" onclick="return confirmationDeleteDog();">Supprimer</a></button>
                        </td>
                    </tr>
                <?php }; ?>

            </tbody>
        </table>
    </div>
    <button class="btn">
        <a href="../dogs/form.php">Ajouter un Chien</a>
    </button>
</section>




<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "dogs-admin.php", "page", $nbPerPage); ?>
</div>

<?php require_once __DIR__ . '../../templates/footer.php' ?>