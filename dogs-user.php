<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/function.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect_user.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$id_utilisateur = $_SESSION['user_id'] ?? null;
$nom_utilisateur = $_SESSION['nom_utilisateur'] ?? 'Utilisateur';
$prenom_utilisateur = $_SESSION['prenom_utilisateur'] ?? 'Utilisateur';

$utilisateur = [];

//recup chiens utilisateur
// $stmt = $db->prepare("SELECT c.id_dog, c.nom_dog, c.date_naissance, r.nom_race, c.age_dog, c.photo_dog, c.sexe_dog, c.date_inscription, c.categorie
//                        FROM chien AS c 
//                        INNER JOIN race AS r                       
//                        ON c.id_race = r.id_race  

//                        WHERE c.id_utilisateur = ?");
// $stmt->execute([$id_utilisateur]);
// $recordset_dogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Page actuelle (par défaut 1)
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Compter le total des enregistrements
$stmtCount = $db->prepare("SELECT COUNT(*) as total FROM chien WHERE id_utilisateur = :id_utilisateur");
$stmtCount->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
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
       c.date_naissance,
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
       WHERE c.id_utilisateur = :id_utilisateur
       ORDER BY c.date_inscription DESC
       LIMIT :limit OFFSET :offset");
$stmt->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
$stmt->bindValue(':limit', $nbPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recordset_dog = $stmt->fetchAll(PDO::FETCH_ASSOC);



require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/sidebar.php';

?>



<div class="title">
    <h2>Bienvenue <?= hsc(ucfirst($prenom_utilisateur)) ?>, voici le résumé de vos activités au Club.</h2>
</div>





<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "dogs-user.php", "page", $nbPerPage); ?>
</div>

<section class="content">
    <div class="contenair" id="dogs">
        <h2>Mes chiens</h2>
        <?php require_once __DIR__ . '/templates/form_nb-per-page.php'; ?>

        <div class="card">
            <div class="card-header">
                <h3>Mes chiens</h3>
                <a href="../dogs/form.php" class="btn">+ Ajouter un chien</a>
            </div>
            <div class="card-body">
                <ul class="dog-list">
                    <?php foreach ($recordset_dog as $dog): ?>
                        <li class="dog-item">
                            <div class="dog-avatar">
                                <img src=" <?= "../upload/xs_" . hsc($dog['photo_dog']) ?>" alt="photo chien">
                            </div>
                            <div class="dog-info">
                                <h4><?= hsc($dog['nom_dog']) ?></h4>
                                <p>Catégorie : <?= hsc($dog['categorie']) ?></p>
                                <p>Râce : <?= hsc($dog['nom_race']) ?></p>
                                <p>Age : <?= hsc($dog['age_dog']) ?> mois</p>
                                <p>Sexe : <?= hsc($dog['sexe_dog']) ?></p>
                            </div>
                            <div class="dog-actions">
                                <button class="btn btn-details"
                                    data-categorie="<?= hsc($dog['categorie']) ?>"
                                    data-nom="<?= hsc($dog['nom_dog']) ?>"
                                    data-race="<?= hsc($dog['nom_race']) ?>"
                                    data-age="<?= hsc($dog['age_dog']) ?>"
                                    data-sexe="<?= hsc($dog['sexe_dog']) ?>"
                                    data-photo="<?= hsc($dog['photo_dog']) ?>"
                                    data-date-inscription="<?= hsc($dog['date_inscription']) ?>"
                                    data-date-naissance="<?= hsc($dog['date_naissance']) ?>">
                                    Détails
                                </button>
                                <button class="btn"><a href="../dogs/form.php?id=<?= hsc($dog['id_dog']) ?>">Modifier</a></button>
                                <button class="btn"><a href="../dogs/delete.php?id=<?= hsc($dog['id_dog']) ?>" onclick="return confirmationDeleteDog();">Supprimer</a></button>
                            </div>
                        </li>

                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div id="dogModal" class="modal_dog" style="display: none;">
        <div class="modal_detail">
            <span class="close">X</span>
            <img id="modal_photo-dog" alt="chien" style="max-width: 100%;">
            <h4 id="modal-nom"></h4>
            <p><strong>Race :</strong> <span id="modal-race"></span></p>
            <p><strong>Catégorie :</strong> <span id="modal-categorie"></span></p>
            <p><strong>Âge :</strong> <span id="modal-age"></span> mois</p>
            <p><strong>Sexe :</strong> <span id="modal-sexe"></span></p>
            <p><strong>Date d'inscription :</strong> <span id="modal-date-inscription"></span></p>
            <p><strong>Date de naissance :</strong> <span id="modal-date-naissance"></span></p>
        </div>
    </div>
</section>

<div class="pagination"> <!--ceil => arrondi à l'entier supérieur-->
    <?php displayPagination($nbPage, $currentPage, "dogs-user.php", "page", $nbPerPage); ?>
</div>

<?php require_once __DIR__ . '/templates/footer.php' ?>