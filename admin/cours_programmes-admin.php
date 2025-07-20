<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_admin.php";

$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur'; //pour personnalisation

$id_utilisateur = $_SESSION['user_id'] ?? null;

$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';


// $stmt = $db->prepare("SELECT * FROM cours JOIN seance ON cours.id_cours = seance.id_cours");
// $stmt->execute();
// $recordset_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Page actuelle (par défaut 1)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Nombre d'éléments par page
$nbPerPage = isset($_GET['nbPerPage']) ? (int) $_GET['nbPerPage'] : 10;

// Évite la division par zéro
if ($nbPerPage <= 0) {
    $nbPerPage = 10;
}

$offset = ($currentPage - 1) * $nbPerPage;

// Compter le total des enregistrements
$stmtCount = $db->prepare("SELECT COUNT(*) as total FROM cours JOIN seance ON cours.id_cours = seance.id_cours");
$stmtCount->execute();
$totalCours = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];


// Calcul du nombre de pages
$nbPage = ceil($totalCours / $nbPerPage);

$stmt = $db->prepare("SELECT * FROM cours JOIN seance ON cours.id_cours = seance.id_cours LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '../../templates/header.php';
require_once __DIR__ . '/../templates/sidebar.php';
?>


<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé des activités du Club Canin.</h2>
</div>



<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "cours_programmes-admin.php", "page", $nbPerPage); ?>
</div>


<section class="cours_programmé" id="cours_programmé">
    <h2>Gestion des Cours</h2>
    <button class="btn">
        <a href="../cours/form.php">Ajouter un Cours</a>
    </button>
    <?php require_once __DIR__ . '/../templates/form_nb-per-page.php'; ?>

    <div class="table-container">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">
                ✅ Le cours et sa séance ont bien été ajoutés !
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert error">
                ❌ Une erreur est survenue lors de l'ajout du cours. Veuillez réessayer.
            </div>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <!-- <th>Nom du Cours</th> -->
                    <th>Type de cours</th>
                    <th>Description du cours</th>
                    <!-- <th>Âge min</th>
                  <th>Âge max</th> -->
                    <th>Catégorie</th>
                    <th>Race</th>
                    <th>Sexe</th>
                    <th>Places</th>
                    <th>Date </th>
                    <th>Heure</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recordset_cours as $row) { ?>
                    <tr>
                        <td><?= hsc($row['id_cours']); ?></td>
                        <!-- <td><?= hsc($row['nom_cours']); ?></td> -->
                        <td><?= hsc($row['type_cours']); ?></td>
                        <td><?= hsc($row['description_cours']); ?></td>
                        <!-- <td><?= hsc($row['age_min']); ?></td> -->
                        <td><?= hsc($row['categorie_acceptee']); ?></td>
                        <!-- <td><?= hsc($row['age_max']); ?></td> -->
                        <td><?= hsc($row['race_dog']); ?></td>
                        <td><?= hsc($row['sexe_dog']); ?></td>
                        <td><?= hsc($row['places_disponibles']); ?></td>
                        <td><?= hsc($row['date_cours']); ?></td>
                        <td><?= hsc($row['heure_cours']); ?></td>

                        <td>
                            <button class="btn"><a href="../cours/form.php?id=<?= hsc($row['id_cours']) ?>">Modifier</a></button>
                            <button class="btn"><a href="../cours/delete.php?id=<?= hsc($row['id_cours']) ?>" onclick="return confirmationDeleteCours();">Supprimer</a></button>
                        </td>
                    </tr>
                <?php }; ?>
            </tbody>
        </table>
    </div>


</section>







<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "cours_programmes-admin.php", "page", $nbPerPage); ?>
</div>


<?php require_once __DIR__ . '../../templates/footer.php' ?>